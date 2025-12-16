<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\Select;

class ProductForm
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

            Forms\Components\Textarea::make('about')
                ->rows(4)
                ->required(),

            Forms\Components\TextInput::make('price')
                ->numeric()
                ->required(),

            Select::make('brand_id')
                ->relationship('brand', 'name')
                ->required(),

            Select::make('category_id')
                ->relationship('category', 'name')
                ->required(),
        ]);
    }
}
