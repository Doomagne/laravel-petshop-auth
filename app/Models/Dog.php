<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Dog extends Model
{
    use HasFactory;

    protected $fillable = [
        'breed_id','mix_breed_id','is_mix','name','slug','age_months','gender','color','size',
        'description','main_image','gallery','vaccinated','sterilized','status','location'
    ];

    protected $casts = [
        'gallery' => 'array',
        'vaccinated' => 'boolean',
        'sterilized' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($dog) {
            if (empty($dog->slug)) {
                $dog->slug = Str::slug($dog->name) . '-' . Str::random(6);
            }
        });
    }

    public function breed()
    {
        return $this->belongsTo(DogBreed::class, 'breed_id');
    }

    public function mixBreed()
    {
        return $this->belongsTo(DogBreed::class, 'mix_breed_id');
    }

    // URL helpers
    public function getMainImageUrlAttribute()
    {
        return $this->main_image ? asset('storage/' . $this->main_image) : null;
    }

    public function getGalleryUrlsAttribute()
    {
        if (! $this->gallery) return [];
        return collect($this->gallery)->map(fn($p) => asset('storage/' . $p))->all();
    }

    public function getBreedLabelAttribute(): string
    {
        if ($this->is_mix && $this->breed && $this->mixBreed) {
            return $this->breed->name . ' / ' . $this->mixBreed->name;
        }
        if ($this->breed) {
            return $this->breed->name;
        }
        return 'N/A';
    }

    public function getAgeLabelAttribute()
    {
        if (is_null($this->age_months)) return 'Unknown';
        if ($this->age_months < 12) return $this->age_months . ' mo';
        return floor($this->age_months / 12) . ' yr ' . ($this->age_months % 12 ? ($this->age_months % 12) . ' mo' : '');
    }
}
