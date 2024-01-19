<?php

namespace App\Http\Controllers;

use App\Models\GlobalConfig;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\UpdateApplicationSettingRequest;
use App\Http\Requests\UpdateBasicSettingRequest;
use App\Http\Requests\UpdateMeetingSettingRequest;
use App\Http\Requests\UpdateSettingPaymentGatewaysRequest;
use App\Http\Requests\UpdateCssSettingRequest;
use App\Http\Requests\UpdateJsSettingRequest;
use App\Http\Requests\UpdateRecaptchaSettingRequest;
use App\Http\Requests\UpdateSmtpSettingRequest;
use App\Mail\TestSMTP;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class GlobalConfigController extends Controller
{
    /**
     * Manage site settings.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.global-config.basic', [
            'page' => __('Global Configuration - Basic'),
            'route' => 'basic',
        ]);
    }

    //update global configuration
    public function updateBasic(UpdateBasicSettingRequest $request)
    {
        if (isDemoMode()) return back()->with('error', __('This feature is not available in demo mode'));

        $rows = [
            'APPLICATION_NAME', 'PRIMARY_COLOR', 'PRIMARY_LOGO', 'SECONDARY_LOGO', 'FAVICON'
        ];

        foreach ($rows as $row) {
            if ($row == 'PRIMARY_LOGO' || $row == 'SECONDARY_LOGO' || $row == 'FAVICON') {
                $file = $request->file($row);
                if ($file && $file->isValid()) {
                    $file->storeAs('public/images', $row . '.png');
                }
            } else {
                GlobalConfig::where('key', $row)->update(['value' => $request->input($row)]);
                Cache::forget('settings');
            }
        }

        return back()->with('success', __('Settings saved.'));
    }

    //show application form
    public function application()
    {
        return view('admin.global-config.application', [
            'page' => __('Global Configuration - Application'),
            'route' => 'application',
        ]);
    }

    //update application data
    public function updateApplication(UpdateApplicationSettingRequest $request)
    {
        if (isDemoMode()) return back()->with('error', __('This feature is not available in demo mode'));

        $rows = [
            'AUTH_MODE', 'COOKIE_CONSENT', 'LANDING_PAGE', 'GOOGLE_ANALYTICS_ID', 'SOCIAL_INVITATION', 'PAYMENT_MODE', 'REGISTRATION', 'VERIFY_USERS'
        ];

        foreach ($rows as $row) {
            GlobalConfig::where('key', $row)->update(['value' => $request->input($row)]);
        }
        Cache::forget('settings');
        return back()->with('success', __('Settings saved.'));
    }

    //show meeting form
    public function meeting()
    {
        return view('admin.global-config.meeting', [
            'page' => __('Global Configuration - Meeting'),
            'route' => 'meeting',
        ]);
    }

    //update meeting data
    public function updateMeeting(UpdateMeetingSettingRequest $request)
    {
        if (isDemoMode()) return back()->with('error', __('This feature is not available in demo mode'));

        $rows = [
            'MODERATOR_RIGHTS', 'DEFAULT_USERNAME', 'SIGNALING_URL', 'END_URL'
        ];

        foreach ($rows as $row) {
            GlobalConfig::where('key', $row)->update(['value' => $request->input($row)]);
        }
        Cache::forget('settings');
        return back()->with('success', __('Settings saved.'));
    }

    /**
     * Show the Payment gateway settings form.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paymentGateways()
    {
        return view('admin.payment-gateways', ['page' => __('Payment Gateways')]);
    }

    /**
     * Update the Payment gateway settings.
     *
     * @param UpdateSettingPaymentGatewaysRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePaymentGateways(UpdateSettingPaymentGatewaysRequest $request)
    {
        if (isDemoMode()) return back()->with('error', __('This feature is not available in demo mode'));

        $rows = [
            'STRIPE', 'STRIPE_KEY', 'STRIPE_SECRET', 'STRIPE_WH_SECRET',
            'PAYPAL', 'PAYPAL_MODE', 'PAYPAL_CLIENT_ID', 'PAYPAL_SECRET', 'PAYPAL_WEBHOOK_ID',
        ];
        foreach ($rows as $row) {
            GlobalConfig::where('key', $row)->update(['value' => $request->input($row)]);
        }
        Cache::forget('settings');
        return back()->with('success', __('Settings saved.'));
    }

    //show js form
    public function customJs()
    {
        return view('admin.global-config.js', [
            'page' => __('Global Configuration - Custom JS'),
            'route' => 'js',
        ]);
    }

    //update js data
    public function updateJs(UpdateJsSettingRequest $request)
    {
        if (isDemoMode()) return back()->with('error', __('This feature is not available in demo mode'));

        $rows = [
            'CUSTOM_JS'
        ];

        foreach ($rows as $row) {
            GlobalConfig::where('key', $row)->update(['value' => $request->input($row)]);
        }
        Cache::forget('settings');
        return back()->with('success', __('Settings saved.'));
    }

    //show js form
    public function customCss()
    {
        return view('admin.global-config.css', [
            'page' => __('Global Configuration - Custom CSS'),
            'route' => 'css',
        ]);
    }

    //update js data
    public function updateCss(UpdateCssSettingRequest $request)
    {
        if (isDemoMode()) return back()->with('error', __('This feature is not available in demo mode'));

        $rows = [
            'CUSTOM_CSS'
        ];

        foreach ($rows as $row) {
            GlobalConfig::where('key', $row)->update(['value' => $request->input($row)]);
        }
        Cache::forget('settings');
        return back()->with('success', __('Settings saved.'));
    }

    //show smtp form
    public function smtp()
    {
        return view('admin.global-config.smtp', [
            'page' => __('Global Configuration - SMTP'),
            'route' => 'smtp',
        ]);
    }

    //update smtp data
    public function updateSmtp(UpdateSmtpSettingRequest $request)
    {
        if (isDemoMode()) return back()->with('error', __('This feature is not available in demo mode'));

        $rows = [
            'MAIL_MAILER', 'MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME', 'MAIL_PASSWORD', 'MAIL_ENCRYPTION', 'MAIL_FROM_ADDRESS'
        ];

        foreach ($rows as $row) {
            GlobalConfig::where('key', $row)->update(['value' => $request->input($row)]);
        }
        Cache::forget('settings');

        return back()->with('success', __('Settings saved.'));
    }

    //API token
    public function api()
    {
        if (!getSetting('API_TOKEN')) {
            $model = GlobalConfig::where('key', 'API_TOKEN')->first();
            $model->value = Str::random(60);
            $model->save();
            Cache::forget('settings');
        }

        return view('admin.global-config.api', ['page' => __('API Token'), 'route' => 'api']);
    }

    //test SMTP
    public function testSmtp (Request $request) {
        if (isDemoMode()) return json_encode(['success' => false, 'error' => __('This feature is not available in demo mode')]);

        try {
            Mail::to($request->email)->send(new TestSMTP());
            return json_encode(['success' => true]);
        } catch (Exception $e) {
            return json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    //show recaptcha form
    public function recaptcha()
    {
        return view('admin.global-config.recaptcha', [
            'page' => __('Global Configuration - Google reCAPTCHA'),
            'route' => 'recaptcha',
        ]);
    }

    //update recaptcha data
    public function updateRecaptcha(UpdateRecaptchaSettingRequest $request)
    {
        if (isDemoMode()) return back()->with('error', __('This feature is not available in demo mode'));

        $rows = [
            'GOOGLE_RECAPTCHA', 'GOOGLE_RECAPTCHA_KEY', 'GOOGLE_RECAPTCHA_SECRET'
        ];

        foreach ($rows as $row) {
            GlobalConfig::where('key', $row)->update(['value' => $request->input($row)]);
        }

        Cache::forget('settings');

        return back()->with('success', __('Settings saved.'));
    }
}
