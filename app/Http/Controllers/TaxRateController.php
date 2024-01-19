<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreTaxRateRequest;
use App\Http\Requests\UpdateTaxRateRequest;
use App\Models\TaxRate;

class TaxRateController extends Controller
{
    /**
     * List the Tax Rates.
     */
    public function index(Request $request)
    {
        $taxRates = TaxRate::paginate(config('app.pagination'));

        return view('admin.tax-rates.index', ['page' => __('Tax Rates'), 'taxRates' => $taxRates]);
    }

    /**
     * Show the create Tax Rate form.
     */
    public function create()
    {
        return view('admin.tax-rates.new', ['page' => __('Create Tax Rate')]);
    }

    /**
     * Show the edit Tax Rate form.
     */
    public function edit($id)
    {
        $taxRate = TaxRate::where('id', $id)->firstOrFail();

        return view('admin.tax-rates.edit', ['page' => __('Edit Tax Rate'), 'taxRate' => $taxRate]);
    }

    /**
     * Store the Tax Rate.
     */
    public function store(StoreTaxRateRequest $request)
    {
        $taxRate = new TaxRate;

        $taxRate->name = $request->input('name');
        $taxRate->type = $request->input('type');
        $taxRate->percentage = $request->input('percentage');
        $taxRate->regions = $request->input('regions');

        $taxRate->save();

        return redirect()->route('admin.tax_rates')->with('success', __('Data created successfully'));
    }

    /**
     * Update the Tax Rate.
     */
    public function update(UpdateTaxRateRequest $request, $id)
    {
        $taxRate = TaxRate::findOrFail($id);

        $taxRate->regions = $request->input('regions');

        $taxRate->save();

        return redirect()->route('admin.tax_rates')->with('success', __('Data updated successfully'));
    }

    //udpate coupon status
    public function updateStatus(Request $request)
    {
        $taxRate = TaxRate::find($request->id);
        $taxRate->status = $request->checked == 'true' ? 1 : 0;

        if ($taxRate->save()) {
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }
}
