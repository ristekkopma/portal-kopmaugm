<?php

namespace App\Filament\Components\Forms;

use Closure;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class TimestampPlaceholder extends Section
{
    public static function make(string | array | Htmlable | Closure | null $heading = null): static
    {
        $static = parent::make($heading)
            ->hidden(fn (?Model $record) => is_null($record))
            ->schema([
                Placeholder::make('created_at')
                    ->helperText(fn (?Model $record): string => $record->created_at)
                    ->content(fn (?Model $record): string => $record->created_at->diffForHumans()),
                Placeholder::make('updated_at')
                    ->label('Last modified')
                    ->helperText(fn (?Model $record): string => $record->created_at)
                    ->content(fn (?Model $record): string => $record->updated_at->diffForHumans()),
            ]);

        return $static;
    }
}
