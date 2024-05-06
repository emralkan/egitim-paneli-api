<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguagesLine extends Model
{
    use HasFactory;


    protected $table = "languagesLine";
    protected $fillable = [
        "id",
        "key",
        "text",
        "language",
    ];
}
