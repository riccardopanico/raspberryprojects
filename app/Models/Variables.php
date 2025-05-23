<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Variables extends Model
{
    protected $table = 'variables';
    public $timestamps = false;

    protected $fillable = [
        'device_id',
        'variable_name',
        'variable_code',
        'boolean_value',
        'string_value',
        'numeric_value',
        'created_at'
    ];

    public function getValue()
    {
        if (!is_null($this->boolean_value)) {
            return (bool) $this->boolean_value;
        } elseif (!is_null($this->string_value)) {
            return $this->string_value;
        } elseif (!is_null($this->numeric_value)) {
            return $this->numeric_value;
        }
        return null;
    }

    public function setValue($value)
    {
        $logData = [
            'user_id'    => Auth::id(),
            'device_id'  => $this->device_id,
            'variable_id'=> $this->id,
        ];

        $this->boolean_value = null;
        $this->string_value = null;
        $this->numeric_value = null;

        if (is_null($value)) {
            $this->save();
            LogData::create($logData);
            return;
        }

        if (is_bool($value)) {
            $logData['boolean_value'] = $this->boolean_value = $value ? 1 : 0;
        } elseif (is_string($value)) {
            $logData['string_value'] = $this->string_value = $value;
        } elseif (is_int($value) || is_float($value)) {
            $logData['numeric_value'] = $this->numeric_value = $value;
        } else {
            throw new \InvalidArgumentException("Tipo di valore non supportato. Deve essere bool, stringa, intero o float.");
        }

        $this->save();
        LogData::create($logData);
    }
}
