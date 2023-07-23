<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BetEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'bet_id', "event_id", "answer"
    ];
    protected $table = 'bet_events';

    public function key(): string
    {
        return 'model_bet_event_'.$this->id;
    }
}
