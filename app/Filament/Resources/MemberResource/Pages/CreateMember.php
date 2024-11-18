<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Enums\RecruitmentStatus;
use App\Filament\Resources\MemberResource;
use App\Models\UserProfile;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        UserProfile::create([
            'user_id' => $data['user_id'],
        ]);
        $data['recruitment_status'] = RecruitmentStatus::Approved;
        $data['joined_at'] = now();

        return parent::handleRecordCreation($data);
    }
}
