<?php

namespace App\Http\Controllers;

use App\Models\Impostazioni;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiController extends Controller
{

    public function getSetting($setting)
    {
        $Impostazioni = Impostazioni::select(['codice', 'descrizione', 'valore'])
        ->where('codice', $setting)->first();
        if(!is_null($Impostazioni)) {
            return $Impostazioni;
        } else {
            return [];
        }

    }

    public function setSetting(Request $request, $setting)
    {
        if(!$request->has('valore') || strlen($request->valore) == 0){
            return ['messaggio' => 'Parametro "valore" mancante!'];
        }

        switch ($setting) {
            case 'token': $valore = (string)$request->valore; break;
            case 'escludi_contacolpi': 
                if(in_array($request->valore, ['ON', 'OFF'])) {
                    $valore = (string)$request->valore; 
                } else {
                    return ['messaggio' => 'Valore non valido!'];
                }
                break;
            default: $valore = $request->valore; break;
        }

        $result = Impostazioni::where('codice', $setting)->update(['valore' => $valore]);
        if(!$result){
            return ['messaggio' => 'Nessuna variabile trovata!'];
        } else {
            return ['messaggio' => 'Variabile impostata!'];
        }
    }

    // public function getLogs(Request $request)
    // {
    //     if(
    //         (!$request->has('start') || !Carbon::hasFormat($request->start, 'Y-m-d\TH:i:s\Z')) ||
    //         (!$request->has('end') || !Carbon::hasFormat($request->end, 'Y-m-d\TH:i:s\Z'))
    //     ){
    //         return ['messaggio' => 'Controllare che entrambi i parametri "start" ed "end" siano presenti e che rispettino il formato "Y-m-dTH:i:sZ"!'];
    //     } else {
    //        return LogOperazioni::whereBetween('data', [Carbon::parse($request->start), Carbon::parse($request->end)])
    //         ->orderBy('data', 'desc')
    //         ->get();
    //     }
    // }

}
