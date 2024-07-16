<?php
use App\Models\User;
use Laravel\Passport\Passport;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});


test('View all Todos', function () {
    // Use Acting As passport and acting user to authenticate the user
    $user = Passport::actingAs(
        User::factory()->create()
    );

    $response = $this->actingAs($user)->get('/api/todo/index');

    $response->assertStatus(200);
});

test('Crete Todo', function () {
    // Use Acting As passport and acting user to authenticate the user
    $user = Passport::actingAs(
        User::factory()->create()
    );

    $response = $this->actingAs($user)->post('/api/todo/store', [
        'title' => 'Test Todo',
        'description' => 'Test Description',
        'status' => 1
    ]);

    $response->assertStatus(201);
});