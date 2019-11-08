<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Attendance extends Model
{
    const INITIAL_MINUTES = 0;
    const IS_NOT_ABSENT = 0;
    const IS_ABSENT = 1;
    const IS_NOT_REVISION = 0;
    const IS_REVISION = 1;

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'absent_status',
        'absent_reason',
        'revision_status',
        'revision_request',
    ];

    protected $dates = [
        'date',
        'start_time',
        'end_time',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 選択した日付のユーザーの勤怠レコードの取得。
     * @param int $userId
     * @return Attendance
     */
    public function fetchSelectDayUserAttendance($day)
    {
        $userId = Auth::id();
        return $this->where('user_id', $userId)->where('date', $day);
    }

    /**
     * 出勤時間をDBへ登録。
     * @param int 
     * @return void
     */
    public function registerStartTime()
    {
        $this->create([
            'user_id' => Auth::id(),
            'date' => Carbon::now()->format('Y-m-d'),
            'start_time' => Carbon::now()
        ]);
    }

    /**
     * 退勤時間をDBへ登録。
     * @param int 
     * @return void
     */
    public function registerEndTime()
    {
        $today = Carbon::now()->format('Y-m-d');
        $todayAttendance = $this->fetchSelectDayUserAttendance($today);
        if (!empty($todayAttendance->first())) {
            $attendances = $todayAttendance->update([
                'end_time' => Carbon::now()
            ]);
        } else {
            $attendances = '再度メニューから勤怠をクリックし本日の出社時間の登録を行ってください';
        }
        return $attendances;
    }

    /**
     * 欠席をDBへ登録
     * @param array $data
     * @return void
     */
    public function absentAttendance($data)
    {
        $today = Carbon::now()->format('Y-m-d');
        $todayAttendance = $this->fetchSelectDayUserAttendance($today);
        $data['absent_status'] = self::IS_ABSENT;
        if (!empty($todayAttendance)) {
            $this->updateAbsentAttendance($todayAttendance, $data);
        } else {
            $data['user_id'] = Auth::id();
            $data['date'] =  Carbon::now()->format('Y-m-d');
            $this->create($data);
        };
    }

    /**
     * 欠席をDBへ登録
     * @param array $data
     * @return void
     */
    public function updateAbsentAttendance($todayAttendance, $data)
    {
        $todayAttendance->update([
            'absent_status' => $data['absent_status'],
            'absent_reason' => $data['absent_reason']
        ]);
    }

    /**
     * 修正申請をDBへ登録
     * @param array $data
     * @return void
     */
    public function updateModifyAttendance($data)
    {
        $selectDayAttendance = $this->fetchSelectDayUserAttendance($data['date']);
        $data['revision_status'] = self::IS_REVISION;
        if (!empty($selectDayAttendance->first())) {
        $selectDayAttendance->update([
            'revision_status' => $data['revision_status'],
            'revision_request' => $data['revision_request']
        ]);
        } else {
            $data['user_id'] = Auth::id();
            $this->create($data);
        }
    }

    /**
     * 出勤＆退勤が共に登録されていて欠席登録されていないレコードの取得
     * @param $userId
     * @return Builder
     */
    public function fetchAttendance($userId)
    {
        return $this->fetchUserAttendances($userId)
            ->where('absent_status', 0)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time');
    }

    /**
     * ユーザーのレコードを最新順に取得
     * @param $userId
     * @return Builder
     */
    public function fetchUserAttendances($userId)
    {
        return $this->where('user_id', $userId)->orderBy('date', 'desc');
    }

    /**
     * ユーザーの累計学習時間(分)を算出
     * @param Cllection $attendances
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
