<?php

namespace App\Http\Controllers;

use App\Models\Impostazioni;
use Illuminate\Http\Request;
use App\Models\LogOperazioni;
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

        // curl --location 'http://192.168.0.114:5000/api/device/test_settingsSave' \
        // --header 'Content-Type: application/json' \
        // --header 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTcyODQ2OTU2NSwianRpIjoiMGRiNWEzMWYtYWUzOC00ZTRmLThkZDMtZjU2OTI0MzNjNzc5IiwidHlwZSI6InJlZnJlc2giLCJzdWIiOjEsIm5iZiI6MTcyODQ2OTU2NSwiY3NyZiI6ImEzNTcxYThiLTkwOTgtNGVkNy1iZTM4LTJmMmY4OWNmYjA3YyIsImV4cCI6MTcyOTA3NDM2NX0.YUDu721q1kOKjm4IiFHH3ff3fT5TJIcus46rWcvMO5w' \
        // --data '{
        // "dato_valore_1": "UnoUnoUnoUnoUnoUnoUnoUnoUno",
        // "dato_valore_2": "Due",
        // "dato_valore_3": "Tre"
        // }'

        extract(Impostazioni::all()->pluck('valore', 'codice')->toArray());
        // $nuova_request               = new Request();
        // $nuova_request->id_macchina  = $id_macchina;
        // $nuova_request->id_operatore = $id_operatore;
        // $nuova_request->codice      = $request->setting;
        // $nuova_request->valore        = $request->value;

       

        // dd(HomeController::sendCurlRequest($nuova_request));

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
                'id_macchina'  => $id_macchina,
                'id_operatore' => $id_operatore,
                'codice'       => $request->setting,
                'valore'       => $request->value
            ]);

            DB::commit();

            return ['success' => true];
        } catch (\Exception $th) {
            DB::rollback();

            return ['success' => false, 'msg' => $th->getMessage()];
        }
    }

    
}