<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'category',
        'date_set',
        'date_due',
        'complete',
    ];

    /**
     * Define the relationship between users and tasks - a task belongs to a single user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Task>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
