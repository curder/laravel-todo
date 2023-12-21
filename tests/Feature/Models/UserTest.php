<?php

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(LazilyRefreshDatabase::class);

it('has users table')->expect(fn() => Schema::hasTable('users'))->toBeTrue();

it('has some columns for database table struct', function () {
    $columns = ['id', 'name', 'email', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at'];

    expect(Schema::hasColumns('users', $columns))->toBeTrue()
        ->and(Schema::getColumnListing('users'))->toEqual($columns);
});

it('has many todos', function () {
    $user = User::factory()->create();

    expect($user)->todos
        ->not->toBeNull()
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty()
        ->todos()
        ->toBeInstanceOf(HasMany::class);

    Todo::factory()->count(10)->create(['user_id' => $user]);

    $user->refresh();
    expect($user)->todos->toHaveCount(10);
});
