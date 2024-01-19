<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    /**
     * Show all the meetings.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $meetings = DB::table('meetings')
            ->select('meetings.*', 'users.username')
            ->join('users', 'meetings.user_id', 'users.id')
            ->paginate(config('app.pagination'));

        return view('admin.meeting.index', [
            'page' => __('Meetings'),
            'meetings' => $meetings,
        ]);
    }

    //udpate meeting status and return json
    public function updateMeetingStatus(Request $request)
    {
        if (isDemoMode()) return json_encode(['success' => false, 'error' => __('This feature is not available in demo mode')]);

        $meeting = Meeting::find($request->id);
        $meeting->status = $request->checked == 'true' ? 'active' : 'inactive';

        if ($meeting->save()) {
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }

    //delete meeting and return json
    public function deleteMeeting(Request $request)
    {
        if (isDemoMode()) return json_encode(['success' => false, 'error' => __('This feature is not available in demo mode')]);

        $meeting = Meeting::find($request->id);

        if ($meeting->delete()) {
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }
}
