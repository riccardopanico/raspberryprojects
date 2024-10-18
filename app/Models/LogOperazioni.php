<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogOperazioni extends Model
{
    use HasFactory;

    protected $table = 'log_operazioni';

    protected $fillable = [
        'id',
        'data',
        'id_macchina',
        'id_operatore',
        'codice',
        'valore',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
