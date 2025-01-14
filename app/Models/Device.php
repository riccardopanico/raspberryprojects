<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';
    public $timestamps = false;

    protected $fillable = [
        'interconnection_id',
        'user_id',
        'mac_address',
        'ip_address',
        'gateway',
        'subnet_mask',
        'dns_address',
        'port_address',
        'username',
        'password',
        'created_at'
    ];

    public function logData()
    {
        return $this->hasMany(LogData::class, 'device_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'device_id', 'id');
    }

    public function variables()
    {
        return $this->hasMany(Variables::class, 'device_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
