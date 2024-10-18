<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impostazioni extends Model
{
    protected $table = 'impostazioni';

    protected $fillable = [
        'codice',
        'descrizione',
        'valore',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
