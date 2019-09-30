<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comments')->truncate();
        DB::table('comments')->insert([
            [
                'user_id'         => 1,
                'question_id'     => 1,
                'comment'         => 'clearfix使えば解消できますよ。クラスを指定してあげましょう',
                'created_at'      => Carbon::create(2019,5,6),
                'updated_at'      => Carbon::create(2019,6,7),
            ],
            [
                'user_id'         => 2,
                'question_id'     => 2,
                'comment'         => 'Laravelなどの有名なフレームワークもCSRF対策にはワンタイムトークンを採用しているため、これを抑えておくだけで問題はないと思います',
                'created_at'      => Carbon::create(2019,4,5),
                'updated_at'      => Carbon::create(2019,5,7),
             ],
             [
                'user_id'         => 1,
                'question_id'     => 3,
                'comment'         => 'EC2,VPC,RDS,S3あたりを覚えておけば問題ないかと思います',
                'created_at'      => Carbon::create(2019,5,10),
                'updated_at'      => Carbon::create(2019,7,22),
             ],
             [
                'user_id'         => 3,
                'question_id'     => 4,
                'comment'         => 'はなまるうどん',
                'created_at'      => Carbon::create(2019,8,8),
                'updated_at'      => Carbon::create(2019,9,30),
             ],
             [
                'user_id'         => 3,
                'question_id'     => 4,
                'comment'         => '上等カレー',
                'created_at'      => Carbon::create(2019,8,8),
                'updated_at'      => Carbon::create(2019,9,30),
             ],
             [
                'user_id'         => 3,
                'question_id'     => 4,
                'comment'         => '上等カレー',
                'created_at'      => Carbon::create(2019,8,8),
                'updated_at'      => Carbon::create(2019,9,30),
             ],
             [
                'user_id'         => 3,
                'question_id'     => 4,
                'comment'         => 'サイゼリア',
                'created_at'      => Carbon::create(2019,8,8),
                'updated_at'      => Carbon::create(2019,9,30),
             ],
        ]);
    }
}
