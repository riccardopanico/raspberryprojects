<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Impostazioni;
use Illuminate\Http\Request;
use App\Models\LogOperazioni;
use App\Models\FornitoriRecapiti;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class HomeController extends Controller
{

    public function home(Request $request)
    {
        $Impostazioni = $this->getSettings();

        extract($Impostazioni->pluck('valore', 'codice')->toArray());

        try {
            $FornitoriRecapiti = collect($this->getTecnici());
        } catch (\Throwable $th) {
            $FornitoriRecapiti = collect([]);
        }

        return view('home', get_defined_vars());
    }

    public function verificaBarcode(Request $request)
    {
        return ['success' => (collect($this->getFaseGa2($request->barcode))->count() > 0)];
    }

    public function getOperatore($id_operatore)
    {
        $Impostazioni = $this->getSettings();

        $ip_local_server = $Impostazioni->where('codice', 'ip_local_server')->first()->valore;
        $porta_local_server = $Impostazioni->where('codice', 'porta_local_server')->first()->valore;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post("http://$ip_local_server:$porta_local_server/api/get_operatore_ga2", [
            'codice_operatore' => $id_operatore
        ]);

        return collect(json_decode($response->body()));
    }

    public function getTecnico()
    {
        $Impostazioni = $this->getSettings();

        $ip_local_server = $Impostazioni->where('codice', 'ip_local_server')->first()->valore;
        $porta_local_server = $Impostazioni->where('codice', 'porta_local_server')->first()->valore;
        $id_tecnico = $Impostazioni->where('codice', 'id_tecnico')->first()->valore;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post("http://$ip_local_server:$porta_local_server/api/get_tecnico_ga2", [
            'codice_tecnico' => $id_tecnico
        ]);

        return collect(json_decode($response->body()));
    }

    public function getTecnici()
    {
        $Impostazioni = $this->getSettings();

        $ip_local_server = $Impostazioni->where('codice', 'ip_local_server')->first()->valore;
        $porta_local_server = $Impostazioni->where('codice', 'porta_local_server')->first()->valore;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post("http://$ip_local_server:$porta_local_server/api/get_tecnici_ga2", []);

        return collect(json_decode($response->body()));
    }

    public function autenticaOperazione(Request $request)
    {
        $operatore = $this->getOperatore($request->operatore);
        if($operatore->count() < 1){
            return ['success' => false];
        }
        Impostazioni::where('codice', 'id_operatore')->update(['valore' => $operatore->first()->CODICE_OPERATORE]);
        return ['success' => true];
    }

    public function invioSms()
    {
        $Impostazioni = $this->getSettings();

        $ip_local_server = $Impostazioni->where('codice', 'ip_local_server')->first()->valore;
        $porta_local_server = $Impostazioni->where('codice', 'porta_local_server')->first()->valore;
        $id_operatore = $Impostazioni->where('codice', 'id_operatore')->first()->valore;
        $id_macchina = $Impostazioni->where('codice', 'id_macchina')->first()->valore;

        $operatore = $this->getOperatore($id_operatore)->first();
        $tecnico = $this->getTecnico()->first();

        $cognome_tecnico = $tecnico->COGNOME;
        $nome_tecnico = $tecnico->NOME;
        $telefono_tecnico = $tecnico->TELEFONO;

        $cognome_operatore = $operatore->COGNOME;
        $nome_operatore = $operatore->NOME;

        $messaggio = "Salve $cognome_tecnico $nome_tecnico, \nè stato richiesto un intervento per la macchina Numero $id_macchina dell'operatore $cognome_operatore $nome_operatore.\nUn saluto.";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post("http://$ip_local_server:$porta_local_server/api/sms", [
            'telefono' => $telefono_tecnico,
            'messaggio' => $messaggio
        ]);

        $response = json_decode($response->body());
        return $response->success;
    }

    public function retiDisponibili(Request $request)
    {

        try {
            $numTentativiReset = 1;
            $numTentativiReimposta = 1;
            nuovoTentativo:

            $process = new Process(['sudo', 'iwlist', 'wlan0', 'scan']);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $output = $process->getOutput();
            if (strpos($output, 'Network is down') !== false) {
                throw new \Exception('Network is down');
            }
            preg_match_all('/ESSID:"(.*?)"/', $output, $matches);

            return $matches[1];
        } catch (\Exception $ex) {
            if($this->resetRete()){
                if($numTentativiReset > 0) {
                    $numTentativiReset--;
                    goto nuovoTentativo;
                }
            } else {
                if($this->impostaRete(true)) {
                    if($numTentativiReimposta > 0) {
                        $numTentativiReimposta--;
                        goto nuovoTentativo;
                    }
                }
            }
            return response()->json(['error' => $ex], 500);
        }
    }

    public function impostaRete($reimposta = false)
    {
        try {
            $Impostazioni = $this->getSettings();

            $newSSID = $Impostazioni->where('codice', 'network_name')->first()->valore;
            $newPSK = $Impostazioni->where('codice', 'network_password')->first()->valore;

            $configFilePath = '/etc/wpa_supplicant/wpa_supplicant.conf';

            $fileContent = $fileContentBase = "ctrl_interface=/var/run/wpa_supplicant
ctrl_interface_group=0
update_config=1
country=IT
";

            if(!$reimposta){
                $fileContent .= "
network={
    ssid=\"$newSSID\"
    psk=\"$newPSK\"
    key_mgmt=WPA-PSK
}";
            }

            $fileContent = addcslashes($fileContent, '"');
            $command = "echo \"$fileContent\" > $configFilePath";
            $process = Process::fromShellCommandline($command);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            return $this->resetRete();
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function resetRete()
    {
        try {
            $Impostazioni = $this->getSettings();

            $network_interface = $Impostazioni->where('codice', 'network_interface')->first()->valore;

            $commandList = [
                "sudo ifdown wlan0",
                "sudo ifdown eth0",
                "sudo ifup $network_interface",
            ];

            foreach ($commandList as $command) {
                $process = new Process(explode(' ', $command));
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }
                sleep(1);
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }

    }

    public function shutdown()
    {
        $process = new Process(['sudo', 'shutdown', 'now']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

    }

    public function impostaIP()
    {
        try {
            $Impostazioni = $this->getSettings();

            $ip_macchina = $Impostazioni->where('codice', 'ip_macchina')->first()->valore;
            $gateway = $Impostazioni->where('codice', 'gateway')->first()->valore;
            $subnet = $Impostazioni->where('codice', 'subnet')->first()->valore;
            $dns_nameservers = $Impostazioni->where('codice', 'dns_nameservers')->first()->valore;

            $commandList = [
                "sudo sed -i 's/address .*/address " . $ip_macchina . "/g' /etc/network/interfaces",
                "sudo sed -i 's/netmask .*/netmask " . $subnet . "/g' /etc/network/interfaces",
                "sudo sed -i 's/gateway .*/gateway " . $gateway . "/g' /etc/network/interfaces",
                "sudo sed -i 's/dns-nameservers .*/dns-nameservers " . $dns_nameservers . "/g' /etc/network/interfaces",
            ];

            foreach ($commandList as $command) {
                $process = Process::fromShellCommandline($command);
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }
            }
            return $this->resetRete();
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function loggaOperazione(Request $request)
    {
        $Impostazioni = $this->getSettings();
        $id_macchina = $Impostazioni->where('codice', 'id_macchina')->first()->valore;
        $id_operatore = $Impostazioni->where('codice', 'id_operatore')->first()->valore;

        try {

            LogOperazioni::create([
                'id_macchina' => $id_macchina,
                'id_operatore' => $request->operatore?:$id_operatore,
                'codice' => $request->action,
                'valore' => $request->input,
            ]);

        } catch (\Exception $th) {
            return false;
        }

        return true;
    }

    public function settingsSave(Request $request)
    {
        DB::beginTransaction();
        try {
            switch ($request->action) {
                case 'conferma_intervento':
                    Impostazioni::where('codice', 'richiesta_manutenzione')->update(['valore' => 0]);
                    break;
                case 'ip_macchina':
                case 'gateway':
                case 'dns_nameservers':
                case 'subnet':
                    Impostazioni::where('codice', $request->action)->update(['valore' => $request->input]);
                    if(!$this->impostaIP()){
                        throw new \Exception("Errore durante il cambio IP!");
                    }
                    break;
                case 'last_barcode':
                    $barcode = $request->input;
                    $barcode_length = strlen($barcode);
                    if(in_array($barcode_length, [11, 12, 14])){
                        if ($barcode_length == 11) {
                            $codpref = intval(substr($barcode, 0, 2));
                            $nlotto = intval(substr($barcode, 2, 4));
                            $progr = intval(substr($barcode, 6, 3));
                            $nfasebol = intval(substr($barcode, 9, 1));
                        } elseif ($barcode_length == 12) {
                            $codpref = intval(substr($barcode, 1, 2));
                            $nlotto = intval(substr($barcode, 3, 4));
                            $progr = intval(substr($barcode, 7, 3));
                            $nfasebol = intval(substr($barcode, 10, 1));
                        } elseif ($barcode_length == 14) {
                            $codpref = intval(substr($barcode, 1, 2));
                            $nlotto = intval(substr($barcode, 3, 4));
                            $progr = intval(substr($barcode, 7, 3));
                            $nfasebol = intval(substr($barcode, 10, 3));
                        }
                    } else {
                        return ['success' => false, 'msg' => 'Lunghezza barcode errata!'];
                    }

                    Impostazioni::where('codice', 'last_barcode')->update(['valore' => $request->input]);
                    Impostazioni::whereIn('codice', ['T1codlavor', 'T1data_lavor', 'TCcodlavor', 'TCdata_lavor'])->update(['valore' => null]);
                    $response = collect($this->getFaseGa2($request->input));
                    if($response->count() > 0) {
                        foreach ($response as $key => $value) {
                            Impostazioni::where('codice', $value->CODFASE.'codlavor')->update(['valore' => $value->CODLAVOR]);
                            Impostazioni::where('codice', $value->CODFASE.'data_lavor')->update(['valore' => $value->DATA]);
                        }
                    }

                    $response = collect($this->getInfoGa2($request->input));
                    $prefisso = $response[0];
                    $articolo = $response[1];
                    Impostazioni::where('codice', 'prefisso')->update(['valore' => $prefisso]);
                    Impostazioni::where('codice', 'lotto')->update(['valore' => $nlotto]);
                    Impostazioni::where('codice', 'articolo')->update(['valore' => $articolo]);

                    $response = collect($this->getSituazioneGa2($request->input, 20));
                    Impostazioni::where('codice', 'SUsituazione')->update(['valore' => json_encode($response)]);

                    $response = collect($this->getSituazioneGa2($request->input, 1));
                    Impostazioni::where('codice', 'PE1situazione')->update(['valore' => json_encode($response)]);
                    $response = collect($this->getSituazioneGa2($request->input, 2));
                    Impostazioni::where('codice', 'PE2situazione')->update(['valore' => json_encode($response)]);
                    $response = collect($this->getSituazioneGa2($request->input, 3));
                    Impostazioni::where('codice', 'PE3situazione')->update(['valore' => json_encode($response)]);

                    break;
                case 'network_name':
                    Impostazioni::where('codice', 'network_name')->update(['valore' => $request->more_info]);
                    Impostazioni::where('codice', 'network_password')->update(['valore' => $request->input]);
                    if(!$this->impostaRete()){
                        throw new \Exception("Errore durante il reset della rete!");
                    }
                    break;
                case 'richiesta_manutenzione':
                    Impostazioni::where('codice', 'richiesta_manutenzione')->update(['valore' => 1]);
                    Impostazioni::where('codice', 'id_tecnico')->update(['valore' => $request->input]);
                    if(!$this->invioSms()){
                        throw new \Exception("Errore durante l'invio SMS!");
                    }
                    break;
                default:
                    Impostazioni::where('codice', $request->action)->update(['valore' => $request->input]);
                    break;
            }

            if(!$this->loggaOperazione($request)){
                throw new \Exception("Errore durante il salvetaggio dei Log Operazioni!");
            }

            DB::commit();

            return ['success' => true];
        } catch (\Exception $th) {
            DB::rollback();
            return ['success' => false, 'msg' => $th->getMessage()];
        }

    }

    public function getSettings()
    {
        return Impostazioni::select('codice', 'valore')->get();
    }

    public function getFaseGa2($barcode)
    {
        try {
            $Impostazioni = $this->getSettings();

            $ip_local_server = $Impostazioni->where('codice', 'ip_local_server')->first()->valore;
            $porta_local_server = $Impostazioni->where('codice', 'porta_local_server')->first()->valore;

            $barcode_length = strlen($barcode);
            if(in_array($barcode_length, [11, 12, 14])){
                if ($barcode_length == 11) {
                    $codpref = intval(substr($barcode, 0, 2));
                    $nlotto = intval(substr($barcode, 2, 4));
                    $progr = intval(substr($barcode, 6, 3));
                    $nfasebol = intval(substr($barcode, 9, 1));
                } elseif ($barcode_length == 12) {
                    $codpref = intval(substr($barcode, 1, 2));
                    $nlotto = intval(substr($barcode, 3, 4));
                    $progr = intval(substr($barcode, 7, 3));
                    $nfasebol = intval(substr($barcode, 10, 1));
                } elseif ($barcode_length == 14) {
                    $codpref = intval(substr($barcode, 1, 2));
                    $nlotto = intval(substr($barcode, 3, 4));
                    $progr = intval(substr($barcode, 7, 3));
                    $nfasebol = intval(substr($barcode, 10, 3));
                }

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json'
                ])->post("http://$ip_local_server:$porta_local_server/api/get_fase_ga2", [
                    'codpref' => $codpref,
                    'nlotto' => $nlotto
                ]);

                return json_decode($response->body());
            } else {
                return ['success' => false, 'msg' => 'Lunghezza barcode errata!'];
            }
        } catch (\Exception $th) {
            return ['success' => false, 'msg' => $th->getMessage()];
        }
    }

    public function getSituazioneGa2($barcode, $codice)
    {
        try {
            $Impostazioni = $this->getSettings();

            $ip_local_server = $Impostazioni->where('codice', 'ip_local_server')->first()->valore;
            $porta_local_server = $Impostazioni->where('codice', 'porta_local_server')->first()->valore;

            $barcode_length = strlen($barcode);
            if(in_array($barcode_length, [11, 12, 14])){
                if ($barcode_length == 11) {
                    $codpref = intval(substr($barcode, 0, 2));
                    $nlotto = intval(substr($barcode, 2, 4));
                    $progr = intval(substr($barcode, 6, 3));
                    $nfasebol = intval(substr($barcode, 9, 1));
                } elseif ($barcode_length == 12) {
                    $codpref = intval(substr($barcode, 1, 2));
                    $nlotto = intval(substr($barcode, 3, 4));
                    $progr = intval(substr($barcode, 7, 3));
                    $nfasebol = intval(substr($barcode, 10, 1));
                } elseif ($barcode_length == 14) {
                    $codpref = intval(substr($barcode, 1, 2));
                    $nlotto = intval(substr($barcode, 3, 4));
                    $progr = intval(substr($barcode, 7, 3));
                    $nfasebol = intval(substr($barcode, 10, 3));
                }

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json'
                ])->post("http://$ip_local_server:$porta_local_server/api/get_situazione_ga2", [
                    'codice' => $codice,
                    'codpref' => $codpref,
                    'nlotto' => $nlotto
                ]);

                return json_decode($response->body());
            } else {
                return ['success' => false, 'msg' => 'Lunghezza barcode errata!'];
            }
        } catch (\Exception $th) {
            return ['success' => false, 'msg' => $th->getMessage()];
        }
    }

    public function getInfoGa2($barcode)
    {
        try {
            $Impostazioni = $this->getSettings();

            $ip_local_server = $Impostazioni->where('codice', 'ip_local_server')->first()->valore;
            $porta_local_server = $Impostazioni->where('codice', 'porta_local_server')->first()->valore;

            $barcode_length = strlen($barcode);
            if(in_array($barcode_length, [11, 12, 14])){
                if ($barcode_length == 11) {
                    $codpref = intval(substr($barcode, 0, 2));
                    $nlotto = intval(substr($barcode, 2, 4));
                    $progr = intval(substr($barcode, 6, 3));
                    $nfasebol = intval(substr($barcode, 9, 1));
                } elseif ($barcode_length == 12) {
                    $codpref = intval(substr($barcode, 1, 2));
                    $nlotto = intval(substr($barcode, 3, 4));
                    $progr = intval(substr($barcode, 7, 3));
                    $nfasebol = intval(substr($barcode, 10, 1));
                } elseif ($barcode_length == 14) {
                    $codpref = intval(substr($barcode, 1, 2));
                    $nlotto = intval(substr($barcode, 3, 4));
                    $progr = intval(substr($barcode, 7, 3));
                    $nfasebol = intval(substr($barcode, 10, 3));
                }

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json'
                ])->post("http://$ip_local_server:$porta_local_server/api/get_info_ga2", [
                    'codpref' => $codpref,
                    'nlotto' => $nlotto
                ]);

                return json_decode($response->body());
            } else {
                return ['success' => false, 'msg' => 'Lunghezza barcode errata!'];
            }
        } catch (\Exception $th) {
            return ['success' => false, 'msg' => $th->getMessage()];
        }
    }

    public function ping($ip)
    {
        $result = $this->executePing($ip);
        return response()->json(['result' => $result]);
    }

    private function executePing($ip)
    {
        $command = "ping -c 1 $ip";
        $output = [];
        $status = 0;
        exec($command, $output, $status);
        return $status === 0;
    }

}
