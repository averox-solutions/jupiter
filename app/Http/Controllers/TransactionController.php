<?php

namespace App\Http\Controllers;

use App\Models\Payment;

class TransactionController extends Controller
{
    public function index()
    {
        $payment = Payment::paginate(config('app.pagination'));

        return view('admin.transaction', ['page' => __('Transaction'), 'transaction' => $payment]);
    }
}
