<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Attendance extends Model
{
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
    public function registerEndTime($todayAttendance)
    {
        $todayAttendance->update([
            'end_time' => Carbon::now()
        ]);
    }

    /**
     * 欠席をDBへ登録
     * @param array $data
     * @return void
     */
    public function absentAttendance($data)
    {
        $date['date'] = Carbon::now()->format('Y-m-d');
        $todayAttendance = $this->fetchSelectDayUserAttendance($date['date']);
        $data['absent_status'] = self::IS_ABSENT;
        $this->recordCheck($todayAttendance, $data);
    }

    /**
     * 欠席をDBへ更新
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
     * 申請をDBへ登録
     * @param array $data
     * @return void
     */
    public function modifyAttendance($data)
    {
        $selectDayAttendance = $this->fetchSelectDayUserAttendance($data['date']);
        $data['revision_status'] = self::IS_REVISION;
        $this->recordCheck($selectDayAttendance, $data);
    }

    /**
     * 修正申請をDBへ更新
     * @param array $data
     * @return void
     */
    public function updateModifyAttendance($selectDayAttendance, $data)
    {
        $selectDayAttendance->update([
            'revision_status' => $data['revision_status'],
            'revision_request' => $data['revision_request']
        ]);
    }

    /**
     * レコードがあるかないかの確認
     * @param $record $data
     * @return void
     */
    public function recordCheck($record, $data)
    {
        if (!empty($record->first())) {
            if (!empty($data['absent_status'])) {
                $this->updateAbsentAttendance($record, $data);
            } else {
                $this->updateModifyAttendance($record, $data);
            }
        } else {
            $this->attendanceCreate($data);
        }
    }

    /**
     * 新規作成
     * @param $data
     * @return void
     */
    public function attendanceCreate($data)
    {
        $data['user_id'] = Auth::id();
        $this->create($data);
    }

    /**
     * 出勤＆退勤が共に登録されていて欠席登録されていないレコードの取得
     * @param $userId
     * @return Builder
     */
    public function scopeDaysAttended($query, $userId)
    {
        return $query->userAttendances($userId)
            ->where('absent_status', 0)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time');
    }

    /**
     * ユーザーのレコードを最新順に取得
     * @param $userId
     * @return Builder
     */
    public function scopeUserAttendances($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
