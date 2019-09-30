<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->truncate();
        DB::table('questions')->insert([
            [
                'user_id'         => 1,
                'tag_category_id' => 1,
                'title'           => 'clearfixって何？',
                'content'         => 'floatした時に次のboxがかぶるのですがこれはバグですか？clearfixって出てきたんですけどなんですか？',
                'created_at'      => Carbon::create(2019,5,5),
                'updated_at'      => Carbon::create(2019,6,6),
             ],
             [
                'user_id'         => 2,
                'tag_category_id' => 2,
                'title'           => 'セキュリティー対策のバリデーション以外に何したらいいですか？',
                'content'         => '調べたらCSRF対策と出てきたのですが、「本人が意図しない形で情報・リクエストを送信されてしまうこと」に対して何をすればいいのかがわからないです',
                'created_at'      => Carbon::create(2019,4,4),
                'updated_at'      => Carbon::create(2019,5,5),
             ],
             [
                'user_id'         => 1,
                'tag_category_id' => 3,
                'title'           => 'AWSのなんのサービスを勉強したらいいですか？',
                'content'         => 'AWSのサービスが多すぎてどれを勉強していいかわからないので業界でよく使用されるサービスを教えて欲しいです',
                'created_at'      => Carbon::create(2019,5,10),
                'updated_at'      => Carbon::create(2019,7,22),
             ],
             [
                'user_id'         => 3,
                'tag_category_id' => 4,
                'title'           => '渋谷周辺で美味しいご飯屋さんありますか？',
                'content'         => '渋谷駅周辺で美味しいご飯屋さんを知りたいです。和食と洋食、中華それぞれお勧めがあったら教えてください。',
                'created_at'      => Carbon::create(2019,8,8),
                'updated_at'      => Carbon::create(2019,9,30),
             ],
        ]);
    }
}
