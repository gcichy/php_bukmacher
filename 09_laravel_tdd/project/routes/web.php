<?php


use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\BalanceController;
use App\Http\Controllers\Account\BuyPremiumController;
use App\Http\Controllers\Account\DeleteAccountController;
use App\Http\Controllers\Account\AccountHistoryController;
use App\Http\Controllers\BetResultController;
use App\Http\Controllers\BetsController;
use App\Http\Controllers\LiveBetsController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Auth\RegisterConfirmController;
use App\Http\Controllers\ScratchController;
use App\Http\Controllers\SpecialBetsController;
use App\Models\BlikCode;
use App\Models\Event;
use App\Models\Odds;
use App\Models\Bet;
use App\Models\Premium;
use App\Models\SpecialEvent;
use App\Models\User;
use Codeception\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use function PHPUnit\Framework\isNull;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    BetResultController::events_update();
    $user = Auth::user();
    if ($user) {
        return view('welcome')->with('user', $user);
    }
    return view('welcome');
})->name('welcome');


Route::get('/home', function (Request $request) {
    return redirect('/')->with('status', $request->session()->get('status'));
})->name('home');

Route::post('/home', function () {
    return redirect('/');
})->name('home');

Route::get('/bets/{disc?}', function (string $disc = null) {
    $user = Auth::user();
    $translate = [
        "football" => 'Piłka Nożna',
        "volleyball" => 'Siatkówka',
        "handball" => 'Piłka Ręczna',
        "dart" => 'Dart',
        "basketball" => 'Koszykówka',
        "esport" => 'E-sport'
    ];
    if ($disc == null) {
        $matches = Event::where('status', 'upcoming')->get();
    } else {
        $matches = Event::where('status', 'upcoming')->where('discipline', $translate[$disc])->get();
    }
    $oddsList = [];
    foreach ($matches as $match) {
        $oddsList[] = Odds::where('event_id', $match->id)->first();
    }

    return $user ?
        view('bets')->with('matches', $matches)->with('odds', $oddsList)->with('user', $user) :
        view('bets')->with('matches', $matches)->with('odds', $oddsList);
});

Route::get('/specialoffers', function () {
    $matches = SpecialEvent::all();
    $oddsList = [];
    foreach ($matches as $match) {
        $oddsList[] = DB::table('odds')->where('event_id', $match->id)->first();
    }
    $user = Auth::user();
    if ($user) {
        return view('specialoffers')->with('matches', $matches)->with('odds', $oddsList)->with('user', $user);
    }
    return view('specialoffers')->with('matches', $matches)->with('odds', $oddsList);
});

Route::get('/results', function () {
    $matches = Event::where('status', 'ended')->get();
    $matchesOdds = [];
    foreach ($matches as $match) {
        $odd = Odds::where('event_id', $match->id)->first();
        if (!is_null($odd)) {
            $matchesOdds[] = $odd;
        } else {
            $matchesOdds[] = Odds::all()->firstOrFail();
        }
    }
    $special_matches = SpecialEvent::all();
    $specials = [];
    $specialsOdds = [];
    $now = time(); // or your date as well
    foreach ($special_matches as $sm) {
        $date = substr(strval($sm->created_at), 0, 10);
        $your_date = strtotime($date);
        $dateDiff = $now - $your_date;
        $daysDiff = round($dateDiff / (60 * 60 * 24));
        if ($daysDiff >= 3) {
            $specials[] = $sm;
            $odd = Odds::where('event_id', $sm->id)->first();
            if (!is_null($odd)) {
                $specialsOdds[] = $odd;
            } else {
                $specialOdds[] = Odds::all()->firstOrFail();
            }
        }
    }
    $user = Auth::user();
    if ($user) {
        return view('results')
            ->with('user', $user)
            ->with('matches', $matches)
            ->with('matches_odds', $matchesOdds)
            ->with('special_matches', $specials)
            ->with('special_odds', $specialsOdds);
    }
    return view('results')
        ->with('matches', $matches)
        ->with('matches_odds', $matchesOdds)
        ->with('special_matches', $specials)
        ->with('special_odds', $specialsOdds);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/confirm', [RegisterConfirmController::class, 'store'])
    ->name('confirm.check');
Route::get('confirm/{_token}', [RegisterConfirmController::class, 'index'])
    ->name('auth.confirm');

Route::get('/scratchcard', [ScratchController::class, 'index'])->middleware(['auth', 'verified'])
    ->name('scratchcard');
Route::post('/scratchcard', [ScratchController::class, 'store'])->middleware(['auth', 'verified'])
    ->name('scratchcard.scratch');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/account', [AccountController::class, 'index'])->middleware(['auth', 'verified'])->name('account');

Route::get('/account/add_balance', [BalanceController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('account.balance');

Route::post('/account/add_balance', [BalanceController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('account.balance');


Route::get('/account/premium', [BuyPremiumController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('account.premium');
Route::post('/account/premium', [BuyPremiumController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('account.premium.buy');

Route::get('/account/delete', [DeleteAccountController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('account.delete');

Route::post('/account/delete', [DeleteAccountController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('account.delete');

//    $bets = Bet::all();
//    return view('account.delete')->with(['user', 'premium', 'bets'], [$user, $premium, $bets]);
//})->middleware(['auth', 'verified'])->name('account.premium');


Route::get('/account/history', [AccountHistoryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('account.history');

Route::post('/bets', [BetsController::class, 'store'])->name('bets.bet');
Route::post('/specialoffers', [SpecialBetsController::class, 'store'])->name('specialoffers.bet');


Route::get('/betcode', function () {
    return view('betcode');
})->name('betcode');

Route::get('/livebets', [\App\Http\Controllers\LiveBetsController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('livebets');
Route::post('/livebets', [LiveBetsController::class, 'store'])->name('livebets.bet');


require __DIR__.'/auth.php';
