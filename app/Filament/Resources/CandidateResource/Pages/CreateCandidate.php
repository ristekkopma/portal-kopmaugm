<?php

namespace App\Filament\Resources\CandidateResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CandidateResource;
use App\Models\Member;
use App\Models\UserProfile;

class CreateCandidate extends CreateRecord
{
    protected static string $resource = CandidateResource::class;


    protected function handleRecordCreation(array $data): Model
    {
        UserProfile::create([
            'user_id' => $data['user_id'],
        ]);

        return parent::handleRecordCreation($data);
    }
}
