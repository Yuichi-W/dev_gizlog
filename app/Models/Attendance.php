<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Attendance extends Model
{

    const INITIAL＿MINUTES = 0;
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

    public function fetchTodayUserAttendance()
    {
        $userId = Auth::id();
        $today = Carbon::now()->format('Y-m-d');
        return $this->where('user_id', $userId)->where('date', $today)->first();
    }

    public function registrerStartTime()
    {
        $nowTime = Carbon::now();
        $today = $nowTime->format('Y-m-d');
        return $this->create([
            'user_id' => Auth::id(),
            'date' => $today,
            'start_time' => $nowTime
        ]);
    }

    public function registrerEndTime($id)
    {
        $nowTime = Carbon::now();
        return $this->find($id)->update([
            'end_time' => $nowTime
        ]);
    }

    public function absentAttendance($inputs)
    {
        $inputs['user_id'] = Auth::id();
        $inputs['date'] =  Carbon::now()->format('Y-m-d');
        $inputs['absent_status'] = self::IS_ABSENT;
        if (!empty($inputs['id'])) {
            $absentAttendance = $this->updateAbsentAttendance($inputs);
        } else {
            $absentAttendance = $this->attendance->fill($inputs)->save();
        }
        return $absentAttendance;
    }

    public function updateAbsentAttendance($inputs)
    {
        return $this->where([
            'date' => $inputs['date'], 
            'user_id' => $inputs['user_id']
        ])->update([
            'absent_status' => $inputs['absent_status'],
            'absent_reason' => $inputs['absent_reason']
        ]);
    }

    public function updateModifyAttendance($inputs)
    {
        $inputs['user_id'] = Auth::id();
        $inputs['revision_status'] = self::IS_REVISION;
        return $this->where([
                'date' => $inputs['date'], 
                'user_id' => $inputs['user_id']
            ])->update([
                'revision_status' => $inputs['revision_status'],
                'revision_request' => $inputs['revision_request']
            ]);
    }

    public function fetchAttendance($id)
    {
        return $this->fetchUserAttendances($id)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time');
    }

    public function fetchUserAttendances($id)
    {
        return $this->where('user_id', $id)->orderBy('date', 'desc');
    }

    public function attendanceTotalMinutes($attendances)
    {
        $attendanceMinutesTotal = self::INITIAL＿MINUTES;
        foreach ($attendances as $attendance)
        {
            if (isset($attendance->start_time) && isset($attendance->end_time)) {
                $attendanceMinutes = $attendance->start_time
                    ->diffInminutes($attendance->end_time);
                $attendanceMinutesTotal += $attendanceMinutes;
            }
        }
        return $attendanceMinutesTotal;
    }
}
