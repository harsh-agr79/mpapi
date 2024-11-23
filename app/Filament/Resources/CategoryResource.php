<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;


class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->label('Category Name')
                ->required()
                ->maxLength(255),
                TextInput::make('meta_title')
                    ->label('Meta Title')
                    ->maxLength(255),
                Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(4),
                FileUpload::make('image')
                    ->label('Category Image')
                    ->image()
                    ->directory('categories') // Folder to store images
                    ->maxSize(1024), // Max size in KB
                TextInput::make('imagefiletag')
                    ->label('Image File Tag')
                    ->maxLength(255),
                TextInput::make('alttext')
                    ->label('Image Alt Text')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label('Name')
                ->searchable()
                ->sortable(),
                ImageColumn::make('image')
                    ->label('Image')
                    ->width(50)
                    ->height(50),
                TextColumn::make('meta_title')
                    ->label('Meta Title'),
                TextColumn::make('meta_description')
                    ->label('Meta Description')
                    ->limit(50), // Show a preview
                TextColumn::make('alttext')
                    ->label('Alt Text'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
