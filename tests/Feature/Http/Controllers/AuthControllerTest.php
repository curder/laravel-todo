<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use function Pest\Laravel\artisan;
use function Pest\Laravel\postJson;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    artisan('passport:client', ['--name' => config('app.name'), '--personal' => null]);
});

it('can not login when username is invalid', function () {
    User::factory()->create(['password' => Hash::make('Password')]);
    postJson("api/login", [
        'username' => 'fake-username',
        'password' => 'Password',
    ])->assertUnprocessable()
        ->assertJsonPath('status', 422)
        ->assertJsonPath('message', 'Your credentials are incorrect. Please try again')
        ->assertJsonPath('data', []);
});

it('can not login when password is invalid', function () {
    $user = User::factory()->create(['password' => Hash::make('Password')]);

    postJson("api/login", [
        'username' => $user->email,
        'password' => 'fake-password',
    ])->assertUnprocessable()
        ->assertJsonPath('status', 422)
        ->assertJsonPath('message', 'Your credentials are incorrect. Please try again')
        ->assertJsonPath('data', []);
});

it('has login api', function () {
    $user = User::factory()->create(['password' => Hash::make('Password')]);

    postJson("api/login", [
        'username' => $user->email,
        'password' => 'Password',
    ])->assertOk()
        ->assertJsonPath('message', 'Login successful')
        ->assertJsonStructure(['data' => ['token_type', 'expires_in', 'access_token'], 'status', 'message']);
});

it('has custom validate rule for register api', function (string $field, mixed $value, ?string $rule = null) {
    postJson("api/register", [$field => $value])
        ->assertJsonValidationErrors($rule ? [$field => $rule] : $field);
})->with([
    ['username', null, 'The username field is required.'],
    ['username', 123, 'The username must be a string.'],
    ['username', str_repeat('a', 256), 'The username must not be greater than 255 characters.'],
    ['email', null, 'The email field is required.'],
    ['email', 123, 'The email must be a string.'],
    ['email', 'example.com', 'The email must be a valid email address.'],
    ['email', str_repeat('a', 256), 'The email must not be greater than 255 characters.'],
    ['password', null, 'The password field is required.'],
    ['password', 123, 'The password must be a string.'],
    ['password', 'abc', 'The password must be at least 6 characters.'],
]);

it('can not register when user exists', function () {
    User::factory()->create(['email' => 'example@example.com']);

    postJson("api/register", ['email' => 'example@example.com'])
        ->assertJsonValidationErrors(['email' => 'The email has already been taken.']);
});

it('has register api', function () {
    postJson("api/register", [
        'username' => 'fake-username',
        'email' => 'example@example.com',
        'password' => 'Password',
    ])
        ->assertCreated()
        ->assertJsonPath('message', 'Registration successful')
        ->assertJsonStructure([
            'data' => ['token_type', 'expires_in', 'access_token'],
            'status',
            'message'
        ]);
});
