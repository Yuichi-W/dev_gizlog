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
    const INITIAL_MINUTES = 0;

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
      *
      * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
      */
    public function registerAttendanceEndTime()
    {
        $today = Carbon::now()->format('Y-m-d');
        $todayAttendance = $this->fetchSelectDayUserAttendance($today);
        if (!empty($todayAttendance->first())) {
            $attendances = $this->attendance->registerEndTime($todayAttendance);
        } else {
            $attendances = '再度メニューから勤怠をクリックし本日の出社時間の登録を行ってください';
        }
        return view('user.attendance.index', compact('attendances'));
    }

    /**
     * 欠席登録ページへ遷移
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function absencePage()
    {
        return view('user.attendance.absence');
    }

    /**
     * 欠席の登録
     * 
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
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function modificationPage()
    {
        return view('user.attendance.modify');
    }

    /**
     * 修正申請の登録
     * 
     * @param AttendanceRequest $request
     * @return @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function modification(AttendanceRequest $request)
    { 
        $inputs = $request->validated();
        $this->attendance->modifyAttendance($inputs);
        return redirect()->route('attendance.index');
    }

     /**
     * mypageへの遷移＆ユーザーの勤怠レコード＆合計出社日数＆累計学習時間の取得
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mypage()
    {
        $userId = Auth::id();
        $attendances = $this->attendance->daysAttended($userId)
            ->orderBy('date', 'desc')
            ->get();
        $attendanceMypage = $this->attendance->userAttendances($userId)
            ->orderBy('date', 'desc')
            ->paginate(self::PER_PAGE);
        $attendanceTotalDate = $this->attendance->daysAttended($userId)->count();
        $attendanceHours = round($this->attendanceTotalMinutes($attendances)/60); 
        return view('user.attendance.mypage', compact('attendanceMypage', 'attendanceTotalDate', 'attendanceHours'));
    }

    /**
     * ユーザーの累計学習時間(分)を算出
     * @param Collection $attendances
     * @return int $attendanceTotalMinutes
     */
    public function attendanceTotalMinutes($attendances)
    {
        $attendanceTotalMinutes = self::INITIAL_MINUTES;
        foreach ($attendances as $attendance) {
            if (!empty($attendance->start_time) && !empty($attendance->end_time)) {
                $attendanceMinutes = $attendance->start_time
                    ->diffInMinutes($attendance->end_time);
                $attendanceTotalMinutes += $attendanceMinutes;
            }
        }
        return $attendanceTotalMinutes;
    }
}
