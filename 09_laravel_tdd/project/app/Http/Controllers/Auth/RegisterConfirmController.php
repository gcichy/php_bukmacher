<?php

namespace App\Http\Controllers\Auth;

use App\Models\Bet;
use App\Models\BetEvent;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class RegisterConfirmController
{
    public function index(Request $request): View
    {
        $user = $request->session()->get('user');
        if ($user) {
            return view('auth.confirm')->with('user', $user);
        }
        return view('auth.confirm');
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request['token']) {
            if (substr(strval($request['_token']), 0, 10) == $request['token']) {
                $user = DB::table('users')->where('person_number', $request->person_number);
                $user->update(['confirmed' => true]);
                return redirect()->route('login')->with('status', 'Rejestracja przebiegła pomyślnie. Powodzenia!');
            }
            return back()->withErrors(['token' => 'Wprowadzony kod jest niepoprawny. Spróbuj ponownie']);
        }


//
//            $user = DB::table('users')->where('email', $request['email']);
//            if (!is_null($user->first())) {
//                $user->update(['password' => Hash::make($request->password)]);
//                return redirect()->route('login')->with('status', 'Zmieniono hasło');
//            }
//            return back()->withInput($request->only('email'))
//                ->withErrors(['email' => 'Wprowadzony email jest niepoprawny. Spróbuj ponownie']);
//
//        }
//        return back()->withInput($request->only('email'))
//            ->withErrors(['token' => 'Wprowadzony kod jest niepoprawny. Spróbuj ponownie']);
        ////        $user_s = DB::table('users')->where('email', $request['email'])->first();
        ////        if (!is_null($user_s) && $user_s->email == $request['email']) {
        ////            return redirect()->route('password.reset', ['token' => $request['_token']]);
        ////        }
        return back()->withErrors(['token' => 'Wprowadź kod, który przesłaliśmy ci na maila']);
    }
}
