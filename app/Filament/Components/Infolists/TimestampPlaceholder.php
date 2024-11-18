<?php

namespace App\Filament\Components\Infolists;

use Closure;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class TimestampPlaceholder extends Section
{
    public static function make(string | array | Htmlable | Closure | null $heading = null): static
    {
        $static = parent::make($heading)
            ->hidden(fn (?Model $record) => is_null($record))
            ->schema([
                TextEntry::make('created_at')
                    ->formatStateUsing(fn (?Model $record): string => $record->created_at->diffForHumans()),
                TextEntry::make('updated_at')
                    ->label('Last modified')
                    ->formatStateUsing(fn (?Model $record): string => $record->created_at->diffForHumans()),
            ]);

        return $static;
    }
}
