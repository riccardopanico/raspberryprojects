<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Puoi accedere direttamente alle variabili con `$this->nome_variabile`
        $error = session('error');

        return view('MF1.login', get_defined_vars());
    }

    public function signin(Request $request)
    {
        try {
            $user = User::where('badge', $request->badge)->firstOrFail();
            Auth::login($user);

            // Imposta direttamente il valore utilizzando il modello della variabile
            $this->badge->setValue($user->badge);

            return redirect()->intended('home');
        } catch (\Throwable $th) {
            return redirect()->route('login')->with(['error' => 'BADGE NON VALIDO!']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
