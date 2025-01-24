<?php

namespace App\Filament\Resources\ContactUsDataResource\Pages;

use App\Filament\Resources\ContactUsDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContactUsData extends EditRecord
{
    protected static string $resource = ContactUsDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
