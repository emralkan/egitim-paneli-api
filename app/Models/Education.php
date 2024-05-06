<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'photo', 'contents', 'package_id'];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_education', 'education_id', 'package_id');
    }

    public function games()
    {
        return $this->belongsToMany(Games::class, 'education_games', 'education_id', 'game_id');
    }



}
