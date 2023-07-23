<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\PESELRule;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string',  'max:255'],
            'surname' => ['required', 'string',  'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:'.User::class, 'regex:/^.+@.+$/'],#Rule::unique('users', 'email')
            'nickname' => ['required', 'string',  'max:255', 'unique:'.User::class],
            'password' => ['required',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#\$%\^&\*])(?=.{8,})/'],//Rules\Password::defaults()],
            'repeat_password' => ['required', 'same:password'],
            'phone_number' => ['required', 'string', 'starts_with:+', 'regex:/^\+\d{11}$/'],
            'person_number' => ['required', new PESELRule()], //, 'string', 'regex:/^[0-9]{11}$/'
            'promo_code' => [],//['string'], //nwn co tutaj
            'terms' => ['required', 'accepted'] //albo mozna boolean zamiast accepted

        ]);

        //TODO sprawdzac czy jest promo_code i zmienic deposit na cos jesli jest (deposit ustawiam w create)


        //dd($request);
        //jak to jeszcze zmienic,
        //chyba musze pozamieniac wszystkie - na _
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'nickname' => $request->nickname,
            'password' => Hash::make(strval($request['password'])),
            'phone_number' => $request->phone_number,
            'person_number' => $request->person_number,
            'promo_code' => $request->promo_code,
            'deposit' => 0,
            'premium' => false,
            'confirmed' => false
        ]);

        //cos takiego msuze dodac jak chce przejsc do innej strony przy regulaminie
        //Route::get('/home', [HomeController::class, 'index'])->name('home');

        event(new Registered($user));

        //Auth::login($user);
        return redirect()->route('auth.confirm', ['_token' => $request['_token']])->with('user', $user);
    }
}
