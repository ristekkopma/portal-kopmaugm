<?php

namespace App\Filament\Components\Columns;

use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\TextColumn;

class WhatsappLinkColumn extends TextColumn
{
    public static function make(string $name = 'phone'): static
    {
        return parent::make($name)
            ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
            ->translateLabel()
            ->iconColor('success')
            ->url(function (?string $state) {
                return $state ? "https://wa.me/" . preg_replace('/\D/', '', $state) : null;
            })
            ->openUrlInNewTab(fn(?string $state): bool => filled($state));
    }
}
