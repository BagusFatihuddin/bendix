<?php

namespace App\Models;

use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'thumbnail_url',
        'thumbnail_public_id',
        'address',
        'is_open',
    ];

    protected $casts = [
        'is_open' => 'boolean',
    ];

    protected static function booted()
    {
        static::deleting(function (Store $store) {
            if ($store->thumbnail_public_id) {
                CloudinaryService::deleteImage($store->thumbnail_public_id);
            }
        });
    }

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

        $result = CloudinaryService::uploadImage($value, 'stores');

        $this->attributes['thumbnail_url'] = $result['url'];
        $this->attributes['thumbnail_public_id'] = $result['public_id'];
    }
}
