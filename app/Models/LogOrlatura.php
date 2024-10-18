<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogOrlatura extends Model
{
    use HasFactory;

    protected $table = 'log_orlatura';

    protected $fillable = [
        'id',
        'data',
        'consumo',
        'id_macchina',
        'id_operatore',
        'commessa',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
