<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\FileUpload::make('thumbnail_url')
                ->image()
                ->label('Thumbnail')
                ->storeFiles(false) // ⬅️ penting: jangan simpan otomatis ke disk
                ->required(),

            Forms\Components\Textarea::make('address')
                ->rows(3)
                ->required(),

            Forms\Components\Toggle::make('is_open')
                ->label('Is Store Open?')
                ->default(true),
        ]);
    }
}
