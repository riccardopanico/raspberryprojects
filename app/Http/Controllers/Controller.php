<?php

namespace App\Http\Controllers;

use App\Models\Variables;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $variables = [];

    public function __get($name)
    {
        if (!isset($this->variables[$name])) {
            $variable = Variables::where('variable_code', $name)->first();
            if ($variable) {
                $this->variables[$name] = $variable;
            } else {
                throw new \InvalidArgumentException("La variabile con il codice '{$name}' non esiste.");
            }
        }

        return $this->variables[$name];
    }

    /**
     * Carica tutte le variabili dal database e le memorizza in $this->variables con i loro valori.
     *
     * @return void
     */
    public function loadAllVariables()
    {
        $variables = [];

        foreach (Variables::all() as $variable) {
            $this->variables[$variable->variable_code] = $variable;
            $variables[$variable->variable_code] = $variable->getValue();
        }
        return $variables;
    }
}
