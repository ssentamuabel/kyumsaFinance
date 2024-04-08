<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contribution;
use App\Models\UserYear;

class Year extends Model
{
    use HasFactory;

    protected $table = 'years';
    protected $fillable = [
                'year',
                'active'
    ];

    

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_years')->withPivot('id');
    }
}
