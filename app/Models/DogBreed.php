<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DogBreed extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug','size','notes'];

    public function dogs()
    {
        return $this->hasMany(Dog::class, 'breed_id');
    }
}
