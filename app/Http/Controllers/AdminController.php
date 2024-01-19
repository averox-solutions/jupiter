<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\GlobalConfig;
use Illuminate\Support\Facades\Cache;
use App\Models\Payment;

class AdminController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [];

        $users = User::where('role', '<>', 'admin')->get();

        $freeUsers = $users->filter(function ($user) {
            return $user->plan_payment_gateway == '';
        });

        $paidUsers = $users->filter(function ($user) {
            return $user->plan_payment_gateway != '';
        });

        $data['meeting'] = Meeting::count();
        $data['user'] = $users->count();
        $data['income'] = Payment::sum('amount');
        $data['freeUsers'] = count($freeUsers);
        $data['paidUsers'] = count($paidUsers);

        $incomeGraph = Payment::select(DB::raw("SUM(amount) as income"), DB::raw("MONTH(created_at) as month"))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('income', 'month')
            ->toArray();

        $userGraph = User::select(DB::raw("count(*) as count"), DB::raw("MONTH(created_at) as month"))
            ->where('role', 'end-user')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $meetingGraph = Meeting::select(DB::raw("count(*) as count"), DB::raw("MONTH(created_at) as month"))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $data['montlyIncome'] = json_encode($incomeGraph);
        $data['userGraph'] = json_encode($userGraph);
        $data['meetingGraph'] = json_encode($meetingGraph);

        return view('admin.dashboard', [
            'page' => __('Dashboard'),
            'data' => $data,
        ]);
    }

    /**
     * Manage update.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update()
    {
        return view('admin.update', [
            'page' => __('Manage Update'),
        ]);
    }

    //check if an update is available or not
    public function checkForUpdate()
    {
        if (isDemoMode()) return json_encode(['success' => false, 'error' => __('This feature is not available in demo mode')]);

        $license_notifications_array = aplVerifyLicense('', true);

        if ($license_notifications_array['notification_case'] != "notification_license_ok") {
            return json_encode(['success' => false, 'error' => $license_notifications_array['notification_text']]);
        }

        $current_version = getSetting('VERSION');
        $all_versions = ausGetAllVersions();
        $changelog = [];

        foreach ($all_versions['notification_data']['product_versions'] as $version) {
            if ($current_version < $version['version_number']) {
                $changelog[$version['version_number']] = ausGetVersion($version['version_number'])['notification_data']['version_changelog'];
            };
        }

        if ($changelog) {
            return json_encode(['success' => true, 'version' => $all_versions['notification_data']['product_versions'][0]['version_number'], 'changelog' => $changelog]);
        } else {
            return json_encode(['success' => false, 'version' => $current_version]);
        }
    }

    //check if an update is available or not
    public function downloadUpdate()
    {
        if (isDemoMode()) return json_encode(['success' => false, 'error' => __('This feature is not available in demo mode')]);

        $license_notifications_array = aplVerifyLicense('', true);

        if ($license_notifications_array['notification_case'] != "notification_license_ok") {
            return json_encode(['success' => false, 'error' => $license_notifications_array['notification_text']]);
        }

        if (date('Y-m-d') > json_decode($license_notifications_array['notification_data'])->support) {
            return json_encode(['success' => false, 'error' => __('Your support has expired. Please renew your support to continue enjoying auto updates.')]);
        }

        $current_version = getSetting('VERSION');
        $all_versions = ausGetAllVersions();
        $version_numbers = [];

        foreach ($all_versions['notification_data']['product_versions'] as $version) {
            if ($current_version < $version['version_number']) array_unshift($version_numbers, $version['version_number']);
        }

        foreach ($version_numbers as $version) {
            $download_notifications_array = ausDownloadFile('version_upgrade_file', $version);

            if ($download_notifications_array['notification_case'] == "notification_operation_ok") {
                $query_notifications_array = ausFetchQuery('upgrade', $version);

                if ($query_notifications_array['notification_case'] == "notification_operation_ok" && $query_notifications_array['notification_data']) {
                    DB::unprepared($query_notifications_array['notification_data']);
                }

                $model = GlobalConfig::where('key', 'VERSION')->first();
                $model->value = $version;
                $model->save();

                Cache::flush();
                Artisan::call('migrate', ['--force' => true]);
            } else {
                return json_encode(['success' => false, 'error' => $download_notifications_array['notification_text']]);
            }
        }

        return json_encode(['success' => true]);
    }

    /**
     * Manage license.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function license()
    {
        return view('admin.license', [
            'page' => __('Manage License'),
        ]);
    }

    //verify license
    public function verifyLicense()
    {
        if (isDemoMode()) return json_encode(['success' => false, 'error' => __('This feature is not available in demo mode')]);

        $license_notifications_array = aplVerifyLicense('', true);

        if ($license_notifications_array['notification_case'] == "notification_license_ok") {
            return json_encode(['success' => true, 'type' => $license_notifications_array['notification_data']]);
        } else {
            return json_encode(['success' => false, 'error' => $license_notifications_array['notification_text']]);
        }
    }

    //uninstall license
    public function uninstallLicense()
    {
        if (isDemoMode()) return json_encode(['success' => false, 'error' => __('This feature is not available in demo mode')]);

        $license_notifications_array = aplUninstallLicense('');

        if ($license_notifications_array['notification_case'] == "notification_license_ok") {
            return json_encode(['success' => true]);
        } else {
            return json_encode(['success' => false, 'error' => $license_notifications_array['notification_text']]);
        }
    }

    /**
     * Show signaling server page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function signaling()
    {
        $url = getSetting('SIGNALING_URL');

        return view('admin.signaling', [
            'page' => __('Signaling Server'),
            'url' => $url,
        ]);
    }

    //check signaling status
    public function checkSignaling()
    {
        $url = getSetting('SIGNALING_URL');
        $status = __('Running');

        try {
            get_headers($url);
        } catch (\Exception $e) {
            $status = __('Unreachable');
        }

        return json_encode(['status' => $status]);
    }
}
