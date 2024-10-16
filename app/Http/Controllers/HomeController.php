<?php

namespace App\Http\Controllers;

use App\Models\Fustelle;
use App\Models\Impostazioni;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    # costruttore
    public function __construct() {
        error_reporting(0);
    }

    public function home(Request $request)
    {
        extract(Impostazioni::all()->pluck('valore', 'codice')->toArray());
        return view('template_1.home', get_defined_vars());
    }

    public function impostazioni(Request $request)
    {
        extract(Impostazioni::all()->pluck('valore', 'codice')->toArray());
        return view('template_1.impostazioni', get_defined_vars());
    }

}
