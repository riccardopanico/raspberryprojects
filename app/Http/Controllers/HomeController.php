<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\LogOrlatura;
use App\Models\Campionatura;
use App\Models\Impostazioni;
use Illuminate\Http\Request;
use App\Models\LogOperazioni;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $impostazioni = Impostazioni::all()->pluck('valore', 'codice')->toArray();
        extract($impostazioni);

        return view('MF1.home', get_defined_vars());
    }

    public function impostazioni(Request $request)
    {
        $impostazioni = Impostazioni::all()->pluck('valore', 'codice')->toArray();
        extract($impostazioni);

        return view('MF1.impostazioni', get_defined_vars());
    }

    public function reports(Request $request)
    {
        $impostazioni = Impostazioni::all()->pluck('valore', 'codice')->toArray();
        extract($impostazioni);

        $dati_totali = LogOrlatura::where('id_macchina', $id_macchina)
            ->selectRaw('SUM(consumo) as consumo_totale, SUM(tempo) as tempo_totale')
            ->first();

        $dati_commessa = LogOrlatura::where('id_macchina', $id_macchina)
            ->where('commessa', $commessa)
            ->selectRaw('SUM(consumo) as consumo_commessa, SUM(tempo) as tempo_commessa')
            ->first();

        $consumo_totale   = round($dati_totali->consumo_totale ?? 0, 2);
        $tempo_totale     = round($dati_totali->tempo_totale ?? 0, 2);
        $consumo_commessa = round($dati_commessa->consumo_commessa ?? 0, 2);
        $tempo_commessa   = round($dati_commessa->tempo_commessa ?? 0, 2);

        return view('MF1.reports', get_defined_vars());
    }

    public function manuale(Request $request)
    {
        $impostazioni = Impostazioni::all()->pluck('valore', 'codice')->toArray();
        extract($impostazioni);

        return view('MF1.manuale', get_defined_vars());
    }

    public function settingsSave(Request $request)
    {
        $impostazioni = Impostazioni::all()->pluck('valore', 'codice')->toArray();
        extract($impostazioni);
        DB::beginTransaction();
        try {
            switch ($request->setting) {
                case '______':
                    Impostazioni::where('codice', '______')->update(['valore' => 1]);
                    break;
                case 'data_cambio_olio':
                    Impostazioni::where('codice', $request->setting)->update(['valore' => $request->value]);
                    Impostazioni::where('codice', 'alert_olio')->update(['valore' => 0]);
                    break;
                case 'data_cambio_spola':
                    Impostazioni::where('codice', $request->setting)->update(['valore' => $request->value]);
                    Impostazioni::where('codice', 'alert_spola')->update(['valore' => 0]);
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

    public function settingsSaveAll(Request $request)
    {
        $impostazioni = Impostazioni::all()->pluck('valore', 'codice')->toArray();
        extract($impostazioni);
        DB::beginTransaction();
        try {
            foreach ($request->settings as $key => $value) {
                if ($key == 'parametro_olio_attivo' || $key == 'parametro_spola_attivo') {
                    $value = $value ? 1 : 0;
                };
                Impostazioni::where('codice', $key)
                    ->update([
                        'valore' => $value
                    ]);
                LogOperazioni::create([
                    'id_macchina'  => $id_macchina,
                    'id_operatore' => Auth::id(),
                    'codice'       => $key,
                    'valore'       => $value
                ]);
            }
            DB::commit();

            return ['success' => true];
        } catch (\Exception $th) {
            DB::rollback();

            return ['success' => false, 'msg' => $th->getMessage()];
        }
    }

    public function campionatura(Request $request)
    {
        $impostazioni = Impostazioni::all()->pluck('valore', 'codice')->toArray();
        extract($impostazioni);

        return view('MF1.campionatura', get_defined_vars());
    }

    public function signalCampionatura(Request $request)
    {
        $action    = $request->input('action');
        $timestamp = $request->input('timestamp');
        $campione  = $request->input('campione');
        $response  = ['success' => false];

        DB::beginTransaction();
        try {
            if ($action === 'START') {
                // Creazione di un nuovo record
                $campionatura = Campionatura::create([
                    'campione' => $campione,
                    'start'    => Carbon::createFromTimestampMs($timestamp, 'Europe/Rome')
                ]);
                $response = [
                    'success' => true,
                    'id'      => $campionatura->id,
                    'start'   => $campionatura->start->format('H:i:s')
                ];
            } elseif ($action === 'STOP') {
                // Aggiornamento del record esistente
                $id           = $request->input('id');
                $campionatura = Campionatura::find($id);

                if ($campionatura) {
                    $campionatura->stop = Carbon::createFromTimestampMs($timestamp, 'Europe/Rome');
                    $campionatura->save();

                    $start        = Carbon::parse($campionatura->start);
                    $stop         = Carbon::parse($campionatura->stop);
                    $log_orlatura = LogOrlatura::whereBetween('data', [$start, $stop])->get();
                    $consumo      = round($log_orlatura->sum('consumo'), 2);
                    $tempo        = round($log_orlatura->sum('tempo'));

                    $response = [
                        'success' => true,
                        'id'      => $campionatura->id,
                        'consumo' => $consumo,
                        'tempo'   => $tempo,
                        'stop'    => $campionatura->stop->format('H:i:s')
                    ];
                }
            }

            DB::commit();
        } catch (\Exception $th) {
            DB::rollback();

            $response = ['success' => false, 'msg' => $th->getMessage()];
        }

        return response()->json($response);
    }

    public function callExternalApi(string $url, array $params = [], string $method = 'GET', array $headers = []): array
    {
        try {
            // Se non c'è un access token, prova a recuperarlo con il login
            if (!Session::has('access_token')) {
                dump('Access token non presente, tentativo di login...');
                $loginResponse = $this->performLogin();
                if (!$loginResponse['success']) {
                    dump('Login fallito:', $loginResponse);
                    return [
                        'success' => false,
                        'status' => 401,
                        'error' => 'Unable to login: ' . $loginResponse['error'],
                    ];
                }
                $headers['Authorization'] = 'Bearer ' . $loginResponse['data']['access_token'];
            } else {
                $headers['Authorization'] = 'Bearer ' . Session::get('access_token');
            }

            // Configura il metodo della richiesta HTTP
            $method = strtoupper($method);
            $request = Http::withHeaders($headers);

            // Aggiungi i parametri in base al metodo
            dump('Eseguo richiesta API:');
            switch ($method) {
                case 'POST': $response = $request->post($url, $params); break;
                case 'PUT': $response = $request->put($url, $params); break;
                case 'DELETE': $response = $request->delete($url, $params); break;
                case 'GET':
                default: $response = $request->get($url, $params); break;
            }

            // Controlla se la risposta è riuscita
            dump('Risposta API ricevuta:', $response->json());
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            } elseif ($response->status() === 401) {
                // Token scaduto, prova ad aggiornare il token
                dump('Token scaduto, tentativo di refresh...');
                $refreshResponse = $this->performRefreshToken();
                if ($refreshResponse['success']) {
                    // Aggiorna l'header con il nuovo token
                    $headers['Authorization'] = 'Bearer ' . $refreshResponse['data']['access_token'];
                    return $this->callExternalApi($url, $params, $method, $headers);
                } else {
                    dump('Refresh token fallito:', $refreshResponse);
                    return [
                        'success' => false,
                        'status' => 401,
                        'error' => 'Unable to refresh token: ' . $refreshResponse['error'],
                    ];
                }
            } else {
                dump('Errore durante la richiesta API:', $response->status(), $response->body());
                return [
                    'success' => false,
                    'status' => $response->status(),
                    'error' => $response->json()['msg'] ?? $response->body(),
                ];
            }
        } catch (\Exception $e) {
            dump('Eccezione durante la richiesta API:', $e->getMessage());
            return [
                'success' => false,
                'status' => 500,
                'error' => $e->getMessage(),
            ];
        }
    }

    private function performRefreshToken(): array
    {
        $url = 'http://192.168.0.114:5000/api/auth/token/refresh';
        $refreshToken = Session::get('refresh_token');
        if (!$refreshToken) {
            dump('Refresh token non disponibile');
            return [
                'success' => false,
                'status' => 401,
                'error' => 'Refresh token not available',
            ];
        }

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $refreshToken,
        ];

        dump('Tentativo di refresh del token...');
        $response = Http::withHeaders($headers)->post($url);
        if ($response->successful()) {
            $responseData = $response->json();
            Session::put('access_token', $responseData['access_token']);
            return [
                'success' => true,
                'data' => $responseData,
            ];
        } else {
            dump('Refresh del token fallito:', $response->body());
            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->body(),
            ];
        }
    }

    private function performLogin(): array
    {
        $url = 'http://192.168.0.114:5000/api/auth/login';
        $credentials = [
            'username' => 'PiDevice1',
            'password' => 'password123',
        ];

        dump('Tentativo di login...');
        $response = Http::post($url, $credentials);
        if ($response->successful()) {
            $responseData = $response->json();
            Session::put('access_token', $responseData['access_token']);
            Session::put('refresh_token', $responseData['refresh_token']);
            return [
                'success' => true,
                'data' => $responseData,
            ];
        } else {
            dump('Login fallito:', $response->body());
            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->body(),
            ];
        }
    }

    public function getDeviceProfile()
    {
        $url = 'http://192.168.0.114:5000/api/device/profile';

        dump('Tentativo di recupero del profilo del dispositivo...');
        $response = $this->callExternalApi($url, [], 'GET');

        if ($response['success']) {
            return response()->json(['message' => 'Device profile retrieved successfully', 'data' => $response['data']], 200);
        } else {
            dump('Errore nel recupero del profilo del dispositivo:', $response);
            return response()->json(['message' => 'Failed to retrieve device profile', 'error' => $response['error']], $response['status']);
        }
    }
}
