<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'validityPeriod', 'price', 'discount', 'discount_period'];

    public function educations()
    {
        return $this->belongsToMany(Education::class, 'package_education', 'package_id', 'education_id');
    }

}
