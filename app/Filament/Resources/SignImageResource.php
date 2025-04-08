<?php

namespace App\Filament\Resources;

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

class SignImageResource extends Resource
{
    protected static ?string $model = SignImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('path')->directory('sign/images')->image()->disk('public')
                ->getUploadedFileNameForStorageUsing(function ($file) {
                    return now()->timestamp . '-' . $file->getClientOriginalName();
                }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('path')
                ->label('Main Image')
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

    public static function getRelations(): array
    {
        return [
            //
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
