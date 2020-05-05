<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Todo
 * @property string title
 * @property boolean completed
 * @package App
 */
class Todo extends Model
{
    protected $fillable = [
        'title', 'completed'
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];
}
