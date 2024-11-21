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
        $impostazioni = Impostazioni::all()->pluck('valore', 'codice')->toArray();
        extract($impostazioni);
        $error = session('error');

        return view('MF1.login', get_defined_vars());
    }

    public function signin(Request $request)
    {
        try {
            // DB::enableQueryLog();
            $user = User::where('badge', $request->id_operatore)->firstOrFail();
            Auth::login($user);

            Impostazioni::where('codice', 'id_operatore')->update(['valore' => $request->id_operatore]);

            extract(Impostazioni::all()->pluck('valore', 'codice')->toArray());
            // session()->put(Impostazioni::all()->pluck('valore', 'codice')->toArray());

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

}
