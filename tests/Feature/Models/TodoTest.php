<?php

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(LazilyRefreshDatabase::class);

it('has todos table')->expect(fn() => Schema::hasTable('todos'))->toBeTrue();

it('has some columns for database table struct', function () {
    $columns = ['id', 'title', 'completed', 'created_at', 'updated_at', 'user_id'];

    expect(Schema::hasColumns('todos', $columns))->toBeTrue()
        ->and(Schema::getColumnListing('todos'))->toEqual($columns);
});

it('belongs to user', function () {
    $todo = Todo::factory()->for($user = User::factory()->create())->create();

    expect($todo)
        ->user_id->toEqual($user->id)
        ->user->id->toEqual($user->id)
        ->user->toBeInstanceOf(User::class)
        ->user()->toBeInstanceOf(BelongsTo::class);
});
