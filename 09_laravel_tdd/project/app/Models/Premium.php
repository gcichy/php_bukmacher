<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Premium extends Model
{
    use HasFactory;

    protected $fillable = [
        'scratches_left', 'scratchcard_id', 'expiration_date', 'harakiried', 'user_id'
    ];
    protected $table = 'premiums';

    public function key(): string
    {
        return 'model_premium_'.$this->id;
    }
}
