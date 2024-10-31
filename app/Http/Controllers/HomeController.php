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
use Symfony\Component\Process\Process;

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

        return view('MF1.impostazioni', get_defined_vars());
    }

    public function manuale(Request $request)
    {
        $impostazioni = Impostazioni::all()->pluck('valore', 'codice')->toArray();
        extract($impostazioni);

        return view('MF1.manuale', get_defined_vars());
    }

    public function reboot()
    {
        $command = 'clear && sudo systemctl stop getty@tty1.service && sudo reboot --no-wall';
        $process = Process::fromShellCommandline($command);
        $process->run();
    }

    public function shutdown() {}

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
        DB::beginTransaction();
        try {
            foreach ($request->settings as $key => $value) {
                Impostazioni::where('codice', $key)
                    ->update([
                        'valore' => $value
                    ]);
                LogOperazioni::create([
                    'id_macchina'  => $request->id_macchina,
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

    public function signalCampionatura_old(Request $request)
    {
        if ( !  empty($request) && $request->has(['action', 'timestamp', 'campione'])) {
            $timestamp = date('Y-m-d H:i:s', strtotime($request->timestamp));

            $campionatura           = new Campionatura();
            $campionatura->campione = $request->campione;
            if ($request->action === 'START') {
                $campionatura->start = $timestamp;
            } elseif ($request->action === 'STOP') {
                $campionatura->stop = $timestamp;
            }
            $campionatura->save();

            return ['success' => true, 'msg' => 'Timestamp received: ' . $timestamp . ' action received: ' . $request->action];
        } else {
            return ['success' => false, 'msg' => 'Campi mancanti'];
        }
    }

    public function signalCampionatura(Request $request)
    {
        $action    = $request->input('action');
        $timestamp = $request->input('timestamp');
        $campione  = $request->input('campione');
        $response  = ['success' => false];
        if ($action === 'START') {
            // Creazione di un nuovo record
            $campionatura = Campionatura::create([
                'campione' => $campione,
                'start'    => Carbon::parse($timestamp)
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
                $campionatura->stop = Carbon::parse($timestamp);
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

        return response()->json($response);
    }
}
