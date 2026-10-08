<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class);

test('user can register successfully', function () {
    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];
    $response = $this->postJson('/api/register', $data);
    $response->assertStatus(201);
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);
    $response->assertJsonStructure([
        'token',
    ]);
});

test('user can login successfully', function () {
    $user = User::factory()->create([
        'email' => 'login@example.com',
        'password' => bcrypt('password123'),
    ]);
    $response = $this->postJson('/api/login', [
        'email' => 'login@example.com',
        'password' => 'password123',
    ]);
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'token',
    ]);
});

test('user cannot login with invalid credentials', function () {
    $user = User::factory()->create([
        'email' => 'wrong@example.com',
        'password' => bcrypt('password123'),
    ]);
    $response = $this->postJson('/api/login', [
        'email' => 'wrong@example.com',
        'password' => 'wrongpassword',
    ]);
    $response->assertStatus(401);
});

test('user can logout successfully', function () {
    $user = User::factory()->create([
        'email' => 'logout@example.com',
        'password' => bcrypt('password123'),
    ]);
    $loginResponse = $this->postJson('/api/login', [
        'email' => 'logout@example.com',
        'password' => 'password123',
    ]);
    $token = $loginResponse->json('token');
    $response = $this->withToken($token)->postJson('/api/logout');
    $response->assertStatus(200);
    $this->assertDatabaseCount('personal_access_tokens', 0);
});

