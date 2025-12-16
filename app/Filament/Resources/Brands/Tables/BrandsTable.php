<?php

namespace App\Filament\Resources\Brands\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables;

class BrandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('slug'),

                Tables\Columns\ImageColumn::make('logo_url'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}


// namespace App\Filament\Resources\Brands\Tables;

// use Filament\Actions\BulkActionGroup;
// use Filament\Actions\DeleteBulkAction;
// use Filament\Actions\EditAction;
// use Filament\Actions\ForceDeleteBulkAction;
// use Filament\Actions\RestoreBulkAction;
// use Filament\Tables\Filters\TrashedFilter;
// use Filament\Tables\Table;
// use Filament\Tables;

// class BrandsTable
// {
//     public static function configure(Table $table): Table
//     {
//         return $table
//             ->columns([
//                 //
//                 Tables\Columns\TextColumn::make('name')
//                 ->searchable(),

//                 Tables\Columns\TextColumn::make('slug'),
                    
//                 Tables\Columns\ImageColumn::make('logo')
//             ])
//             ->filters([
//                 TrashedFilter::make(),
//             ])
//             ->recordActions([
//                 EditAction::make(),
//             ])
//             ->toolbarActions([
//                 BulkActionGroup::make([
//                     DeleteBulkAction::make(),
//                     ForceDeleteBulkAction::make(),
//                     RestoreBulkAction::make(),
//                 ]),
//             ]);
//     }
// }
