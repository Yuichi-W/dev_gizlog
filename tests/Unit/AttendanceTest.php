<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;
    // トラディショナルなデータベースを使用していても、RefreshDatabaseトレイトにより、マイグレーションに最適なアプローチが取れます。テストクラスてこのトレイトを使えば、全てが処理される
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);

    }

    public function testIndex()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/attendance');
        $response->assertStatus(200);
    }

    public function testAbsence()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/attendance/absence');
        $response->assertStatus(200);
    }

    public function tsetModify()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/attendance/modify');
        $response->assertStatus(200);
    }
}
