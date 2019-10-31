<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Http\Requests\User\AttendanceRequest;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected $attendance;

    public function __construct(Attendance $attendance)
    {
        $this->middleware('auth');
        $this->attendance = $attendance;
    }

    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\Response
      */
    public function index()
    {
        $attendance = $this->attendance->fetchTodayUserAttendance();
        return view('user.attendance.index', compact('attendance'));
    }

    /**
      * Store a newly created resource in storage.
      *
      * @return \Illuminate\Http\Response
      */
    public function registerAttendanceStartTime()
    {
        $this->attendance->registrerStartTime();
        return redirect()->route('attendance.index');
    }

    /**
      * Store a newly created resource in storage.
      *
      * @return \Illuminate\Http\Response
      */
    public function registerAttendanceEndTime($id)
    {
        $this->attendance->registrerEndTime($id);
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
        $this->attendance->absentAttendance($inputs);
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
        $this->attendance->updateModifyAttendance($inputs);
        return redirect()->route('attendance.index');
    }

     /**
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mypage()
    {
        $userId = Auth::id();
        $attendances = $this->attendance->fetchUserAttendances($userId)->get();
        $dateSum = $this->attendance->fetchAttendance($userId)->count();
        $attendanceHours = round($this->attendance->attendanceTotalMinutes($attendances)/60); 
        return view('user.attendance.mypage', compact('attendances', 'dateSum', 'attendanceHours'));
    }
}
