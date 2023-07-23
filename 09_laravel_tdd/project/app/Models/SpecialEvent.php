<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'question', 'answer_1', 'answer_2', 'correct'
    ];
    protected $table = 'special_events';

    public function key(): string
    {
        return 'model_special_event_'.$this->id;
    }
}
