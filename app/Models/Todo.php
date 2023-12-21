<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Todo
 * @property string title
 * @property boolean completed
 * @package App
 */
class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'completed'
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
