<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'date_time',
        'description',
        'all_day',
        'task_list_id',
    ];

    protected $casts = [
        'all_day' => 'boolean',
        'date_time' => 'datetime',
    ];
}
