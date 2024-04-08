<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Year;

class UserYear extends Model
{
    use HasFactory;

    protected $table = 'user_years';
    protected $fillable = [
        'user_id',
        'year_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function contributions()
    {
        return $this->hasMany(Contribution::class, 'user_year_id');
    }
}
