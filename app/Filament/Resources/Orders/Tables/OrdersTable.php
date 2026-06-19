<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')->sortable()->copyable(true)
                    ->searchable(),
                TextColumn::make('customer_name')->searchable()->sortable()->url(fn($record) =>$record->customer ? CustomerResource::getUrl('edit', ['record' => $record->customer->id]) : null)->color('primary'),

                TextColumn::make('coupon_id')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('discount')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('total')
                    ->money('USD', true)->weight('bold')
                    ->sortable(),
                TextColumn::make('shipping_full_name')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),


                TextColumn::make('payment_status')
                    ->badge(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('item_count')->counts('items')->label('Items')->color('info')
                    ->badge(),

                TextColumn::make('tracking_number')
                    ->searchable()->toggleable()->copyable(true),
                TextColumn::make('customer_notes')
                    ->searchable(),
                TextColumn::make('admin_notes')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])->multiple(),
                TrashedFilter::make(),
                SelectFilter::make('payment_status')->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->native(false)
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make()->url(fn($record) => route('orders.show', $record)),
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
