<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tasks;
use App\Models\LogOrlatura;
use App\Models\Campionatura;
use App\Models\Variables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $Variables = Variables::all()->pluck('valore', 'codice')->toArray();
        extract($Variables);
        $richiesta_filato = 0;
        $richiesta_intervento = 0;
        $tasks = Tasks::whereNotIn('status', ['CANCELED', 'COMPLETED'])
            ->select('task_type', DB::raw('count(*) as count'))
            ->groupBy('task_type')
            ->get()
            ->each(function($task) use(&$richiesta_filato, &$richiesta_intervento) {
                ${$task->task_type} = $task->count ? 1 : 0;
            });
        return view('MF1.home', get_defined_vars());
    }

    public function impostazioni(Request $request)
    {
        $Variables = Variables::all()->pluck('valore', 'codice')->toArray();
        extract($Variables);

        return view('MF1.impostazioni', get_defined_vars());
    }

    public function reports(Request $request)
    {
        $Variables = Variables::all()->pluck('valore', 'codice')->toArray();
        extract($Variables);

        $dati_totali = LogOrlatura::where('device_id', $device_id)
            ->selectRaw('SUM(consumo) as consumo_totale, SUM(tempo) as tempo_totale')
            ->first();

        $dati_commessa = LogOrlatura::where('device_id', $device_id)
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
        $Variables = Variables::all()->pluck('valore', 'codice')->toArray();
        extract($Variables);

        return view('MF1.manuale', get_defined_vars());
    }

    public function settingsSave(Request $request)
    {
        $Variables = Variables::all()->pluck('valore', 'codice')->toArray();
        extract($Variables);
        DB::beginTransaction();
        try {
            switch ($request->setting) {
                case '______':
                    Variables::where('codice', '______')->update(['valore' => 1]);
                    break;
                case 'data_cambio_olio':
                    Variables::where('codice', $request->setting)->update(['valore' => $request->value]);
                    Variables::where('codice', 'alert_olio')->update(['valore' => 0]);
                    break;
                case 'data_cambio_spola':
                    Variables::where('codice', $request->setting)->update(['valore' => $request->value]);
                    Variables::where('codice', 'alert_spola')->update(['valore' => 0]);
                    break;
                case 'richiesta_filato':
                case 'richiesta_intervento':
                    Tasks::create([
                        'device_id' => $device_id,
                        'task_type' => $request->setting,
                        'status' => 'UNASSIGNED'
                    ]);
                    break;
                default:
                    Variables::where('codice', $request->setting)->update(['valore' => $request->value]);
                    break;
            }
            DB::commit();

            return ['success' => true];
        } catch (\Exception $th) {
            DB::rollback();

            return ['success' => false, 'msg' => $th->getMessage()];
        }
    }

    public function settingsSaveAll(Request $request)
    {
        $Variables = Variables::all()->pluck('valore', 'codice')->toArray();
        extract($Variables);
        DB::beginTransaction();
        try {
            foreach ($request->settings as $key => $value) {
                if ($key == 'parametro_olio_attivo' || $key == 'parametro_spola_attivo') {
                    $value = $value ? 1 : 0;
                };
                Variables::where('codice', $key)
                    ->update([
                        'valore' => $value
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
        $Variables = Variables::all()->pluck('valore', 'codice')->toArray();
        extract($Variables);

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
                $loginResponse = $this->performLogin();
                if (!$loginResponse['success']) {
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
            switch ($method) {
                case 'POST':
                    $response = $request->post($url, $params);
                    break;
                case 'PUT':
                    $response = $request->put($url, $params);
                    break;
                case 'DELETE':
                    $response = $request->delete($url, $params);
                    break;
                case 'GET':
                default:
                    $response = $request->get($url, $params);
                    break;
            }

            // Controlla se la risposta è riuscita
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            } elseif ($response->status() === 401) {
                // Token scaduto, prova ad aggiornare il token
                $refreshResponse = $this->performRefreshToken();
                if ($refreshResponse['success']) {
                    // Aggiorna l'header con il nuovo token
                    $headers['Authorization'] = 'Bearer ' . $refreshResponse['data']['access_token'];
                    return $this->callExternalApi($url, $params, $method, $headers);
                } else {
                    $loginResponse = $this->performLogin();
                    if ($loginResponse['success']) {
                        $headers['Authorization'] = 'Bearer ' . $loginResponse['data']['access_token'];
                        return $this->callExternalApi($url, $params, $method, $headers);
                    } else {
                        return [
                            'success' => false,
                            'status' => 401,
                            'error' => 'Unable to refresh or login: ' . $loginResponse['error'],
                        ];
                    }
                }
            } elseif ($response->status() === 422 && strpos($response->body(), 'Signature verification failed') !== false) {
                // Se la verifica della firma fallisce, significa che la chiave JWT potrebbe essere cambiata, quindi tenta il login
                $loginResponse = $this->performLogin();
                if ($loginResponse['success']) {
                    $headers['Authorization'] = 'Bearer ' . $loginResponse['data']['access_token'];
                    return $this->callExternalApi($url, $params, $method, $headers);
                } else {
                    return [
                        'success' => false,
                        'status' => 401,
                        'error' => 'Unable to login after JWT key change: ' . $loginResponse['error'],
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'status' => $response->status(),
                    'error' => $response->json()['msg'] ?? $response->body(),
                ];
            }
        } catch (\Exception $e) {
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

        $response = Http::withHeaders($headers)->post($url);
        if ($response->successful()) {
            $responseData = $response->json();
            Session::put('access_token', $responseData['access_token']);
            return [
                'success' => true,
                'data' => $responseData,
            ];
        } else {
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

        $response = $this->callExternalApi($url, [], 'GET');

        if ($response['success']) {
            return response()->json(['message' => 'Device profile retrieved successfully', 'data' => $response['data']], 200);
        } else {
            return response()->json(['message' => 'Failed to retrieve device profile', 'error' => $response['error']], $response['status']);
        }
    }
}
