<?php

namespace App\Http\Controllers;

use App\Models\Impostazioni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
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
        extract(Impostazioni::all()->pluck('valore', 'codice')->toArray());
        DB::beginTransaction();
        try {
            switch ($request->setting) {
                case '______':
                    Impostazioni::where('codice', '______')->update(['valore' => 1]);
                    break;
                default:
                    Impostazioni::where('codice', $request->setting)->update(['valore' => $request->value]);
                    break;
            }

            LogOperazioni::create([
                'id_macchina' => $id_macchina,
                'id_operatore' => $id_operatore,
                'codice' => $request->setting,
                'valore' => $request->value
            ]);

            DB::commit();

            return ['success' => true];
        } catch (\Exception $th) {
            DB::rollback();

            return ['success' => false, 'msg' => $th->getMessage()];
        }
    }
}