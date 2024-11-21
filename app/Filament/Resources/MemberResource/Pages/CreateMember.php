<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Enums\RecruitmentStatus;
use App\Enums\UserRole;
use App\Filament\Resources\MemberResource;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Wallet;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $user = User::find($data['user_id']);
        $user->update([
            'role' => UserRole::Member,
        ]);

        UserProfile::create([
            'user_id' => $data['user_id'],
        ]);
        $data['recruitment_status'] = RecruitmentStatus::Approved;
        $data['joined_at'] = now();

        if ($user->doesntHave('wallet')) {
            Wallet::create([
                'user_id' => $data['user_id'],
            ]);
        }

        return parent::handleRecordCreation($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
