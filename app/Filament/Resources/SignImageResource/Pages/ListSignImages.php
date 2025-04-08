<?php

namespace App\Filament\Resources\SignImageResource\Pages;

use App\Filament\Resources\SignImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSignImages extends ListRecords
{
    protected static string $resource = SignImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
