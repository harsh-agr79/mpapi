<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogRelationManagerResource\RelationManagers\ActivityLogsRelationManager;
use App\Filament\Resources\SignImageResource\Pages;
use App\Filament\Resources\SignImageResource\RelationManagers;
use App\Models\SignImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class SignImageResource extends Resource
{
    protected static ?string $model = SignImage::class;

    protected static ?string $navigationLabel = 'Sign-In/Up Image';

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationGroup = 'Pages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('path')->directory('sign/images')->image()->disk('public')
                ->getUploadedFileNameForStorageUsing(function ($file) {
                    return now()->timestamp . '-' . $file->getClientOriginalName();
                }),
                TextInput::make('white_text'),
                TextInput::make('yellow_text')

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('path')
                ->label('Main Image'),
                TextColumn::make('white_text'),
                TextColumn::make('yellow_text')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function canCreate(): bool
    {
        return false;
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
            'index' => Pages\ListSignImages::route('/'),
            'create' => Pages\CreateSignImage::route('/create'),
            'edit' => Pages\EditSignImage::route('/{record}/edit'),
        ];
    }
}
