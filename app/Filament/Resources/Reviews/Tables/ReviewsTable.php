<?php

namespace App\Filament\Resources\Reviews\Tables;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Reviews\ReviewResource;
use App\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\Action;
class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->searchable()->url(fn($record) =>ProductResource::getUrl('edit', ['record' => $record->product]))->weight('bold')
                    ->sortable(),
                TextColumn::make('customer.name')->searchable()->url(fn($record) =>CustomerResource::getUrl('edit', ['record' => $record->customer]))->weight('bold')
                    ->sortable(),

                TextColumn::make('rating')
                    ->formatStateUsing(fn($state) => str_repeat('⭐', $state))->color('warning')
                    ->sortable(),
                TextColumn::make('title')->limit(50)
                    ->searchable(),
                TextColumn::make('comment')->limit(100)
                    ->searchable(),
                IconColumn::make('is_verified')
                    ->boolean(),
                IconColumn::make('is_approved')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                TernaryFilter::make('is_approved')
                    ->label('Approved')
                    ->trueLabel('Approved')
                    ->falseLabel('Not Approved')->boolean()->native(),
                TernaryFilter::make('is_verified')
                    ->label('Verified')
                    ->trueLabel('Verified')
                    ->falseLabel('Not Verified')->boolean()->native(),
                
            ])
            ->recordActions([
                Action::make('approve')->icon('heroicon-o-check')->label('Approve')->color('success')->action(function
                ($record) {
                $record->is_approved = true;
                $record->save();
                })->visible(fn($record) => !$record->is_approved)->requiresConfirmation(),
                EditAction::make(),
                Action::make('reject')->icon('heroicon-o-check')->label('Approve')->color('success')->action(function
                ($record) {
                $record->is_approved = false;
                $record->save();
                })->visible(fn($record) => !$record->is_approved)->requiresConfirmation(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
