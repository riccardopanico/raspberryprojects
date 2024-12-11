<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        if (is_null($value)) {
            throw new \InvalidArgumentException("Il valore non può essere null.");
        }

        DB::transaction(function () use ($value) {
            $this->boolean_value = null;
            $this->string_value = null;
            $this->numeric_value = null;

            if (is_bool($value)) {
                $this->boolean_value = $value ? 1 : 0;
            } elseif (is_string($value)) {
                $this->string_value = $value;
            } elseif (is_int($value) || is_float($value)) {
                $this->numeric_value = $value;
            } else {
                throw new \InvalidArgumentException("Tipo di valore non supportato. Deve essere bool, stringa, intero o float.");
            }

            $this->save();

            LogData::create([
                'created_at'     => Carbon::now(),
                'user_id'        => Auth::id(),
                'device_id'      => $this->device_id,
                'variable_id'    => $this->id,
                'numeric_value'  => is_numeric($value) ? $value : null,
                'boolean_value'  => is_bool($value) ? ($value ? 1 : 0) : null,
                'string_value'   => is_string($value) ? $value : null
            ]);
        });
    }
}
