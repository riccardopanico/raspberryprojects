<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogData extends Model
{
    protected $table = 'log_data';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'device_id',
        'variable_id',
        'boolean_value',
        'string_value',
        'numeric_value',
        'created_at'
    ];

    // Metodo per ottenere l'ultimo valore registrato di una variabile
    public static function getLastValue($variable_id)
    {
        $record = self::where('variable_id', $variable_id)->orderBy('created_at', 'desc')->first();
        if ($record) {
            return $record->numeric_value ?? $record->boolean_value ?? $record->string_value;
        }
        return null;
    }
}
