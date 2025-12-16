<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Services\CloudinaryService;

class PaymentMethod extends Model
{
    protected $fillable = [
        'provider',
        'name',
        'image',
        'account_name',
        'account_number',
        'details',
        'active',
        'sort_order',
    ];

    /**
     * Mutator: jika user upload file via Filament (TemporaryUploadedFile),
     * upload ke Cloudinary dan simpan **hanya** URL ke kolom `image`.
     * Jika value bukan TemporaryUploadedFile (mis. string URL), simpan apa adanya.
     */
    public function setImageAttribute($value): void
    {
        if (! $value instanceof TemporaryUploadedFile) {
            $this->attributes['image'] = $value;
            return;
        }

        // Upload ke Cloudinary (folder payment_methods)
        $result = CloudinaryService::uploadImage($value, 'payment_methods');

        // Pastikan CloudinaryService mengembalikan array dengan 'url'
        $this->attributes['image'] = $result['url'] ?? null;
    }

    /**
     * Accessor untuk memudahkan tampilan image URL (fallback ke placeholder)
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return $this->image;
        }

        return asset('images/payment-placeholder.png');
    }
}
