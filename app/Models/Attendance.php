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
     * 関数概要 : ユーザーの最新の勤怠レコードの取得。
     * @param int $userId
     * @return Attendance
     */
    public function fetchTodayUserAttendance()
    {
        $userId = Auth::id();
        $today = Carbon::now()->format('Y-m-d');
        return $this->where('user_id', $userId)->where('date', $today)->first();
    }

    /**
     * 関数概要 : 出勤時間をDBへ登録。
     * @param int 
     * @return void
     */
    public function registrerStartTime()
    {
        $nowTime = Carbon::now();
        $today = $nowTime->format('Y-m-d');
        $this->create([
            'user_id' => Auth::id(),
            'date' => $today,
            'start_time' => $nowTime
        ]);
    }

    /**
     * 関数概要 : 退勤時間をDBへ登録。
     * @param int 
     * @return void
     */
    public function registrerEndTime($id)
    {
        $nowTime = Carbon::now();
        if ($this->find($id)->date->isToday()) {
            $this->find($id)->update([
                'end_time' => $nowTime
            ]);
        }
    }

    /**
     * 関数概要 : 欠席をDBへ登録
     * @param array $data
     * @return void
     */
    public function absentAttendance($data)
    {
        $data['user_id'] = Auth::id();
        $data['date'] =  Carbon::now()->format('Y-m-d');
        $data['absent_status'] = self::IS_ABSENT;
        if (!empty($data['id'])) {
            $this->updateAbsentAttendance($data);
        } else {
            $this->attendance->fill($data)->save();
        };
    }

    /**
     * 関数概要 : 欠席をDBへ登録
     * @param array $data
     * @return void
     */
    public function updateAbsentAttendance($data)
    {
        return $this->where([
            'date' => $data['date'], 
            'user_id' => $data['user_id']
        ])->update([
            'absent_status' => $data['absent_status'],
            'absent_reason' => $data['absent_reason']
        ]);
    }

    /**
     * 関数概要 : 修正申請をDBへ登録
     * @param array $data
     * @return void
     */
    public function updateModifyAttendance($data)
    {
        $data['user_id'] = Auth::id();
        $data['revision_status'] = self::IS_REVISION;
        $this->where([
            'date' => $data['date'], 
            'user_id' => $data['user_id']
            ])->update([
                'revision_status' => $data['revision_status'],
                'revision_request' => $data['revision_request']
            ]);
    }

    /**
     * 関数概要 : 出勤＆退勤が共に登録されているレコードの取得
     * @param $userId
     * @return Builder
     */
    public function fetchAttendance($userId)
    {
        return $this->fetchUserAttendances($userId)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time');
    }

    /**
     * 関数概要 : ユーザーのレコードを最新順に取得
     * @param $userId
     * @return Builder
     */
    public function fetchUserAttendances($userId)
    {
        return $this->where('user_id', $userId)->orderBy('date', 'desc');
    }

    /**
     * 関数概要 : ユーザーの累計学習時間(分)を算出
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
