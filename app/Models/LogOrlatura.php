<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogOrlatura extends Model
{
    use HasFactory;

    protected $table = 'log_orlatura';
    public $timestamps = false;

    protected $fillable = [
        'data',
        'consumo',
        'id_macchina',
        'id_operatore',
        'commessa',
        'created_at'
    ];
}
