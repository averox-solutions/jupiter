<?php

namespace App\Http\Controllers;

use App\Mail\UserCreation;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    /**
     * Show all the users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = DB::table('users')
            ->select('users.id', 'username', 'email', 'users.status', 'users.created_at', 'plans.name')
            ->where('role', 'end-user')
            ->join('plans', 'users.plan_id', 'plans.id')
            ->paginate(config('app.pagination'));

        return view('admin.user.index', [
            'page' => __('Users'),
            'users' => $users,
        ]);
    }

    //udpate user status and return json data
    public function updateUserStatus(Request $request)
    {
        if (isDemoMode()) return json_encode(['success' => false, 'error' => __('This feature is not available in demo mode')]);

        $user = User::find($request->id);
        $user->status = $request->checked == 'true' ? 'active' : 'inactive';

        if ($user->save()) {
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }

    //delete user and return json data
    public function deleteUser(Request $request)
    {
        if (isDemoMode()) return json_encode(['success' => false, 'error' => __('This feature is not available in demo mode')]);

        $user = User::find($request->id);

        if ($user->delete()) {
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }

    //show create user form
    public function createUserForm()
    {
        return view('admin.user.create', [
            'page' => __('Create User'),
        ]);
    }

    //create user and send an email
    public function createUser(StoreUserRequest $request)
    {
        $model = new User();
        $model->username = $request->username;
        $model->email = $request->email;
        $model->password = Hash::make($request->password);
        $model->save();

        Mail::to($request->email)->send(new UserCreation($request->all()));
        if (getSetting('VERIFY_USERS') == 'enabled') {
            $model->sendEmailVerificationNotification();
        }
        return redirect('/users')->with('success', __('User created'));
    }
}
