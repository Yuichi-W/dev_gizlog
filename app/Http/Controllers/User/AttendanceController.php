<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Requests\User\AttendanceRequest;

class AttendanceController extends Controller
{
    protected $attendance;

    public function __counstruct(Attendance $attendance)
    {
        $this->middleware('auth');
        $this->attendance = $attendance;
    }

    public function index()
    {
        return view('user.attendance.index');
    }

    public function absence()
    {
        return view('user.attendance.absence');
    }

    public function modify()
    {
        return view('user.attendance.modify');
    }

    public function mypage()
    {
        return view('user.attendance.mypage');
    }
}
