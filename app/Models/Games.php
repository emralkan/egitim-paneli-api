<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'education_id', 'positionX', 'positionY', 'start_frame', 'flagX', 'flagY',
        'flag_image', 'sentence', 'back_image', 'box_color', 'part', 'block_limit', 'toolbox', 'blocks_define'];

    public function education()
    {
        return $this->belongsTo(Education::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Modules::class);
    }

    public function educations()
    {
        return $this->belongsToMany(Education::class, 'education_games', 'game_id', 'education_id');
    }

}
