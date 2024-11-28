<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogOperazioni extends Model
{
    use HasFactory;

    protected $table = 'log_operazioni';
    public $timestamps = false;

    protected $fillable = [
        'data',
        'device_id',
        'id_operatore',
        'codice',
        'valore',
        'created_at'
    ];
}
