<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'thumbnail_url',
        'thumbnail_public_id',
        'about',
        'category_id',
        'brand_id',
        'price',
    ];

    protected $casts = [
        'price' => MoneyCast::class,
    ];

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function setThumbnailUrlAttribute($value): void
    {
        if (! $value instanceof TemporaryUploadedFile) {
            $this->attributes['thumbnail_url'] = $value;
            return;
        }

        $result = CloudinaryService::uploadImage($value, 'products');

        $this->attributes['thumbnail_url'] = $result['url'];
        $this->attributes['thumbnail_public_id'] = $result['public_id'];
    }

    /**
     * Accessor: normalized image URL ready to use in views.
     * Returns:
     *  - absolute URL (http/https) if thumbnail_url is absolute
     *  - Storage::url(...) if value points to storage disk
     *  - asset(...) otherwise
     *  - null if no thumbnail_url
     */
public function getImageUrlAttribute()
{
    // jika ada thumbnail_url langsung pakai itu
    if (!empty($this->attributes['thumbnail_url'])) {
        return $this->attributes['thumbnail_url'];
    }

    // kalau ada relasi photos dan ada item, pakai yang pertama
    if ($this->relationLoaded('photos') && $this->photos->isNotEmpty()) {
        return $this->photos->first()->url ?? $this->photos->first()->thumbnail_url ?? null;
    }

    // coba lazy load photos jika belum
    if ($this->photos()->exists()) {
        $first = $this->photos()->first();
        return $first->url ?? $first->thumbnail_url ?? null;
    }

    return null;
}

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ProductPhoto::class);
    }
}
