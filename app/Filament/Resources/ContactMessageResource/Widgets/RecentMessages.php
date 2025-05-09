<?php

namespace App\Filament\Resources\ContactMessageResource\Widgets;

use App\Filament\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentMessages extends BaseWidget
{
    protected static ?string $heading = 'Recent Contact Messages';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->poll('10s')
            ->defaultSort('created_at', 'desc')
            ->query(
                ContactMessage::query()
            )
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('full_name')->sortable(),
                TextColumn::make('email')->sortable(),
                TextColumn::make('phone_number')->sortable(),
                TextColumn::make('company')->sortable(),
                TextColumn::make('message')->limit(50),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->url(fn(ContactMessage $record): string => ContactMessageResource::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-o-eye')
            ]);
    }
}
