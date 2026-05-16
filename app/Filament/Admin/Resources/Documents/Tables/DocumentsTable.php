<?php

namespace App\Filament\Admin\Resources\Documents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('application_id')
                    ->label('App ID')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Document Type'),

                IconColumn::make('verified')
                    ->boolean()
                    ->label('Verified'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Uploaded On'),
            ])
            ->filters([
                SelectFilter::make('type'),
                TernaryFilter::make('verified'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
