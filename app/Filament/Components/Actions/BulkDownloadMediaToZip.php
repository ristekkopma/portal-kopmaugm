<?php

namespace App\Filament\Components\Actions;

use Closure;
use Illuminate\Support\Collection;
use Filament\Tables\Actions\BulkAction;
use Spatie\MediaLibrary\Support\MediaStream;

class BulkDownloadMediaToZip extends BulkAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->action(
            function (?Collection $records): MediaStream {
                return MediaStream::create($this->fileName())->addMedia($this->mediaCollectionName());
            }
        );
    }
    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->label(ucwords($name))
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function (?Collection $records): MediaStream {
                $mediaItems = new Collection();

                foreach ($records as $record) {
                    $folderName = str($record->user->name)->slug();
                    $recordMediaItems = $record->getMedia($this->mediaCollectionName());

                    foreach ($recordMediaItems as $media) {
                        $filePath = "{$folderName}/{$media->file_name}";
                        $mediaItems->push(
                            $media->setCustomProperty('zip_filename_prefix', $filePath)
                        );
                    }
                }

                return MediaStream::create($this->fileName())->addMedia($mediaItems);
            });
    }

    public static function fileName(Closure|string $getFilename = 'default.zip'): string
    {
        return $getFilename;
    }

    public static function mediaCollectionName(Closure|string $collectionName = 'avatar'): string
    {
        return $collectionName;
    }
}
