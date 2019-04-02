<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Thread;

class UpdatesThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_moderator_can_update_his_thread()
    {
        $moderatorUser = factory(User::class)->create(['role' => 'moderator']);

        $thread = factory(Thread::class)->create(['user_id' => $moderatorUser->id]);

        $update = [
            'title' => 'Updated Title'
        ];

        $this->actingAs($moderatorUser);

        $this->post("/threads/{$thread->id}", $update)
            ->assertStatus(200);

        $this->assertDatabaseHas('threads', array_merge($update, [
            'user_id' => $moderatorUser->id,
            'description' => $thread->description
        ]));
    }

    /** @test */
    public function a_moderator_cannot_update_another_moderators_thread()
    {
        $moderatorUser = factory(User::class)->create(['role' => 'moderator']);

        $thread = factory(Thread::class)->create();

        $update = [
            'title' => 'Updated Title'
        ];

        $this->actingAs($moderatorUser);

        $this->post("/threads/{$thread->id}", $update)
            ->assertStatus(403);

        $this->assertDatabaseMissing('threads', array_merge($update, [
            'user_id' => $moderatorUser->id,
            'description' => $thread->description
        ]));
    }

    /** @test */
    public function a_former_moderator_cannot_update_his_thread()
    {
        $formerModerator = factory(User::class)->create(['role' => 'user']);

        $thread = factory(Thread::class)->create(['user_id' => $formerModerator->id]);

        $update = [
            'title' => 'Updated Title'
        ];

        $this->actingAs($formerModerator);


        $this->post("/threads/{$thread->id}", $update)
            ->assertStatus(403);


        $this->assertDatabaseMissing('threads', array_merge($update, [
            'user_id' => $formerModerator->id,
            'description' => $thread->description
        ]));
    }
}
