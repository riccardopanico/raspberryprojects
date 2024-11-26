<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    public $timestamps = false;
    protected $fillable = [
        'device_id',
        'task_type',
        'created_at',
        'sent',
        'status'
    ];
}
