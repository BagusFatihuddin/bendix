<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\FileUpload::make('logo_url')
                    ->image()
                    ->label('Logo')
                    ->storeFiles(false)   // penting: biar mutator dapet TemporaryUploadedFile
                    ->required(),

                Repeater::make('brandCategories')
                    ->relationship()
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required(),
                    ]),
            ]);
    }
}
