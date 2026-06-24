<?php

namespace App\Livewire;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Filament\Resources\Customers\CustomerResource;
use Filament\Tables\Columns\TextColumn;
class LatestOrders extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Order::query())
            ->columns([
                //
                TextColumn::make('order_number')->sortable()->copyable(true)
                    ->searchable(),
                TextColumn::make('customer_name')->searchable()->sortable()->url(fn($record) =>$record->customer ? CustomerResource::getUrl('edit', ['record' => $record->customer->id]) : null)->color('primary'),
                TextColumn::make('total')
                    ->money('USD', true)->weight('bold')
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->badge()->color(fn($state) => match($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'secondary',
                    }),
                TextColumn::make('status')
                    ->badge()->color(fn($state) => match($state) {
                        'pending' => 'warning',
                        'processing' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->heading('Latest Orders')
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
