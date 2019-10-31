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
        if ($this->find($id)->date->isToday()) {
            return $this->find($id)->update([
                'end_time' => $nowTime
            ]);
        }
    }

    public function absentAttendance($data)
    {
        $data['user_id'] = Auth::id();
        $data['date'] =  Carbon::now()->format('Y-m-d');
        $data['absent_status'] = self::IS_ABSENT;
        if (!empty($data['id'])) {
            $absentAttendance = $this->updateAbsentAttendance($data);
        } else {
            $absentAttendance = $this->attendance->fill($data)->save();
        }
        return $absentAttendance;
    }

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

    public function updateModifyAttendance($data)
    {
        $data['user_id'] = Auth::id();
        $data['revision_status'] = self::IS_REVISION;
        return $this->where([
                'date' => $data['date'], 
                'user_id' => $data['user_id']
            ])->update([
                'revision_status' => $data['revision_status'],
                'revision_request' => $data['revision_request']
            ]);
    }

    public function fetchAttendance($userId)
    {
        return $this->fetchUserAttendances($userId)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time');
    }

    public function fetchUserAttendances($userId)
    {
        return $this->where('user_id', $userId)->orderBy('date', 'desc');
    }

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
