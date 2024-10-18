<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogOperazioni extends Model
{
	protected $table = 'log_operazioni';

	protected $fillable = [
        'id_macchina',
        'id_operatore',
        'codice',
        'valore',
	];
}
