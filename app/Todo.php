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
        'user_id', 'title', 'completed'
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
