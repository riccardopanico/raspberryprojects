<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Impostazioni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) {

        $id_macchina = Impostazioni::where('codice', 'id_macchina')->first()->valore;
        return view('template_2.login', ['id_macchina' => $id_macchina, 'error' => session('error')]);
    }

    public function signin(Request $request) {
        try {
            $user = User::findOrFail($request->id_operatore);
            Auth::login($user);

            Impostazioni::where('codice', 'id_operatore')->update(['valore' => $request->id_operatore]);

            return redirect()->intended('home');
        } catch (\Throwable $th) {
            return redirect()->route('login')->with(['error' => 'BADGE NON VALIDO!']);
        }
    }

    public function logout(Request $request) {
        Auth::logout();

        return redirect()->route('login');
    }
}
