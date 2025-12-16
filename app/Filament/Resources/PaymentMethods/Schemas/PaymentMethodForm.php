<?php

namespace App\Filament\Resources\PaymentMethods\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\Toggle;

class PaymentMethodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Forms\Components\TextInput::make('provider')
                ->placeholder('bank / dana / gopay')
                ->maxLength(50),

            Forms\Components\TextInput::make('name')
                ->label('Display Name')
                ->required(),

            Forms\Components\FileUpload::make('image')
                ->label('Logo')
                ->image()
                ->storeFiles(false) // supaya mutator bisa ambil TemporaryUploadedFile
                ->directory('payment-methods'),

            Forms\Components\TextInput::make('account_name')
                ->label('Account Name'),

            Forms\Components\TextInput::make('account_number')
                ->label('Account Number'),

            Forms\Components\Textarea::make('details')
                ->label('Details')
                ->columnSpanFull(),

            Toggle::make('active')
                ->label('Active')
                ->default(true)
                ->required(),

            Forms\Components\TextInput::make('sort_order')
                ->numeric()
                ->default(0)
                ->required(),
        ]);
    }
}
