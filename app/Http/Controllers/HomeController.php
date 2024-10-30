<?php

namespace App\Http\Controllers;

use App\Models\LogOrlatura;
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

        return view('template_1.home', get_defined_vars());
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

        $consumo_totale   = $dati_totali->consumo_totale ?? 0;
        $tempo_totale     = $dati_totali->tempo_totale ?? 0;
        $consumo_commessa = $dati_commessa->consumo_commessa ?? 0;
        $tempo_commessa   = $dati_commessa->tempo_commessa ?? 0;

        return view('template_1.impostazioni', get_defined_vars());
    }

    public function manuale(Request $request)
    {
        $impostazioni = Impostazioni::all()->pluck('valore', 'codice')->toArray();
        extract($impostazioni);

        return view('template_1.manuale', get_defined_vars());
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
}
