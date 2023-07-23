<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Temp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            ['email' => 'required|email']
        );

        $user_s = User::where('email', $request['email'])->first();
        if ($user_s instanceof User) {
            if ($user_s->email == $request['email']) {
                return redirect()->route('password.reset', ['token' => $request['_token']])->with('email', $request['email']);
            }
        }
        return back()->withInput($request->only('email'))
                            ->withErrors(['email' => 'Konto z podanym adresem email nie istnieje']);


        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        //dd($request);

//        $status = Password::sendResetLink(
//            $request->only('email')
//        );
        //$token = $request['_token'];
        //return back();
//        return $status == Password::RESET_LINK_SENT
//                    ? back()->with('status', __($status))
//                    : back()->withInput($request->only('email'))
//                            ->withErrors(['email' => __($status)]);
    }
}
