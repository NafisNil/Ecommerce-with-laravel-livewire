<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Hash;
class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Information')->columnSpanFull()->columns(2)
                    ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('email_verified_at')
                    ->email()
                    ->default(null),
                TextInput::make('password')
                    ->password()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                DatePicker::make('date_of_birth')->native(false)->displayFormat('Y-m-d')->default(null),
                Select::make('gender')
                    ->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'])
                    ->default(null)->native(false),
                Toggle::make('is_active')
                    ->required(),
                ]),

                Section::make('Password')->columnSpanFull()->columns(2)
                    ->schema([
                TextInput::make('password')
                    ->password()->dehydrateStateUsing(fn ($state) => $state ? Hash::make($state) : null)->dehydrated(fn($state) => filled($state))->required(fn(string $operation) => $operation === 'create')->revealable(),
                TextInput::make('password_confirmation')
                    ->password()->dehydrated(false)
                    ->required(fn(string $operation) => $operation === 'create')->revealable()
                ]),
            ]);
    }
}
