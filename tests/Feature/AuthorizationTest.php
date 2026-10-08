<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class);

test('regular user cannot create service', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withToken($token)->postJson('/api/services', [
        'name' => 'Test Service',
        'description' => 'Test Description',
        'price' => 100,
        'duration' => 60,
    ]);
    $response->assertStatus(403);
});

test('admin can create service', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $token = $admin->createToken('test-token')->plainTextToken;

    $response = $this->withToken($token)->postJson('/api/services', [
        'name' => 'Test Service',
        'description' => 'Test Description',
        'price' => 100,
        'duration' => 60,
    ]);
    
    $response->assertStatus(201);
    $this->assertDatabaseHas('services', ['name' => 'Test Service']);
});
