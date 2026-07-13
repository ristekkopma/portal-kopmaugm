<?php

namespace App\Filament\Portal\Resources\EventResource\Pages;

use App\Filament\Portal\Resources\EventResource;
use App\Models\EventFollower;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\DB;

class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('follow')
                ->label(fn (): string => $this->isFollowing() ? '✓ Ingin Mengikuti' : 'Ingin Mengikuti')
                ->icon(fn (): string => $this->isFollowing() ? 'heroicon-s-star' : 'heroicon-o-star')
                ->color(fn (): string => $this->isFollowing() ? 'success' : 'gray')
                ->outlined(fn (): bool => ! $this->isFollowing())
                ->action(function (): void {
                    DB::transaction(function (): void {
                        $follower = $this->getRecord()->followers()
                            ->withTrashed()
                            ->where('user_id', auth()->id())
                            ->first();

                        if (! $follower) {
                            $this->getRecord()->followers()->create([
                                'user_id' => auth()->id(),
                                'status' => 'interested',
                            ]);
                        } elseif ($follower->trashed()) {
                            $follower->restore();
                            $follower->update(['status' => 'interested']);
                        } else {
                            $follower->update([
                                'status' => $follower->status === 'cancelled' ? 'interested' : 'cancelled',
                            ]);
                        }
                    });

                    $this->getRecord()->refresh();
                    Notification::make()->title('Status keikutsertaan diperbarui')->success()->send();
                }),

            Actions\Action::make('register')
                ->label('Daftar Event')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('primary')
                ->url(fn (): ?string => $this->getRecord()->safe_registration_url)
                ->openUrlInNewTab()
                ->visible(fn (): bool => filled($this->getRecord()->safe_registration_url)),

            Actions\Action::make('review')
                ->label(fn (): string => $this->userReview() ? 'Edit Review' : 'Beri Review')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->visible(fn (): bool => $this->canReview())
                ->fillForm(fn (): array => [
                    'rating' => $this->userReview()?->rating,
                    'review' => $this->userReview()?->review,
                ])
                ->form([
                    Forms\Components\ToggleButtons::make('rating')
                        ->label('Rating')
                        ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'])
                        ->icons([
                            1 => 'heroicon-s-star', 2 => 'heroicon-s-star', 3 => 'heroicon-s-star',
                            4 => 'heroicon-s-star', 5 => 'heroicon-s-star',
                        ])
                        ->inline()
                        ->required(),
                    Forms\Components\Textarea::make('review')
                        ->label('Ulasan')
                        ->required()
                        ->rules(['not_regex:/^\s*$/'])
                        ->minLength(2)
                        ->maxLength(2000),
                ])
                ->action(function (array $data): void {
                    $data['review'] = trim($data['review']);
                    abort_if($data['review'] === '', 422);

                    $this->getRecord()->reviews()->updateOrCreate(
                        ['user_id' => auth()->id()],
                        $data,
                    );

                    $this->getRecord()->refresh()->load('reviews.user');
                    Notification::make()->title('Review berhasil disimpan')->success()->send();
                }),

            Actions\Action::make('share')
                ->label('Bagikan')
                ->icon('heroicon-o-share')
                ->action(fn () => null)
                ->extraAttributes(fn (): array => [
                    'x-on:click' => 'navigator.clipboard.writeText(' . json_encode(route('events.show', $this->getRecord())) . ')',
                ]),
        ];
    }

    private function isFollowing(): bool
    {
        return $this->getRecord()->activeFollowers()->where('user_id', auth()->id())->exists();
    }

    private function userReview()
    {
        return $this->getRecord()->reviews()->where('user_id', auth()->id())->first();
    }

    private function canReview(): bool
    {
        return $this->getRecord()->schedule_end?->isPast()
            || $this->getRecord()->status === 'completed';
    }
}
