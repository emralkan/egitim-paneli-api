<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationGame extends Model
{
    protected $table = 'education_games';
    protected $fillable = ['education_id', 'game_id'];
}
