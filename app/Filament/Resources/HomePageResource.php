<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogRelationManagerResource\RelationManagers\ActivityLogsRelationManager;
use App\Filament\Resources\HomePageResource\Pages;
use App\Filament\Resources\HomePageResource\RelationManagers;
use App\Models\HomePage;
use App\Models\HomePageCover;
use App\Models\HomePageSlider;
use App\Models\HomePageSupport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HomePageResource extends Resource
{
    protected static ?string $model = HomePage::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Home Page';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationGroup = 'Pages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Home Page Meta Tags')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(255)
                            ->placeholder('Enter a meta title for SEO'),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Enter a meta description for SEO'),
                    ])
                    ->collapsible(),
    
                // Wrap both repeaters inside a Grid
                Forms\Components\Grid::make(12) // 12-column grid
                    ->schema([
                        Section::make('Home Page Covers')
                            ->schema([
                                Repeater::make('home_page_covers')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->image()
                                            ->directory('covers')
                                            ->required(),
                                        Forms\Components\TextInput::make('main_text')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('sub_text')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('button_text')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('button_link')
                                            ->url()
                                            ->maxLength(255),
                                    ])
                                    ->disableLabel(),
                            ])
                            ->collapsible()
                            ->columnSpan(6), // Half width
    
                        Section::make('Home Page Sliders')
                            ->schema([
                                Repeater::make('home_page_sliders')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->image()
                                            ->directory('sliders')
                                            ->required(),
                                        Forms\Components\TextInput::make('main_text')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('sub_text')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('button_text')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('button_link')
                                            ->url()
                                            ->maxLength(255),
                                    ])
                                    ->disableLabel(),
                            ])
                            ->collapsible()
                            ->columnSpan(6), // Half width
                        Section::make('Home Page Support')
                            ->schema([
                                Repeater::make('home_page_supports')
                                    ->schema([
                                        Forms\Components\TextInput::make('main_text')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('sub_text')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('button_link')
                                            ->url()
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->disableItemCreation() // Disable adding new items
                                    ->disableItemDeletion() // Disable deleting items
                                    ->reorderable()
                                    ->disableLabel(),
                            ])
                            ->collapsible()
                           
                            ->columnSpan(6), // Half width
                            Section::make('Image Blocks')
                            ->schema([
                                Repeater::make('home_page_image_blocks')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->directory('homepageimageblock')
                                            ->label('Image')
                                            ->image()
                                            ->required(),
                                        Forms\Components\TextInput::make('alt')
                                            ->label('Alt Text'),
                                    ])
                                    ->createItemButtonLabel('Add New Image Block')
                                    ->columns(2)
                                    ->disableLabel(),
                            ])
                            ->collapsible()
                            ->columnSpan(6),
                    ]),
            ]);
    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('meta_title')->label('Home Page')->limit(50),
            ])
            ->filters([
                //
            ])
            ->paginated(false)
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

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomePages::route('/'),
            'create' => Pages\CreateHomePage::route('/create'),
            'edit' => Pages\EditHomePage::route('/{record}/edit'),
        ];
    }
}
