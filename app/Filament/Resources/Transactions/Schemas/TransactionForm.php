<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\Select;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Forms\Components\TextInput::make('name')
                ->required(),

            Forms\Components\TextInput::make('phone_number')
                ->tel()
                ->required(),

            Forms\Components\Textarea::make('address')
                ->rows(3)
                ->required(),

            Forms\Components\FileUpload::make('proof_url')
                ->label('Payment Proof')
                ->image()
                ->storeFiles(false)    // ⬅️ WAJIB supaya mutator dapet TemporaryUploadedFile
                ->required(),

            Forms\Components\TextInput::make('duration')
                ->numeric()
                ->minValue(1)
                ->required(),

            Forms\Components\Select::make('delivery_type')
                ->options([
                    'pickup' => 'Pickup',
                    'home_delivery' => 'Home Delivery',
                ])
                ->required(),

            Forms\Components\Toggle::make('is_paid')
                ->default(false),

            Forms\Components\DatePicker::make('started_at')
                ->required(),

            Forms\Components\DatePicker::make('ended_at')
                ->required(),

            Forms\Components\TextInput::make('total_amount')
                ->numeric()
                ->required(),

            Select::make('product_id')
                ->relationship('product', 'name')
                ->required(),

            Select::make('store_id')
                ->relationship('store', 'name')
                ->required(),
        ]);
    }
}
