<?php

namespace App\Filament\Components\Forms\Address;

use App\Enums\AddressType;
use App\Models\CRM\Client;
use App\Models\Support\Address;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class BillingForm extends Section
{
    public static function make(string | array | Htmlable | Closure | null $heading = 'Billing Address'): static
    {
        $static = parent::make($heading)
            ->relationship('billingAddress')
            ->columns(3)
            ->schema([
                Forms\Components\Hidden::make('type')
                    ->default(AddressType::BILLING),
                Forms\Components\TextInput::make('street')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('city'),
                Forms\Components\TextInput::make('zip')
                    ->label('Postal code'),
                Forms\Components\TextInput::make('state'),
                Forms\Components\TextInput::make('country'),
            ]);

        return $static;
    }
}
