<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Variables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private function loadVariables()
    {
        $Variables = Variables::all();
        $variablesArray = [];
        foreach ($Variables as $variable) {
            $variablesArray[$variable->variable_code] = $variable->getValue();
        }
        return $variablesArray;
    }

    public function login(Request $request)
    {
        $variablesArray = $this->loadVariables();
        $error = session('error');

        return view('MF1.login', array_merge($variablesArray, get_defined_vars()));
    }

    public function signin(Request $request)
    {
        try {
            $user = User::where('badge', $request->badge)->firstOrFail();
            Auth::login($user);

            $variable = Variables::where('variable_code', 'badge')->firstOrFail();
            $variable->setValue($user->badge);
            $variable->save();

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
