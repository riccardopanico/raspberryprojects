<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dipendente extends Model
{
    protected $table = 'dipendenti';
    public $timestamps = false;

    protected $fillable = [
        'badge', 'nome', 'cognome'
    ];

    public function toArray()
    {
        return [
            'id' => $this->id,
            'badge' => $this->badge,
            'nome' => $this->nome,
            'cognome' => $this->cognome
        ];
    }

    public function logs()
    {
        return $this->hasMany(LogData::class, 'user_id', 'id');
    }
}
