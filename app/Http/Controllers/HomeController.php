<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tasks;
use App\Models\Device;
use App\Models\LogData;
use App\Models\Campionatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        if (env('APP_NAME') === 'RP1'){
            $this->user_id->setValue(null);
            Auth::logout();
        }

        $richiesta_filato = 0;
        $richiesta_intervento = 0;

        extract($this->loadAllVariables());

        $tasks = Tasks::whereNotIn('status', ['CANCELED', 'COMPLETED'])
            ->select('task_type', DB::raw('count(*) as count'))
            ->groupBy('task_type')
            ->get()
            ->each(function ($task) use (&$richiesta_filato, &$richiesta_intervento) {
                ${$task->task_type} = $task->count ? 1 : 0;
            });

        if (!session()->has('tecnici')) {
            $tecnici = $this->getTecnici($request)->original['data'];
            session(['tecnici' => $tecnici]);
            session()->save();
        } else {
            $tecnici = session('tecnici');
        }

        return view(env('APP_NAME') . '.home', get_defined_vars());
    }

    public function impostazioni(Request $request)
    {
        extract($this->loadAllVariables());
        return view(env('APP_NAME') . '.impostazioni', get_defined_vars());
    }

    public function reports(Request $request)
    {
        extract($this->loadAllVariables());

        $device = Device::where('interconnection_id', $interconnection_id)->first();

        // Query per dati totali
        $dati_totali = LogData::where('device_id', $device->id)
            ->where('variable_id', $this->encoder_consumo->id)
            ->selectRaw('SUM(numeric_value) as consumo_totale')
            ->first();

        $tempo_totale = LogData::where('device_id', $device->id)
            ->where('variable_id', $this->encoder_operativita->id)
            ->selectRaw('SUM(numeric_value) as tempo_totale')
            ->first();

        // Trova la data dell'ultima commessa nel log fino ad ora
        $start_commessa = LogData::where('device_id', $device->id)
            ->where('variable_id', $this->commessa->id)
            ->where('string_value', $commessa)
            ->max('created_at');

        $stop_commessa = Carbon::now()->format('Y-m-d H:i:s');

        // Query per dati commessa
        $dati_commessa = LogData::where('device_id', $device->id)
            ->where('variable_id', $this->encoder_consumo->id)
            ->whereBetween('created_at', [$start_commessa, $stop_commessa])
            ->selectRaw('SUM(numeric_value) as consumo_commessa')
            ->first();

        $tempo_commessa = LogData::where('device_id', $device->id)
            ->where('variable_id', $this->encoder_operativita->id)
            ->whereBetween('created_at', [$start_commessa, $stop_commessa])
            ->selectRaw('SUM(numeric_value) as tempo_commessa')
            ->first();

        // Arrotonda i valori finali
        $consumo_totale = round($dati_totali->consumo_totale ?? 0, 2);
        $tempo_totale = round($tempo_totale->tempo_totale ?? 0, 2);
        $consumo_commessa = round($dati_commessa->consumo_commessa ?? 0, 2);
        $tempo_commessa = round($tempo_commessa->tempo_commessa ?? 0, 2);
        return view(env('APP_NAME') . '.reports', get_defined_vars());
    }

    public function manuale(Request $request)
    {
        extract($this->loadAllVariables());
        return view(env('APP_NAME') . '.manuale', get_defined_vars());
    }

    public function getTecnici(Request $request)
    {
        extract($this->loadAllVariables());
        try {
            $device = Device::where('interconnection_id', $interconnection_id)->first();
            $codice_tecnico = $request->input('codice_tecnico');

            $payload = [];
            if (!empty($codice_tecnico)) {
                $payload['codice_tecnico'] = $codice_tecnico;
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post("http://{$device->gateway}:{$device->port_address}/api/recipe/tecnici", $payload);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Errore nella richiesta al server Flask',
                    'details' => $response->body()
                ], 500);
            }

            $data = $response->json();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getSettings()
    {
        return $this->loadAllVariables();
    }

    public function settingsSave(Request $request)
    {
        DB::beginTransaction();
        try {
            $setting = $request->setting;
            $value = $request->value;
            extract($this->loadAllVariables());

            $device = Device::where('interconnection_id', $interconnection_id)->first();

            switch ($setting) {
                case 'richiesta_filato':
                case 'richiesta_intervento':
                    Tasks::create([
                        'device_id' => $device->id,
                        'task_type' => $setting,
                        'status' => 'UNASSIGNED'
                    ]);
                    $tecnico = collect(session('tecnici'))->firstWhere('ID', $value);

                    if (is_null($tecnico)) {
                        return response()->json([
                            'success' => false,
                            'error' => 'Tecnico non trovato'
                        ], 404);
                    }

                    $payload = [
                        'telefono' => '3298006664',
                        // 'telefono' => $tecnico['TELEFONO'],
                        'messaggio' => 'Richiesta di intervento richiesta per dalla macchina "' . $device->id . '" da ' . $tecnico['NOME'] . ' ' . $tecnico['COGNOME']
                    ];

                    Http::withHeaders([
                        'Content-Type' => 'application/json'
                    ])->post("http://{$device->gateway}:{$device->port_address}/api/recipe/sms", $payload);

                    break;
                case 'commessa':
                    $barcode = $value;
                    $barcodeData = $this->parseBarcode($barcode);

                    if (isset($barcodeData['success']) && !$barcodeData['success']) {
                        return $barcodeData;
                    }

                    $this->{$setting}->setValue($barcode);

                    // Pulizia delle impostazioni precedenti
                    foreach (['T1codlavor', 'T1data_lavor', 'TCcodlavor', 'TCdata_lavor'] as $code) {
                        $this->{$code}->setValue(null);
                    }

                    // Recupero dati delle fasi di lavorazione
                    $responseFasi = collect($this->getFaseGa2($barcode));
                    if ($responseFasi->get('success') && isset($responseFasi['data'])) {
                        foreach ($responseFasi['data'] as $fase) {
                            $this->{$fase->CODFASE . 'codlavor'}->setValue($fase->CODLAVOR);
                            $this->{$fase->CODFASE . 'data_lavor'}->setValue($fase->DATA);
                        }
                    }

                    // Recupero informazioni lotto e articolo
                    $responseInfo = collect($this->getInfoGa2($barcode));
                    if ($responseInfo->get('success')) {
                        $this->prefisso->setValue($responseInfo['data'][0]);
                        $this->lotto->setValue($responseInfo['data'][1]);
                        $this->articolo->setValue($responseInfo['data'][2]);
                        $this->note_lavorazione->setValue($responseInfo['data'][3]);
                    }

                    // Recupero delle situazioni per vari codici
                    foreach ([20 => 'SUsituazione', 1 => 'PE1situazione', 2 => 'PE2situazione', 3 => 'PE3situazione'] as $codice => $settingKey) {
                        $responseSituazione = collect($this->getSituazioneGa2($barcode, $codice));
                        if ($responseSituazione->get('success')) {
                            $this->{$settingKey}->setValue(json_encode($responseSituazione['data']));
                        }
                    }

                    break;
                default:
                    $this->{$setting}->setValue($value);
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
        DB::beginTransaction();
        try {
            $this->parametro_olio_attivo->setValue(filter_var($request->parametro_olio_attivo, FILTER_VALIDATE_BOOLEAN));
            $this->parametro_olio->setValue((int) $request->parametro_olio);
            $this->parametro_spola_attivo->setValue(filter_var($request->parametro_spola_attivo, FILTER_VALIDATE_BOOLEAN));
            $this->parametro_spola->setValue((int) $request->parametro_spola);
            $this->fattore_taratura->setValue((int) $request->fattore_taratura);

            DB::commit();

            return ['success' => true];
        } catch (\Exception $th) {
            DB::rollback();

            return ['success' => false, 'msg' => $th->getMessage()];
        }
    }

    public function campionatura(Request $request)
    {
        extract($this->loadAllVariables());
        return view(env('APP_NAME') . '.campionatura', get_defined_vars());
    }

    public function signalCampionatura(Request $request)
    {
        $action = $request->input('action');
        $timestamp = $request->input('timestamp');
        $campione = $request->input('campione');
        $response = ['success' => false];

        DB::beginTransaction();
        try {
            if ($action === 'START') {
                $campionatura = Campionatura::create([
                    'campione' => $campione,
                    'start' => Carbon::createFromTimestampMs($timestamp, 'Europe/Rome')
                ]);
                $response = [
                    'success' => true,
                    'id' => $campionatura->id,
                    'start' => $campionatura->start->format('H:i:s')
                ];
            } elseif ($action === 'STOP') {
                $id = $request->input('id');
                $campionatura = Campionatura::find($id);

                if ($campionatura) {
                    $campionatura->stop = Carbon::createFromTimestampMs($timestamp, 'Europe/Rome');
                    $campionatura->save();

                    $start = Carbon::parse($campionatura->start);
                    $stop = Carbon::parse($campionatura->stop);

                    $log_data = LogData::whereBetween('created_at', [$start, $stop])->get();
                    $consumo = round($log_data->sum('numeric_value'), 2);
                    $tempo = $log_data->count(); // Example: each log entry represents one unit of time

                    $response = [
                        'success' => true,
                        'id' => $campionatura->id,
                        'consumo' => $consumo,
                        'tempo' => $tempo,
                        'stop' => $campionatura->stop->format('H:i:s')
                    ];
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            $response = ['success' => false, 'msg' => $e->getMessage()];
        }

        return response()->json($response);
    }

    private function parseBarcode($barcode)
    {
        $barcode_length = strlen($barcode);

        if (!in_array($barcode_length, [11, 12, 14])) {
            return ['success' => false, 'msg' => 'Lunghezza barcode errata!'];
        }

        if ($barcode_length == 11) {
            return [
                'codpref' => intval(substr($barcode, 0, 2)),
                'nlotto' => intval(substr($barcode, 2, 4)),
                'progr' => intval(substr($barcode, 6, 3)),
                'nfasebol' => intval(substr($barcode, 9, 1))
            ];
        } elseif ($barcode_length == 12) {
            return [
                'codpref' => intval(substr($barcode, 1, 2)),
                'nlotto' => intval(substr($barcode, 3, 4)),
                'progr' => intval(substr($barcode, 7, 3)),
                'nfasebol' => intval(substr($barcode, 10, 1))
            ];
        } else { // $barcode_length == 14
            return [
                'codpref' => intval(substr($barcode, 1, 2)),
                'nlotto' => intval(substr($barcode, 3, 4)),
                'progr' => intval(substr($barcode, 7, 3)),
                'nfasebol' => intval(substr($barcode, 10, 3))
            ];
        }
    }

    private function processBarcodeRequest($barcode, $endpoint, $extraParams = [])
    {
        try {
            extract($this->loadAllVariables());

            $device = Device::where('interconnection_id', $interconnection_id)->first();

            $parsedData = $this->parseBarcode($barcode);

            if (isset($parsedData['success']) && $parsedData['success'] === false) {
                return $parsedData; // Restituisce errore di lunghezza barcode
            }

            $payload = array_merge([
                'codpref' => $parsedData['codpref'],
                'nlotto' => $parsedData['nlotto']
            ], $extraParams);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post("http://{$device->gateway}:{$device->port_address}/api/$endpoint", $payload);

            return json_decode($response->body());
        } catch (\Exception $th) {
            return ['success' => false, 'msg' => $th->getMessage()];
        }
    }

    public function getFaseGa2($barcode)
    {
        return $this->processBarcodeRequest($barcode, 'recipe/fase');
    }

    public function getSituazioneGa2($barcode, $codice)
    {
        return $this->processBarcodeRequest($barcode, 'recipe/situazione', ['codice' => $codice]);
    }

    public function getInfoGa2($barcode)
    {
        return $this->processBarcodeRequest($barcode, 'recipe/info');
    }
}
