<?php

namespace App\Models;

use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Brand extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name',
        'logo_url',
        'logo_public_id',
    ];

    protected static function booted()
    {
        static::deleting(function (Brand $brand) {
            if ($brand->logo_public_id) {
                CloudinaryService::deleteImage($brand->logo_public_id);
            }
        });
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // 🔥 Mutator Cloudinary untuk logo
    public function setLogoUrlAttribute($value): void
    {
        if (! $value instanceof TemporaryUploadedFile) {
            $this->attributes['logo_url'] = $value;
            return;
        }

        $result = CloudinaryService::uploadImage($value, 'brands');

        $this->attributes['logo_url'] = $result['url'];
        $this->attributes['logo_public_id'] = $result['public_id'];
    }

    public function brandCategories(): HasMany
    {
        return $this->hasMany(BrandCategory::class, 'brand_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
