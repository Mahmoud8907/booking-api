<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;

uses(RefreshDatabase::class);

test('user cannot book an already booked appointment', function () {
    $user = User::factory()->create();
    $service = Service::create([
        'name' => 'Test Service',
        'description' => 'Test Description',
        'price' => 100,
        'duration' => 60,
    ]);
    $token = $user->createToken('test-token')->plainTextToken;
    Appointment::create([
        'user_id' => $user->id,
        'service_id' => $service->id,
        'appointment_date' => '2026-10-10 15:00:00',
        'status' => 'pending',
    ]);
    $response = $this->withToken($token)->postJson('/api/appointments', [
        'service_id' => $service->id,
        'appointment_date' => '2026-10-10 15:00:00',
    ]);
    $response->assertStatus(409);
});

test('user can book an appointment successfully', function () {
    $user = User::factory()->create();
    $service = Service::create([
        'name' => 'Test Service',
        'description' => 'Test Description',
        'price' => 100,
        'duration' => 60,
    ]);
    $token = $user->createToken('test-token')->plainTextToken;
    $response = $this->withToken($token)->postJson('/api/appointments', [
        'service_id' => $service->id,
        'appointment_date' => '2026-10-11 15:00:00',
    ]);
    $response->assertStatus(201);
    $this->assertDatabaseHas('appointments', [
        'user_id' => $user->id,
        'service_id' => $service->id,
        'status' => 'pending',
    ]);
});

test('user can cancel their own appointment', function () {
    $user = User::factory()->create();
    $service = Service::create([
        'name' => 'Test Service',
        'description' => 'Test Description',
        'price' => 100,
        'duration' => 60,
    ]);
    $appointment = Appointment::create([
        'user_id' => $user->id,
        'service_id' => $service->id,
        'appointment_date' => '2026-10-12 15:00:00',
        'status' => 'pending',
    ]);
    $token = $user->createToken('test-token')->plainTextToken;
    $response = $this->withToken($token)->patchJson("/api/appointments/{$appointment->id}/cancel");
    $response->assertStatus(200);
    $this->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'status' => 'cancelled',
    ]);
});


test('user cannot cancel another user appointment', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $service = Service::create([
        'name' => 'Test Service',
        'description' => 'Test Description',
        'price' => 100,
        'duration' => 60,
    ]);
    $appointment = Appointment::create([
        'user_id' => $user->id,
        'service_id' => $service->id,
        'appointment_date' => '2026-10-13 15:00:00',
        'status' => 'pending',
    ]);
    $token = $otherUser->createToken('test-token')->plainTextToken;
    $response = $this->withToken($token)->patchJson("/api/appointments/{$appointment->id}/cancel");
    $response->assertStatus(403);
});

test('user cannot cancel an already cancelled appointment', function () {
    $user = User::factory()->create();
    $service = Service::create([
        'name' => 'Test Service',
        'description' => 'Test Description',
        'price' => 100,
        'duration' => 60,
    ]);
    $appointment = Appointment::create([
        'user_id' => $user->id,
        'service_id' => $service->id,
        'appointment_date' => '2026-10-14 15:00:00',
        'status' => 'cancelled',
    ]);
    $token = $user->createToken('test-token')->plainTextToken;
    $response = $this->withToken($token)->patchJson("/api/appointments/{$appointment->id}/cancel");
    $response->assertStatus(409);
});
