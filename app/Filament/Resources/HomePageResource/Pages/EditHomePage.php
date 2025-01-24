<?php

namespace App\Filament\Resources\HomePageResource\Pages;

use App\Filament\Resources\HomePageResource;
use Filament\Actions;
use App\Models\HomePageCover;
use App\Models\HomePageSlider;
use App\Models\HomePageSupport;
use App\Models\HomePageImageBlock;
use Filament\Resources\Pages\EditRecord;

class EditHomePage extends EditRecord
{
    protected static string $resource = HomePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Handle dynamic cards
        if (isset($data['home_page_covers'])) {
            HomePageCover::truncate(); // Clear previous cards
            foreach ($data['home_page_covers'] as $card) {
                HomePageCover::create($card);
            }
        }

        if (isset($data['home_page_sliders'])) {
            HomePageSlider::truncate(); // Clear previous cards
            foreach ($data['home_page_sliders'] as $card) {
                HomePageSlider::create($card);
            }
        }

        if (isset($data['home_page_supports'])) {
            HomePageSupport::truncate(); // Clear previous cards
            foreach ($data['home_page_supports'] as $card) {
                HomePageSupport::create($card);
            }
        }


        if (isset($data['home_page_image_blocks'])) {
            HomePageImageBlock::truncate(); // Clear previous team members
            foreach ($data['home_page_image_blocks'] as $member) {
                HomePageImageBlock::create($member);
            }
        }

        unset($data['home_page_covers'], $data['home_page_sliders'], $data['home_page_supports'], $data['home_page_image_blocks']);

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['home_page_covers'] = HomePageCover::all()->toArray();
        $data['home_page_sliders'] = HomePageSlider::all()->toArray();
        $data['home_page_supports'] = HomePageSupport::all()->toArray();
        $data['home_page_image_blocks'] = HomePageImageBlock::all()->toArray();

        return $data;
    }
}
