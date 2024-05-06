<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{
    use HasFactory;

    protected $table = "modules";
    protected $fillable = [
        "id",
        "name",
    ];

    public function games()
    {
        return $this->belongsToMany(Games::class);
    }
}
