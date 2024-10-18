<?php

namespace App\Http\Controllers;

use App\Models\Impostazioni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class HomeController extends Controller
{
    # costruttore
    public function __construct()
    {
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

    public function settingsSave(Request $request)
    {
        DB::beginTransaction();
        try {
            switch ($request->setting) {
                case 'richiesta_intervento':
                    Impostazioni::where('codice', 'richiesta_intervento')->update(['valore' => 1]);
                    break;
                default:
                    Impostazioni::where('codice', $request->setting)->update(['valore' => $request->value]);
                    break;
            }

            DB::commit();

            return ['success' => true];
        } catch (\Exception $th) {
            DB::rollback();

            return ['success' => false, 'msg' => $th->getMessage()];
        }
    }
}
