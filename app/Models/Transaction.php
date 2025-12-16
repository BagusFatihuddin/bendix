<?php

namespace App\Models;
use Illuminate\Http\UploadedFile;
use App\Casts\MoneyCast;
use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

protected $fillable = [
    'name',
    'trx_id',
    'status',
    'proof_url',
    'proof_public_id',
    'phone_number',
    'address',
    'total_amount',
    'product_id',
    'store_id',
    'payment_method_id', // <= baru
    'duration',
    'is_paid',
    'delivery_type',
    'started_at',
    'ended_at',
];


    protected $casts = [
        'total_amount' => MoneyCast::class,
        'started_at' => 'date',
        'ended_at' => 'date',
        'is_paid' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function (Transaction $trx) {
            if (! $trx->trx_id) {
                $trx->trx_id = $trx->generateUniqueTrxId();
            }
        });

        static::deleting(function (Transaction $trx) {
            if ($trx->proof_public_id) {
                CloudinaryService::deleteImage($trx->proof_public_id);
            }
        });
    }

    public function generateUniqueTrxId()
    {
        $prefix = 'SW';
        do {
            $randomString = $prefix . mt_rand(1000, 9999);
        } while (self::where('trx_id', $randomString)->exists());
        return $randomString;
    }

public function setProofUrlAttribute($value): void
{
    // kalau bukan file upload (sudah URL string), simpan langsung
    if (! $value instanceof TemporaryUploadedFile && ! $value instanceof UploadedFile) {
        $this->attributes['proof_url'] = $value;
        return;
    }

    try {
        Log::debug('Mutator: uploading proof to Cloudinary', [
            'class' => get_class($value),
            'originalName' => method_exists($value, 'getClientOriginalName') ? $value->getClientOriginalName() : null,
            'size' => method_exists($value, 'getSize') ? $value->getSize() : null,
        ]);

        $result = CloudinaryService::uploadImage($value, 'transactions');

        if (! empty($result['url'])) {
            $this->attributes['proof_url'] = $result['url'];
        }
        if (! empty($result['public_id'])) {
            $this->attributes['proof_public_id'] = $result['public_id'];
        }

        Log::debug('Mutator: upload result', ['result' => $result]);
    } catch (\Throwable $e) {
        // log error — tapi jangan crash app; simpan pesan error supaya bisa dicek
        Log::error('Mutator: upload to Cloudinary failed', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        // opsi: set proof_url ke null (default) atau set error flag
        $this->attributes['proof_url'] = null;
    }
}

        public function paymentMethod()
    {
        return $this->belongsTo(\App\Models\PaymentMethod::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
