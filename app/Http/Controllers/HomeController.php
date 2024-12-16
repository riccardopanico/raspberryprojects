<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tasks;
use App\Models\LogOrlatura;
use App\Models\Campionatura;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function home(Request $request)
    {
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

        return view('MF1.home', get_defined_vars());
    }

    public function impostazioni(Request $request)
    {
        $this->loadAllVariables();
        extract($this->variables);
        return view('MF1.impostazioni', get_defined_vars());
    }

    public function reports(Request $request)
    {
        $this->loadAllVariables();
        extract($this->variables);

        $dati_totali = LogOrlatura::where('device_id', $device_id)
            ->selectRaw('SUM(consumo) as consumo_totale, SUM(tempo) as tempo_totale')
            ->first();

        $dati_commessa = LogOrlatura::where('device_id', $device_id)
            ->where('commessa', $commessa)
            ->selectRaw('SUM(consumo) as consumo_commessa, SUM(tempo) as tempo_commessa')
            ->first();

        $consumo_totale = round($dati_totali->consumo_totale ?? 0, 2);
        $tempo_totale = round($dati_totali->tempo_totale ?? 0, 2);
        $consumo_commessa = round($dati_commessa->consumo_commessa ?? 0, 2);
        $tempo_commessa = round($dati_commessa->tempo_commessa ?? 0, 2);

        return view('MF1.reports', get_defined_vars());
    }

    public function manuale(Request $request)
    {
        return view('MF1.manuale', get_defined_vars());
    }

    public function settingsSave(Request $request)
    {
        DB::beginTransaction();
        try {
            $setting = $request->setting;
            $value = $request->value;

            switch ($setting) {
                case '______':
                    $this->______->setValue(1);
                    break;
                case 'data_cambio_olio':
                case 'data_cambio_spola':
                    $this->{$setting}->setValue($value);
                    break;
                case 'richiesta_filato':
                case 'richiesta_intervento':
                    Tasks::create([
                        'device_id' => $this->device_id,
                        'task_type' => $setting,
                        'status' => 'UNASSIGNED'
                    ]);
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
            foreach ($request->settings as $key => $value) {
                if (in_array($key, ['parametro_olio_attivo', 'parametro_spola_attivo'])) {
                    $value = $value ? 1 : 0;
                }
                $this->{$key}->setValue($value);
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
        return view('MF1.campionatura', get_defined_vars());
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
                    $log_orlatura = LogOrlatura::whereBetween('data', [$start, $stop])->get();
                    $consumo = round($log_orlatura->sum('consumo'), 2);
                    $tempo = round($log_orlatura->sum('tempo'));

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
        } catch (\Exception $th) {
            DB::rollback();

            $response = ['success' => false, 'msg' => $th->getMessage()];
        }

        return response()->json($response);
    }
}
