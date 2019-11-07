<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Http\Requests\User\AttendanceRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    const PER_PAGE = 10;

    protected $attendance;

    public function __construct(Attendance $attendance)
    {
        $this->middleware('auth');
        $this->attendance = $attendance;
    }

    /**
      * indexページへ遷移
      *
      * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
      */
    public function index()
    {
        $today = Carbon::now()->format('Y-m-d');
        $attendances = $this->attendance->fetchSelectDayUserAttendance($today)->first();
        return view('user.attendance.index', compact('attendances'));
    }

    /**
      * 出社時間の登録
      *
      * @return \Illuminate\Http\Response
      */
    public function registerAttendanceStartTime()
    {
        $this->attendance->registerStartTime();
        return redirect()->route('attendance.index');
    }

    /**
      * 退社時間の登録
      * @param $id
      * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
      */
    public function registerAttendanceEndTime($id)
    {
        $this->attendance->registerEndTime($id);
        return redirect()->route('attendance.index');
    }

    /**
     * 欠席登録ページへ遷移
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function absencePage()
    {
        return view('user.attendance.absence');
    }

    /**
     * 欠席の登録
     * @param AttendanceRequest $request
     * @return @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function absence(AttendanceRequest $request)
    {
        $inputs = $request->validated();
        $this->attendance->absentAttendance($inputs);
        return redirect()->route('attendance.index');
    }

    /**
     * 修正申請ページへ遷移
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function modificationPage()
    {
        return view('user.attendance.modify');
    }

    /**
     * 修正申請の登録
     * @param AttendanceRequest $request
     * @return @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function modification(AttendanceRequest $request)
    { 
        $inputs = $request->validated();
        $this->attendance->updateModifyAttendance($inputs);
        return redirect()->route('attendance.index');
    }

     /**
     * mypageへの遷移＆ユーザーの勤怠レコード＆合計出社日数＆累計学習時間の取得
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mypage()
    {
        $userId = Auth::id();
        $attendances = $this->attendance->fetchAttendance($userId)->get();
        $attendanceMypage = $this->attendance->fetchUserAttendances($userId)
            ->paginate(self::PER_PAGE);
        $attendanceTotalDate = $this->attendance->fetchAttendance($userId)->count();
        $attendanceHours = round($this->attendance->attendanceTotalMinutes($attendances)/60); 
        return view('user.attendance.mypage', compact('attendanceMypage', 'attendanceTotalDate', 'attendanceHours'));
    }
}
