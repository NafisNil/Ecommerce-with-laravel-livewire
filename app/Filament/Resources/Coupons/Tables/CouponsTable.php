<?php

namespace App\Filament\Resources\Coupons\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->sortable()->searchable()->copyable(true),
                TextColumn::make('type')->color(
                    fn($state) => $state === 'fixed' ? 'success' : 'warning'
                )
                    ->badge(),
                TextColumn::make('value')->formatStateUsing(fn($state, $record) => $record->type === 'fixed' ? '$' . $state : $state . '%')->sortable(),

                TextColumn::make('min_order_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('max_discount_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('usage_limit')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('usage_count')
                    ->counts('usages')->color('warning')
                    ->sortable(),
                TextColumn::make('usage_limit_per_user')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('start_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_at')
                    ->dateTime()
                    ->sortable()->color(fn($state) => now()->greaterThan($state) ? 'danger' : 'success'),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                //
                SelectFilter::make('type')->options([
                    'fixed' => 'Fixed',
                    'percent' => 'Percent',
                ])->native(false),
                TernaryFilter::make('is_active')
                    ->label('Active')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive')
                    ->native(false)->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
