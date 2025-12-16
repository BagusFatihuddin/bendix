<?php

namespace App\Models;

use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'icon_url',
        'icon_public_id',
    ];

    protected static function booted()
    {
        static::deleting(function (Category $category) {
            if ($category->icon_public_id) {
                CloudinaryService::deleteImage($category->icon_public_id);
            }
        });
    }

    

    /**
     * Slug otomatis
     */
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * 🔥 MUTATOR UTAMA: Upload icon ke Cloudinary
     */
    public function setIconUrlAttribute($value): void
    {
        // Kalau bukan upload baru (misal edit tanpa ganti icon)
        if (! $value instanceof TemporaryUploadedFile) {
            $this->attributes['icon_url'] = $value;
            return;
        }

        // Hapus icon lama jika ada
        if (! empty($this->icon_public_id)) {
            CloudinaryService::deleteImage($this->icon_public_id);
        }

        // Upload baru
        $result = CloudinaryService::uploadImage($value, 'categories');

        $this->attributes['icon_url'] = $result['url'];
        $this->attributes['icon_public_id'] = $result['public_id'];
    }

    public function brandCategories(): HasMany
    {
        return $this->hasMany(BrandCategory::class, 'category_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }


public function brands(): BelongsToMany
{
    return $this->belongsToMany(
        Brand::class,
        'products',        // pivot implicit
        'category_id',
        'brand_id'
    )->distinct();
}

}
