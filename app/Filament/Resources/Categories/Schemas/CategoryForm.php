<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\FileUpload::make('icon_url')
                    ->image()
                    ->label('Icon')
                    ->storeFiles(false) // ⬅️ penting: jangan simpan otomatis ke disk
                    ->required(),
            ]);
    }
}
