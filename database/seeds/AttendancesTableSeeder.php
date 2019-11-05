<?php

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendancesTableSeeder extends Seeder
{
    /**
     * 関数概要　 : 作成レコードは下記の４種
     * 「出社」 　: start_time,end_time共に同一日時で値（時間）があるstart_timeの方が早い
     * 「研修中」 : start_timeに値があり、end_timeがnullである
     * 「欠席」 　: start_time,end_time共にnullでabsent_statusが1,absent_resonに文字列が入る
     * 「申請」 　: start_time,end_time共に同一日時で値（時間）があり、revision_statusが1,revision_requestに文字列が入る
     *
     * @return void
     */
    public function run()
    {
        DB::table('attendances')->truncate();
        factory(Attendance::class, 100)->create();
        factory(Attendance::class, 100)->states('traning')->create();
        factory(Attendance::class, 100)->states('absent')->create();
        factory(Attendance::class, 100)->states('modify')->create();
    }
}
