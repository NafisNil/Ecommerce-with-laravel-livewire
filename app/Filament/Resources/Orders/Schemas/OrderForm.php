<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->required(),
                TextInput::make('customer_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('coupon_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric(),
                TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('shipping_cost')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('tax')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total')
                    ->required()
                    ->numeric(),
                TextInput::make('shipping_full_name')
                    ->default(null),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('shipping_address_line1')
                    ->default(null),
                TextInput::make('shipping_address_line2')
                    ->default(null),
                TextInput::make('shipping_city')
                    ->default(null),
                TextInput::make('shipping_state')
                    ->default(null),
                TextInput::make('shipping_postal_code')
                    ->default(null),
                TextInput::make('shipping_country')
                    ->default(null),
                Select::make('payment_method')
                    ->options(['credit_card' => 'Credit card', 'stripe' => 'Stripe', 'bank_transfer' => 'Bank transfer'])
                    ->default('stripe')
                    ->required(),
                Select::make('payment_status')
                    ->options([
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ])
                    ->default('pending')
                    ->required(),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ])
                    ->default('pending')
                    ->required(),
                TextInput::make('transaction_id')
                    ->default(null),
                TextInput::make('tracking_number')
                    ->default(null),
                TextInput::make('customer_notes')
                    ->default(null),
                TextInput::make('admin_notes')
                    ->default(null),
            ]);
    }
}
