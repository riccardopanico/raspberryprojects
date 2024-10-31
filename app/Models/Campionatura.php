<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campionatura extends Model
{
    use HasFactory;

    protected $table = 'campionatura';
    public $timestamps = false;

    protected $fillable = [
        'campione',
        'start',
        'stop'
    ];
}
