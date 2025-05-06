<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogRelationManagerResource\RelationManagers\ActivityLogsRelationManager;
use App\Filament\Resources\ContactUsIconResource\Pages;
use App\Filament\Resources\ContactUsIconResource\RelationManagers;
use App\Models\ContactUsIcon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactUsIconResource extends Resource
{
    protected static ?string $model = ContactUsIcon::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationLabel = 'Social Links';

    protected static ?int $navigationSort = 13;
    protected static ?string $navigationGroup = 'Pages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('icon')
                ->image() // Restrict to image files
                ->directory('contact_us_icons') // Store in this subdirectory
                ->required()
                ->label('Icon'),

            Forms\Components\TextInput::make('url')
                ->required()
                ->url()
                ->maxLength(255)
                ->label('URL'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon') // Show the uploaded icon as an image
                ->label('Icon'),
            Tables\Columns\TextColumn::make('url')->label('URL'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ActivityLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactUsIcons::route('/'),
            'create' => Pages\CreateContactUsIcon::route('/create'),
            'edit' => Pages\EditContactUsIcon::route('/{record}/edit'),
        ];
    }
}
