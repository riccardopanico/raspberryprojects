<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impostazioni extends Model
{
    protected $table = 'impostazioni';
    public $timestamps = false;

    protected $fillable = [
        'codice',
        'descrizione',
        'valore',
        'created_at'
    ];
}
