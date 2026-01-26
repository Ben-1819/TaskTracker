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
}
