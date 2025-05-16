<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SearchResource\Pages;
use App\Filament\Resources\SearchResource\RelationManagers;
use App\Models\Search;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SearchResource extends Resource {
    protected static ?string $model = Search::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    public static function form( Form $form ): Form {
        return $form
        ->schema( [
            //
        ] );
    }

    public static function table( Table $table ): Table {
        return $table
         ->modifyQueryUsing(fn (Builder $query) => $query->groupby('term'))
        ->columns( [
            TextColumn::make( 'term' )
            ->label( 'Search Term' )
            ->sortable()
            ->searchable(),
            TextColumn::make('search_count'),
        ])
        ->poll('1s')
        ->searchable()
        ->filters( [
            //
        ] )
        ->actions( [
            // Tables\Actions\EditAction::make(),
        ] )
        ->bulkActions( [
            Tables\Actions\BulkActionGroup::make( [
                // Tables\Actions\DeleteBulkAction::make(),
            ] ),
        ] );
    }

    public static function canCreate(): bool {
        return false;
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListSearches::route( '/' ),
            // 'create' => Pages\CreateSearch::route( '/create' ),
            // 'edit' => Pages\EditSearch::route( '/{record}/edit' ),
        ];
    }
}
