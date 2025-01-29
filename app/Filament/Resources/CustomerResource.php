<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;

class CustomerResource extends Resource {
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form( Form $form ): Form {
        return $form
        ->schema( [
            TextInput::make( 'name' )
            ->required()
            ->maxLength( 255 ),
            TextInput::make( 'email' )
            ->email()
            ->unique( ignoreRecord: true )
            ->required(),
            TextInput::make( 'phone_no' )
            ->unique( ignoreRecord: true )
            ->required()
            ->length( 10 ),

            TextInput::make( 'password' )
            ->password()
            ->maxLength( 255 )
            ->required(),
            DatePicker::make( 'email_verified_at' ),

        ] );
    }

    public static function table( Table $table ): Table {
        return $table
        ->columns( [
            TextColumn::make('name')->sortable()->searchable(),
            TextColumn::make('email')->sortable()->searchable(),
            TextColumn::make('phone_no')->sortable()->searchable(),
            TextColumn::make('email_verified_at')->label('Email Verified'),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ] )
        ->filters( [
            //
        ] )
        ->actions( [
            Tables\Actions\EditAction::make(),
        ] )
        ->bulkActions( [
            Tables\Actions\BulkActionGroup::make( [
                Tables\Actions\DeleteBulkAction::make(),
            ] ),
        ] );
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListCustomers::route( '/' ),
            'create' => Pages\CreateCustomer::route( '/create' ),
            'edit' => Pages\EditCustomer::route( '/{record}/edit' ),
        ];
    }
}
