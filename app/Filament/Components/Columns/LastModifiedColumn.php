<?php

namespace App\Filament\Components\Columns;

use Filament\Tables\Columns\TextColumn;

class LastModifiedColumn extends TextColumn
{
    public static function make(string $name = 'updated_at'): static
    {
        return parent::make($name)
            ->label('Last modified')
            ->translateLabel()
            ->sortable()
            ->since()
            ->toggleable();
    }
}
