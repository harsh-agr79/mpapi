<?php

namespace App\Filament\Resources\AboutUsResource\Pages;

use App\Filament\Resources\AboutUsResource;
use Filament\Actions;
use App\Models\AboutUsCard;
use App\Models\TeamMember;
use Filament\Resources\Pages\EditRecord;

class EditAboutUs extends EditRecord
{
    protected static string $resource = AboutUsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Handle dynamic cards
        if (isset($data['about_us_cards'])) {
            AboutUsCard::truncate(); // Clear previous cards
            foreach ($data['about_us_cards'] as $card) {
                AboutUsCard::create($card);
            }
        }

        // Handle team members
        if (isset($data['team_members'])) {
            TeamMember::truncate(); // Clear previous team members
            foreach ($data['team_members'] as $member) {
                TeamMember::create($member);
            }
        }

        unset($data['about_us_cards'], $data['team_members']);

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['about_us_cards'] = AboutUsCard::all()->toArray();
        $data['team_members'] = TeamMember::all()->toArray();

        return $data;
    }
}
