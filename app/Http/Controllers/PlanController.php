<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use App\Models\TaxRate;
use App\Models\Coupon;
use App\Models\Currency;

class PlanController extends Controller
{
    /**
     * List the plans.
     */
    public function index(Request $request)
    {
        $plans = Plan::paginate(config('app.pagination'));
        return view('admin.plans.index', ['page' => __('Plans'), 'plans' => $plans]);
    }

    /**
     * Show the create Plan form.
     */
    public function create()
    {
        $coupons = Coupon::all();

        $taxRates = TaxRate::all();

        $currencies = Currency::all()->pluck('name', 'code');

        return view('admin.plans.new', ['page' => __('Create Plan'), 'coupons' => $coupons, 'taxRates' => $taxRates, 'currencies' => $currencies]);
    }

    /**
     * Show the edit Plan form.
     */
    public function edit($id)
    {
        $plan = Plan::where('id', $id)->firstOrFail();

        $coupons = Coupon::all();

        $taxRates = TaxRate::all();

        $currencies = Currency::all()->pluck('name', 'code');

        return view('admin.plans.edit', ['page' => __('Edit Plan'), 'plan' => $plan, 'coupons' => $coupons, 'taxRates' => $taxRates, 'currencies' => $currencies]);
    }

    /**
     * Store the Plan.
     */
    public function store(StorePlanRequest $request)
    {
        if (isDemoMode()) return back()->with('error', __('This feature is not available in demo mode'));

        $plan = new Plan;
        $plan->name = $request->input('name');
        $plan->description = $request->input('description');
        $plan->amount_month = round($request->input('amount_month'), 2);
        $plan->amount_year = round($request->input('amount_year'), 2);
        $plan->currency = $request->input('currency');
        $plan->coupons = $request->input('coupons');
        $plan->tax_rates = $request->input('tax_rates');
        $plan->features = $request->input('features');
        $plan->save();

        return redirect()->route('admin.plans')->with('success', __('Data created successfully'));
    }

    /**
     * Update the Plan.
     */
    public function update(UpdatePlanRequest $request, $id)
    {
        if (isDemoMode()) return back()->with('error', __('This feature is not available in demo mode'));
        
        $plan = Plan::findOrFail($id);

        if ($plan->hasPrice()) {
            $plan->amount_month = round($request->input('amount_month'), 2);
            $plan->amount_year = round($request->input('amount_year'), 2);
            $plan->currency = $request->input('currency');
            $plan->coupons = $request->input('coupons');
            $plan->tax_rates = $request->input('tax_rates');
        }
        $plan->name = $request->input('name');
        $plan->description = $request->input('description');
        $plan->features = $request->input('features');
        $plan->save();

        return redirect()->route('admin.plans')->with('success', __('Data updated successfully'));
    }

    //udpate coupon status
    public function updateStatus(Request $request)
    {
        if (isDemoMode()) return json_encode(['success' => false, 'error' => __('This feature is not available in demo mode')]);
        
        $plan = Plan::find($request->id);
        $plan->status = $request->checked == 'true' ? 1 : 0;

        if ($plan->save()) {
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }
}
