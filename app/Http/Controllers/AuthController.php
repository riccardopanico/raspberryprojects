<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        extract($this->loadAllVariables());
        $error = session('error');
        $this->user_id->setValue(null);
        if (env('APP_NAME') !== 'RP1')
            $this->badge->setValue(null);
        return view(env('APP_NAME') . '.login', get_defined_vars());
    }

    public function signin(Request $request)
    {
        try {
            $user = User::where('badge', $request->badge)->firstOrFail();
            Auth::login($user);

            $this->user_id->setValue($user->id);
            if (env('APP_NAME') !== 'RP1')
                $this->badge->setValue($user->badge);

            return redirect()->intended('home');
        } catch (\Throwable $th) {
            return redirect()->route('login')->with(['error' => 'BADGE NON VALIDO!']);
        }
    }

    public function logout(Request $request)
    {
        if (env('APP_NAME') !== 'RP1')
            $this->badge->setValue(null);
        $this->user_id->setValue(null);

        Auth::logout();

        return redirect()->route('login');
    }
}
