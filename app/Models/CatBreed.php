<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatBreed extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'size', 'notes'];

    public function cats()
    {
        return $this->hasMany(Cat::class, 'breed_id');
    }
}



