<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attendances')->truncate();
        DB::table('attendances')->insert([

            [
                'user_id' => 1,
                'date' => Carbon::create('2019', '10', '01'),
                'start_time' => Carbon::create('2019', '10', '01', '09', '45', '00'),
                'end_time' => Carbon::create('2019', '10', '01', '19', '05', '00'),
                'absent_status' => 0,
                'absent_reason' => null,
                'revision_status' => 0,
                'revision_request' => null,
            ],
            [
                'user_id' => 2,
                'date' => Carbon::create('2019', '10', '02'),
                'start_time' => Carbon::create('2019', '10', '02', '10', '15', '20'),
                'end_time' => Carbon::create('2019', '10', '02', '19', '25', '10'),
                'absent_status' => 0,
                'absent_reason' => null,
                'revision_status' => 1,
                'revision_request' => '電車が遅延していたため遅刻しました。',
            ],
            [
                'user_id' => 3,
                'date' => Carbon::create('2019', '09', '22'),
                'start_time' => Carbon::create('2019', '09', '22', '09', '15', '20'),
                'end_time' => null,
                'absent_status' => 0,
                'absent_reason' => null,
                'revision_status' => 0,
                'revision_request' => null,
            ],
            [
                'user_id' => 1,
                'date' => Carbon::create('2019', '10', '05'),
                'start_time' => Carbon::create('2019', '10', '05', '09', '35', '50'),
                'end_time' => null,
                'absent_status' => 0,
                'absent_reason' => null,
                'revision_status' => 1,
                'revision_request' => '用事のため途中で帰りました',
            ],
            [
                'user_id' => 4,
                'date' => Carbon::create('2019', '10', '02'),
                'start_time' => null,
                'end_time' => null,
                'absent_status' => 1,
                'absent_reason' => '体調不良のため',
                'revision_status' => 0,
                'revision_request' => null,
            ],
            [
                'user_id' => 2,
                'date' => Carbon::create('2019', '10', '02'),
                'start_time' => null,
                'end_time' => null,
                'absent_status' => 1,
                'absent_reason' => '体調不良のため',
                'revision_status' => 1,
                'revision_request' => '病院で処方された薬を飲んだら嘘のように元気になったので午後から出社しています。',
            ],
            [
                'user_id' => 4,
                'date' => Carbon::create('2019', '10', '15'),
                'start_time' => Carbon::create('2019', '10', '15', '09', '45', '10'),
                'end_time' => Carbon::create('2019', '10', '15', '21', '15', '10'),
                'absent_status' => 0,
                'absent_reason' => null,
                'revision_status' => 1,
                'revision_request' => '課題締め切り日が近いため残業しました',
            ],
            [
                'user_id' => 4,
                'date' => Carbon::create('2019', '10', '25'),
                'start_time' => Carbon::create('2019', '10', '25', '09', '25', '10'),
                'end_time' => Carbon::create('2019', '10', '25', '19', '15', '10'),
                'absent_status' => 0,
                'absent_reason' => null,
                'revision_status' => 1,
                'revision_request' => '早く来すぎました',
            ],
            [
                'user_id' => 4,
                'date' => Carbon::create('2019', '10', '27'),
                'start_time' => Carbon::create('2019', '10', '27', '10', '00', '10'),
                'end_time' => Carbon::create('2019', '10', '27', '19', '35', '10'),
                'absent_status' => 0,
                'absent_reason' => null,
                'revision_status' => 1,
                'revision_request' => '10秒の遅刻',
            ],
        ]);
    }
}
