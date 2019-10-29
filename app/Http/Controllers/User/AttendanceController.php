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

    /**
      * Display a listing of the resource.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $today = Carbon::now()->format('Y-m-d');
        $attendance = $this->attendance->where('user_id', $userId)->where('date_time', $today)->first();
        return view('user.attendance.index', compact('attendance'));
    }

    /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
    public function startTime(Request $request)
    {
        $inputs = $request->all();
        $inputs['user_id'] = Auth::id();
        $inputs['absent_status'] = 0;
        $this->attendance->fill($inputs)->save();
        return redirect()->route('attendance.index');
    }

    /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
    public function endTime(Request $request, $id)
    {
        $inputs = $request->all();
        $idAttendance = $this->attendance->find($id);
        $idAttendance->fill($inputs)->save();
        return redirect()->route('attendance.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function absencePage($id = null)
    {
        $attendance = $this->attendance->find($id);
        return view('user.attendance.absence', compact('attendance'));
    }

    /**
     * @param AttendanceRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function absence(AttendanceRequest $request)
    {
        $inputs = $request->all();
        $inputs['user_id'] = Auth::id();
        if (!empty($inputs['id'])) {
            $this->attendance->find($inputs['id'])->fill($inputs)->save();
        } else {
            $this->attendance->fill($inputs)->save();
        }
        return redirect()->route('attendance.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function modifyPage($id)
    {
        $attendance = $this->attendance->find($id);
        return view('user.attendance.modify', compact('attendance'));
    }

    /**
     * @param AttendanceRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function modify(AttendanceRequest $request)
    {
        $inputs = $request->all();
        $inputs['user_id'] = Auth::id();
        $this->attendance->find($inputs['id'])->fill($inputs)->save();
        return redirect()->route('attendance.index');
    }

     /**
     * @param AttendanceRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
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
