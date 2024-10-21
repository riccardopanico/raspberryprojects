<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogOperatori extends Model
{
    use HasFactory;

    protected $table = 'log_operatori';

    protected $fillable = [
        'nome',
        'cognome',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
