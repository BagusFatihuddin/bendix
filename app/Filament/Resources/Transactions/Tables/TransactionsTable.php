<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('trx_id')
                    ->searchable()
                    ->label('Transaction ID'),

                Tables\Columns\ImageColumn::make('proof_url')
                    ->label('Proof'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone_number'),

                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean(),

                Tables\Columns\TextColumn::make('delivery_type'),

                Tables\Columns\TextColumn::make('started_at')
                    ->date(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->money('IDR'),
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
