<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class PricingController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkPaymentMode');
    }

    /**
     * Show the Pricing page.
     */
    public function index()
    {
        $plans = Plan::where('status', 1)->get();

        return view('pricing.index', ['plans' => $plans, 'page' => __('Pricing')]);
    }
}
