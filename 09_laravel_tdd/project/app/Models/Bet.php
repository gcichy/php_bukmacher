<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', "date", "stake", "total_odd","status", "bet_result", "win_price"
    ];
    protected $table = 'bets';

    public function key(): string
    {
        return 'model_bet'.$this->id;
    }
}
