<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use Filament\Forms\Components\{TextInput, Select, DatePicker, Section, Textarea, Grid};
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Tables\Filters\Filter;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Order Details')->schema([
                    Select::make('customer_id')
                        ->label('Customer')
                        ->relationship('customer', 'name')
                        ->searchable()
                        ->required(),
    
                    Select::make('current_status')
                        ->label('Order Status')
                        ->options([
                            'pending' => 'Pending',
                            'approved' => 'Approved',
                            'packing' => 'Packing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                            'returned' => 'Returned',
                            'refunded' => 'Refunded',
                        ])
                        ->required(),
    
                    DatePicker::make('order_date')->label('Order Date')->required(),
    
                    TextInput::make('total_amount')
                        ->label('Total Amount')
                        ->prefix('₹')
                        ->numeric()
                        ->required()
                        ->readOnly(),
    
                    TextInput::make('discount')
                        ->label('Discount')
                        ->prefix('₹')
                        ->numeric()
                        ->readOnly(),
    
                    TextInput::make('discounted_total')
                        ->label('Discounted Total')
                        ->prefix('₹')
                        ->numeric()
                        ->required()
                        ->readOnly(),
    
                    TextInput::make('net_total')
                        ->label('Net Total')
                        ->prefix('₹')
                        ->numeric()
                        ->required()
                        ->readOnly(),
                ])->columns(2),
    
                Section::make('Billing Address')->schema([
                    TextInput::make('billing_full_name')->label('Full Name')->required(),
                    TextInput::make('billing_email')->label('Email')->email(),
                    TextInput::make('billing_phone_number')->label('Phone')->tel(),
                    TextInput::make('billing_street_address')->label('Street Address')->required(),
                    TextInput::make('billing_city')->label('City')->required(),
                    TextInput::make('billing_state')->label('State')->required(),
                    TextInput::make('billing_postal_code')->label('Postal Code')->required(),
                ])->columns(2),
    
                Section::make('Shipping Address')->schema([
                    TextInput::make('shipping_full_name')->label('Full Name')->required(),
                    TextInput::make('shipping_email')->label('Email')->email(),
                    TextInput::make('shipping_phone_number')->label('Phone')->tel(),
                    TextInput::make('shipping_street_address')->label('Street Address')->required(),
                    TextInput::make('shipping_city')->label('City')->required(),
                    TextInput::make('shipping_state')->label('State')->required(),
                    TextInput::make('shipping_postal_code')->label('Postal Code')->required(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Order ID')->sortable(),
                TextColumn::make('customer.name')->label('Customer')->sortable(),
                TextColumn::make('current_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'info',
                        'processing' => 'info',
                        'packing' => 'secondary',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        'returned' => 'danger',
                        'refunded' => 'dark',
                    })
                    ->sortable(),
                TextColumn::make('order_date')->label('Order Date')->date(),
                TextColumn::make('total_amount')->label('Total Amount')->money('INR')->sortable(),
                TextColumn::make('discounted_total')->label('Discounted Total')->money('INR'),
                TextColumn::make('net_total')->label('Net Total')->money('INR')->sortable(),
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
            RelationManagers\OrderItemRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
