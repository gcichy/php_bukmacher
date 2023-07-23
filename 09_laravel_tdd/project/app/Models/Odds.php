<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Odds extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id', 'win_op_1', 'win_op_2',  'sum', 'is_special'
    ];
    protected $table = 'odds';

    public function key(): string
    {
        return 'model_odds_'.$this->id;
    }
}
