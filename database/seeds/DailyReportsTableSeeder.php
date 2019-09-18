<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DailyReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('daily_reports')->truncate();
        DB::table('daily_reports')->insert([
            [
                'user_id'        => 3,
                'title'          => '日報機能テスト',
                'content'        => 'テストテストテスト',
                'reporting_time' => Carbon::create(2019,1,1),
                'created_at'     => Carbon::create(2019,1,1),
                'updated_at'     => Carbon::create(2019,5,5),
            ],
            [
                'user_id'        => 5,
                'title'          => 'コーディング',
                'content'        => 'コーディングたくさんしました',
                'reporting_time' => Carbon::create(2019,2,2),
                'created_at'     => Carbon::create(2019,4,4),
                'updated_at'     => Carbon::create(2019,6,6),
            ],
        ]);
    }
}
