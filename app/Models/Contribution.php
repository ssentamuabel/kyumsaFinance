<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\UserYear;

class Contribution extends Model
{
    use HasFactory;

    protected $table = 'contributions';
    protected $fillable = [
        'amount',
        'month',
        'telephone',
        'network',
        'user_year_id'
    ];



    public function userYear()
    {
        return $this->belongsTo(UserYear::class, 'user_year_id');
    }

    public function user()
    {
        return $this->userYear->user();
    }

    public function year()
    {
        return $this->userYear->year();
    }
}
