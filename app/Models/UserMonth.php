<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Month;
use App\Models\Contribution;

class UserMonth extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function month()
    {
        return $this->belongsTo(Month::class);
    }
    public function contributions(): HasMany
    {
        return $this->hasMany(Contribution::class);
    }
}
