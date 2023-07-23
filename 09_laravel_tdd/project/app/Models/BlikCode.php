<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlikCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'code'
    ];
    protected $table = 'blik_codes';

    public function key(): string
    {
        return 'model_blik_code'.$this->id;
    }
}
