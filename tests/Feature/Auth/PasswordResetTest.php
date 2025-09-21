<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken; // 1. Tambahkan ini di atas
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

test('reset password link screen can be rendered', function () {
    $response = $this->get(route('password.request'));

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    $this->withoutMiddleware(VerifyCsrfToken::class); // <-- Ditambahkan di sini
    Notification::fake();

    $user = User::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $response = $this->get(route('password.reset', ['token' => $token, 'email' => $user->email]));

    $response->assertStatus(200);
});

test('password can be reset with valid token', function () {
    $this->withoutMiddleware(VerifyCsrfToken::class); // <-- Ditambahkan di sini

    $user = User::factory()->create();
    $token = Password::createToken($user);

    $response = $this->post(route('password.store'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('login'));
});

test('password cannot be reset with invalid token', function () {
    $this->withoutMiddleware(VerifyCsrfToken::class); // <-- Ditambahkan di sini

    $user = User::factory()->create();

    $response = $this->post(route('password.store'), [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasErrors('email');
});