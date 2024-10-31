<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Impostazioni;
use Illuminate\Http\Request;
use App\Models\LogOperazioni;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $id_macchina = Impostazioni::where('codice', 'id_macchina')->first()->valore;
        return view('MF1.login', ['id_macchina' => $id_macchina, 'error' => session('error')]);
    }

    public function signin(Request $request)
    {
        try {
            // DB::enableQueryLog();
            $user = User::find($request->id_operatore);
            Auth::login($user);

            Impostazioni::where('codice', 'id_operatore')->update(['valore' => $request->id_operatore]);

            extract(Impostazioni::all()->pluck('valore', 'codice')->toArray());
            LogOperazioni::create([
                'id_macchina'  => $id_macchina,
                'id_operatore' => $id_operatore,
                'codice'       => 'id_operatore',
                'valore'       => $id_operatore
            ]);

            return redirect()->intended('home');
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            return redirect()->route('login')->with(['error' => 'BADGE NON VALIDO!']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('login');
    }

    public static function sendCurlRequest(Request $nuova_request)
    {
        // URL della richiesta
        $url = 'http://192.168.0.114:5000/api/device/login';

        // Token JWT di esempio
        $token = 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTcyODQ2OTU2NSwianRpIjoiMGRiNWEzMWYtYWUzOC00ZTRmLThkZDMtZjU2OTI0MzNjNzc5IiwidHlwZSI6InJlZnJlc2giLCJzdWIiOjEsIm5iZiI6MTcyODQ2OTU2NSwiY3NyZiI6ImEzNTcxYThiLTkwOTgtNGVkNy1iZTM4LTJmMmY4OWNmYjA3YyIsImV4cCI6MTcyOTA3NDM2NX0.YUDu721q1kOKjm4IiFHH3ff3fT5TJIcus46rWcvMO5w';

        // Dati da inviare nel corpo della richiesta
        // $data = json_encode([
        //     "dato_valore_1" => "UnoUnoUnoUnoUnoUnoUnoUnoUno",
        //     "dato_valore_2" => "Due",
        //     "dato_valore_3" => "Tre"
        // ]);
        $data = json_encode($nuova_request);

        // Impostazione di cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: ' . $token
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // Esegui la richiesta cURL
        $response = curl_exec($ch);

        // Controllo di eventuali errori
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);

            return response()->json(['error' => 'Errore cURL: ' . $error], 500);
        }

        // Chiudi la risorsa cURL
        curl_close($ch);

        // Decodifica e ritorna la risposta JSON
        $responseDecoded = json_decode($response, true);

        return response()->json($responseDecoded);
    }
}
