<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatesThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_moderator_can_create_a_thread()
    {
        $moderatorUser = factory(User::class)->create(['role' => 'moderator']);

        $thread = factory(Thread::class)->make(['user_id' => null])->toArray();

        $this->actingAs($moderatorUser);

        $this->post('/threads', $thread)
            ->assertStatus(200);

        $this->assertDatabaseHas('threads', array_merge($thread, ['user_id' => $moderatorUser->id]));
    }

    /** @test */
    public function a_normal_user_cannot_create_a_thread()
    {
        $normalUser = factory(User::class)->create(['role' => 'user']);

        $thread = factory(Thread::class)->make(['user_id' => null])->toArray();

        $this->actingAs($normalUser);

        $this->post('/threads', $thread)
            ->assertStatus(403);

        $this->assertDatabaseMissing('threads', array_merge($thread, ['user_id' => $normalUser->id]));
    }
}
