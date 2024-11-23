<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Facades\DB;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Inventory';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('unique_id')->required()->unique(ignoreRecord: true),
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required()
                    ->reactive(), // Makes the field reactive to changes
            
            Select::make('subcategory_ids')
                ->label('Subcategories')
                ->multiple() // Allow selecting multiple subcategories
                ->required()
                ->options(function (callable $get) {
                    $categoryId = $get('category_id'); // Get the selected category ID
                    if (!$categoryId) {
                        return []; // If no category is selected, return an empty array
                    }

                    // Fetch subcategories dynamically based on the selected category
                    return DB::table('subcategories')
                        ->where('category_id', $categoryId)
                        ->pluck('name', 'id');
                })
                ->reactive(),
                TextInput::make('price')->numeric()->required(),
                TextInput::make('sku'),
                TextInput::make('meta_title'),
                Textarea::make('meta_description'),
                Toggle::make('outofstock'),
                Toggle::make('hidden'),
                RichEditor::make('details'),
                KeyValue::make('specifications'),
                FileUpload::make('image_1')->directory('products/images')->image()->disk('public')
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        return now()->timestamp . '-' . $file->getClientOriginalName();
                    }),
                TextInput::make('image_2_alt'),
                FileUpload::make('image_2')->directory('products/images')->image()->disk('public')
                ->getUploadedFileNameForStorageUsing(function ($file) {
                    return now()->timestamp . '-' . $file->getClientOriginalName();
                }),
                TextInput::make('image_2_alt'),
                FileUpload::make('image_3')->directory('products/images')->image()->disk('public')
                ->getUploadedFileNameForStorageUsing(function ($file) {
                    return now()->timestamp . '-' . $file->getClientOriginalName();
                }),
                TextInput::make('image_3_alt'),
                FileUpload::make('image_4')->directory('products/images')->image()->disk('public')
                ->getUploadedFileNameForStorageUsing(function ($file) {
                    return now()->timestamp . '-' . $file->getClientOriginalName();
                }),
                TextInput::make('image_4_alt'),
                FileUpload::make('image_5')->directory('products/images')->image()->disk('public')
                ->getUploadedFileNameForStorageUsing(function ($file) {
                    return now()->timestamp . '-' . $file->getClientOriginalName();
                }),
                TextInput::make('image_5_alt'),
                Repeater::make('colors')
                ->label('Colors')
                ->schema([
                    ColorPicker::make('color')
                        ->label('Color'),
                ])
                ->minItems(1)
                ->maxItems(10),
                
                Textarea::make('short_description'),
               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('unique_id'),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('price')->sortable(),
                BooleanColumn::make('outofstock')->label('Out of Stock'),
                BooleanColumn::make('hidden'),
                BadgeColumn::make('colors')->formatStateUsing(fn ($state) => implode(', ', $state)),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
