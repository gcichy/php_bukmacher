<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request])
            ->with('email', $request->session()->get('email'));
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $v = Validator::make($request->all(), [
           'token' => ['required'],
           'email' => ['required', 'email'],
           'password' => ['required', 'confirmed', Rules\Password::defaults()],
           'password_confirmation' => ['required'],
        ]);
        if ($v->fails()) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors($v);
        }
        //token validation - to simulate receiving real email, sent token must be inserted in the page
        //it will be 10 last characters of token from url: password-reset/{token}
        if (substr(strval($request['_token']), 0, 10) == $request['token']) {
            $user = DB::table('users')->where('email', $request['email']);
            if (!is_null($user->first())) {
                $user->update(['password' => Hash::make(strval($request['password']))]);//$request->input('password'), $request->password, $request['password']
                return redirect()->route('login')->with('status', 'Zmieniono hasło');
            }
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Wprowadzony email jest niepoprawny. Spróbuj ponownie']);
        }
        return back()->withInput($request->only('email'))
                ->withErrors(['token' => 'Wprowadzony kod jest niepoprawny. Spróbuj ponownie']);
    }
}
