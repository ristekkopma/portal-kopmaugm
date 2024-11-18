<?php

namespace App\Filament\Components\Columns;

use Filament\Tables\Columns\TextColumn;

class IDColumn extends TextColumn
{
    public static function make(string $name = 'id'): static
    {
        return parent::make($name)
            ->label(fn () => strtoupper($name))
            ->sortable()
            ->alignCenter()
            ->grow(false)
            ->width(1)
            ->searchable()
            ->copyable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
