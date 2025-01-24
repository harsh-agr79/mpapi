<?php

namespace App\Filament\Resources\ContactUsIconResource\Pages;

use App\Filament\Resources\ContactUsIconResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContactUsIcon extends EditRecord
{
    protected static string $resource = ContactUsIconResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
