<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\UserCode;

class TwoFAController extends Controller
{
    /**
     * TFA index
     *
     * @return response()
     */
    public function index()
    {
        if ((auth()->user() && auth()->user()->tfa == 'inactive') || Session::has('user_tfa')) {
            return redirect()->route('dashboard');
        }

        return view('tfa-verify', [
            'page' => __('Verify your code'),
        ]);
    }

    /**
     * TFA store
     *
     * @return response()
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'=>'required',
        ]);
  
        $code = UserCode::where('user_id', auth()->user()->id)
                        ->where('code', $request->code)
                        ->first();
  
        if (!is_null($code)) {
            Session::put('user_tfa', auth()->user()->id);
            return redirect()->route('dashboard');
        }

        return back()->with('error', __('The code you entered is incorrect'));
    }

    /**
     * TFA resend
     *
     * @return response()
     */
    public function resend()
    {
        auth()->user()->generateCode();
        return back()->with('success', __('The code has been resent'));
    }
}
