<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\UserMonth;

class Contribution extends Model
{
    use HasFactory;

    protected $table = 'contributions';
    protected $fillable = [
        'amount',
        'year',
        'telephone',
        'network',
        'user_month_id'
    ];



    public function userMonths(): BelongsTo
    {
        return $this->belongsTo(UserMonth::class);
    }
}
