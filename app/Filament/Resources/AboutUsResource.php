<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutUsResource\Pages;
use App\Filament\Resources\AboutUsResource\RelationManagers;
use App\Models\AboutUs;
use App\Models\AboutUsCard;
use App\Models\TeamMember;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AboutUsResource extends Resource
{
    protected static ?string $model = AboutUs::class;

    protected static ?string $navigationLabel = 'About Us';

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationGroup = 'Pages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('About Us Content')
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->label('About Us Content')
                            ->required(),
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(255)
                            ->placeholder('Enter a meta title for SEO'),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Enter a meta description for SEO'),
                    ]),
                Section::make('Dynamic Cards')
                    ->schema([
                        Repeater::make('about_us_cards')
                            ->schema([
                                Forms\Components\FileUpload::make('image')->directory('aboutuscard')->label('Image')->image()->required(),
                                Forms\Components\TextInput::make('title')->label('Card Title')->required(),
                                Forms\Components\Textarea::make('text')->label('Card Text')->required(),
                            ])
                            ->createItemButtonLabel('Add New Card')
                            ->columns(2)
                            ->disableLabel(),
                    ])
                    ->collapsible(),
                Section::make('Team Members')
                    ->schema([
                        Repeater::make('team_members')
                            ->schema([
                                Forms\Components\FileUpload::make('photo')->directory('aboutusteam')->label('Photo')->image()->required(),
                                Forms\Components\TextInput::make('name')->label('Name')->required(),
                                Forms\Components\TextInput::make('designation')->label('Designation')->required(),
                            ])
                            ->createItemButtonLabel('Add New Team Member')
                            ->columns(3)
                            ->disableLabel(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('content')->label('About Us Content')->limit(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->paginated(false)
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

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAboutUs::route('/'),
            // 'create' => Pages\CreateAboutUs::route('/create'),
            'edit' => Pages\EditAboutUs::route('/{record}/edit'),
        ];
    }
}
