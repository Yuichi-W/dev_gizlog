<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\User\AttendanceRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    protected $attendance;

    public function __construct(Attendance $attendance, User $user)
    {
        $this->middleware('auth');
        $this->attendance = $attendance;
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $userId = Auth::id();
        $today = Carbon::now()->format('Y-m-d');
        $attendance = $this->attendance->where('user_id', $userId)->where('date_time', $today)->first();
        // dd($attendance);
        return view('user.attendance.index', compact('attendance'));
    }

    public function startTime(Request $request)
    {
        $inputs = $request->all();
        // dd($inputs);
        $inputs['user_id'] = Auth::id();
        $inputs['absent_status'] = 0;
        // dd($inputs);
        // dd($this->attendance->timestamps);
        $this->attendance->timestamps = false;
        $this->attendance->fill($inputs)->save();
        return redirect()->route('attendance.index');
    }

    public function endTime(Request $request, $id)
    {
        $inputs = $request->all();
        dd($inputs);
        // $this->attendance->timestamps = false;
        $this->attendance->find($id)->fill($inputs)->save();
        return redirect()->route('attendance.index');
    }

    public function absencePage()
    {
        return view('user.attendance.absence');
    }

    public function absence(AttendanceRequest $request)
    {
        $inputs = $request->all();
        $this->attendance->create($inputs);
        return redirect()->route('attendance.index');
    }

    public function modifyPage()
    {
        return view('user.attendance.modify');
    }

    public function modify(AttendanceRequest $request)
    {
        $inputs = $request->all();
        $this->attendance->create($inputs);
        return redirect()->route('attendance.index');
    }

    public function mypage(Request $request)
    {
        $userId = Auth::id();
        $attendances = $this->attendance->fetchUserAttendances($userId)->get();
        $dateSum = $this->attendance
            ->fetchUserAttendances($userId)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->count();
        $attendanceMinutesTotal=0;
        foreach ($attendances as $attendance)
        {
            if (isset($attendance->start_time) && isset($attendance->end_time)) {
                $attendanceMinutes = $attendance->start_time
                    ->diffInminutes($attendance->end_time);
                $attendanceMinutesTotal += $attendanceMinutes;
            }
        }
        $attendanceHours = round($attendanceMinutesTotal/60);
        return view('user.attendance.mypage', compact('attendances', 'dateSum', 'attendanceHours'));
    }
}
