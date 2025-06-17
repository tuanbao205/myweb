<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
    'title', 'description', 'all_day', 'start_time', 'end_time', 'user_id',
];


    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'all_day' => 'boolean',
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}

}

