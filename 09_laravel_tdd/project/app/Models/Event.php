<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'opponent_1', 'opponent_2', 'date', 'discipline', 'time', 'timezone',
        'league', 'round', 'status', 'score'
    ];
    protected $table = 'events';

    public function key(): string
    {
        return 'model_event_'.$this->id;
    }
}
