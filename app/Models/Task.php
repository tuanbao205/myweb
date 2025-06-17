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
        'user_id', 
    ];

    protected $casts = [
        'all_day' => 'boolean',
        'date_time' => 'datetime',
    ];

    // Task thuộc về một user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Task thuộc về một danh sách (nếu có quan hệ)
    public function taskList()
    {
        return $this->belongsTo(TaskList::class); // 👈 nếu có model TaskList
    }
}
