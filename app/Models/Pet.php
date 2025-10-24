<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'species',
        'breed',
        'age',
        'price',
        'description',
        'image_url',
        'is_available',
        'user_id',
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'species' => 'required|string|max:100',
        'price' => 'required|numeric|min:0',
        'description' => 'required|string|max:1000',
    ];

    /**
     * Get the user that owns the pet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a shortened version of the pet's description.
     */
    public function shortDescription()
    {
        return Str::limit($this->description, 50);
    }
}
