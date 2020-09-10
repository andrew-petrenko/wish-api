<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $user_id
 * @property string $title
 * @property integer $goal_amount
 * @property integer $deposited_amount
 * @property string $description
 * @property string $due_date
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class Wish extends Model
{
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'goal_amount',
        'deposited_amount',
        'description',
        'due_date',
        'created_at',
        'updated_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
