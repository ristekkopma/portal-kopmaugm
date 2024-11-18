<?php

namespace App\Filament\Resources\CandidateResource\Pages;

use App\Enums\RecruitmentStatus;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CandidateResource;

class ListCandidates extends ListRecords
{
    protected static string $resource = CandidateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = ['all' => Tab::make('All')->badge($this->getModel()::query()->candidate()->count())
            ->modifyQueryUsing(function ($query) {
                return $query->candidate();
            })];

        foreach (RecruitmentStatus::cases() as $status) {
            $name = $status->getLabel();
            $slug = str($name)->slug()->toString();

            $tabs[$slug] = Tab::make($name)
                ->badge($this->getModel()::query()->candidate()->where('recruitment_status', $status)->count())
                ->badgeColor($status->getColor())
                ->modifyQueryUsing(function ($query) use ($status) {
                    return $query->candidate()->where('recruitment_status', $status);
                });
        }

        return $tabs;
    }
}
