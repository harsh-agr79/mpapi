<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogRelationManagerResource\RelationManagers\ActivityLogsRelationManager;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use Filament\Forms\Components\{TextInput, Select, DatePicker, Section, Toggle, Textarea, Grid};
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\ViewAction;
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
use Filament\Tables\Enums\FiltersLayout;
use OrderHistoryLogsRelationManager;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?int $navigationSort = 1;

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

                    Select::make('payment_status')
                        ->label('Payment Status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid By any method',
                            'cod' => 'COD',
                        ])
                        ->required(),

                    DatePicker::make('order_date')->label('Order Date')->required(),

                    TextInput::make('total_amount')
                        ->label('Total Amount')
                        ->prefix('रु')
                        ->numeric()
                        ->required()
                        ->readOnly(),

                    TextInput::make('delivery_charge')
                        ->label('Delivery Charge')
                        ->prefix('रु')
                        ->numeric()
                        ->required()
                        ->readOnly(),

                    TextInput::make('discount')
                        ->label('Discount')
                        ->prefix('रु')
                        ->numeric()
                        ->readOnly(),

                    TextInput::make('discounted_total')
                        ->label('Discounted Total')
                        ->prefix('रु')
                        ->numeric()
                        ->required()
                        ->readOnly(),

                    TextInput::make('coupon_code')
                        ->label('Coupon Applied')

                        ->readOnly(),

                    TextInput::make('coupon_discount')
                        ->label('Coupon Discount')
                        ->prefix('रु')
                        ->numeric()
                        ->required()
                        ->readOnly(),

                    Toggle::make('free_shipping')
                        ->disabled(),

                    TextInput::make('net_total')
                        ->label('Net Total')
                        ->prefix('रु')
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
            ->poll('10s')
            ->query(
                Order::query()
                    ->orderBy('created_at', 'desc') // Order by created_at descending
            )
            ->columns([
                TextColumn::make('id')->label('Order ID')->sortable(),
                TextColumn::make('customer.name')->label('Customer')->sortable(),
                TextColumn::make('current_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
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
                TextColumn::make('total_amount')->label('Total Amount')->money('NPR')->sortable(),
                TextColumn::make('discounted_total')->label('Discounted Total')->money('NPR'),
                TextColumn::make('net_total')->label('Net Total')->money('NPR')->sortable(),
            ])
            ->searchable()
            ->defaultPaginationPageOption(25)
            ->filters([
                Filter::make('created_from')
                    ->form([
                        DatePicker::make('created_from'),
                        // DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            );
                        // ->when(
                        //     $data['created_until'],
                        //     fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        // );
                    }),
                Filter::make('created_until')
                    ->form([
                        // DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            // ->when(
                            //     $data['created_from'],
                            //     fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            // );
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),

            ], layout: FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(3)
            ->actions([
                ViewAction::make()
                    ->modalHeading(fn($record) => 'Order: #' . ucfirst($record->id))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->infolist([
                        InfoSection::make()
                            ->schema([

                                TextEntry::make('id')->label('ORDER ID')
                                    ->getStateUsing(fn($record) => '#' . $record->id),
                                TextEntry::make('order_date')->label('ORDER DATE'),
                                TextEntry::make('billing_full_name')->label('BILLED TO'),
                                TextEntry::make('billing_address')->label('BILLING ADDRESS')
                                    ->getStateUsing(function ($record) {
                                        $parts = array_filter([
                                            $record->billing_street_address,
                                            $record->billing_municipality,
                                            $record->billing_city,
                                            $record->billing_state,
                                            $record->billing_country_region,
                                            $record->billing_postal_code,
                                        ]);

                                        return implode(', ', $parts);
                                    })
                                    ->hidden(fn($record) => empty(array_filter([
                                        $record->billing_street_address,
                                        $record->billing_municipality,
                                        $record->billing_city,
                                        $record->billing_state,
                                        $record->billing_country_region,
                                        $record->billing_postal_code,
                                    ])))
                                    ->extraAttributes(['class' => 'capitalize']),
                                TextEntry::make('shipping_full_name')->label('SHIP TO'),
                                TextEntry::make('shipping_address')->label('SHIPPING ADDRESS')
                                    ->getStateUsing(function ($record) {
                                        $parts = array_filter([
                                            $record->shipping_street_address,
                                            $record->shipping_municipality,
                                            $record->shipping_city,
                                            $record->shipping_state,
                                            $record->shipping_country_region,
                                            $record->shipping_postal_code,
                                        ]);

                                        return implode(', ', $parts);
                                    })
                                    ->hidden(fn($record) => empty(array_filter([
                                        $record->shipping_street_address,
                                        $record->shipping_municipality,
                                        $record->shipping_city,
                                        $record->shipping_state,
                                        $record->shipping_country_region,
                                        $record->shipping_postal_code,
                                    ])))
                                    ->extraAttributes(['class' => 'capitalize']),
                                TextEntry::make('current_status')->label('ORDER STATUS')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'approved' => 'info',
                                        'processing' => 'info',
                                        'packing' => 'secondary',
                                        'shipped' => 'primary',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger',
                                        'returned' => 'danger',
                                        'refunded' => 'dark',
                                    }),
                                TextEntry::make('payment_status')->label('PAYMENT STATUS')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'paid' => 'success',
                                        'cod' => 'info',
                                    }),
                                RepeatableEntry::make('OrderItem')
                                    ->label('Order Items')
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('product.name')->label('Item Name'),
                                        TextEntry::make('quantity')->label('Quantity'),
                                        TextEntry::make('color')->label('Color')
                                            ->visible(fn($record) => filled($record->color)),
                                        TextEntry::make('price')->label('Price')->money('npr'),
                                        TextEntry::make('discounted_price')->label('Discounted Price')->money('npr')
                                            ->money('npr'),
                                        TextEntry::make('line_total')->label('Line Total')->money('npr')
                                            ->getStateUsing(fn($record) => $record->quantity * $record->discounted_price)
                                        ,
                                    ])
                                    ->columns(3),
                                KeyValueEntry::make('amounts')
                                    ->label('AMOUNTS')
                                    ->state(function ($record) {
                                        return [
                                            'SUB-TOTAL' => 'NPR ' . number_format($record->total_amount, 2),
                                            'DISCOUNT' => 'NPR ' . number_format($record->discount, 2),
                                            'TOTAL' => 'NPR ' . number_format($record->discounted_total, 2),
                                            'COUPON CODE' => $record->coupon_code,
                                            'COUPON DISCOUNT' => 'NPR ' . $record->coupon_discount,
                                            'DELIVERY FEE' => 'NPR ' . number_format($record->delivery_charge, 2),
                                            'NET TOTAL' => 'NPR ' . number_format($record->net_total, 2),
                                        ];
                                    })
                                    ->columnSpanFull()
                                ,
                                TextEntry::make('created_at')->label('CREATED_AT'),
                                TextEntry::make('updated_at')->label('UPDATED_AT'),
                                // TextEntry::make('deleted_at')->label('DELETED_AT')->visible(fn($record) => filled($record->deleted_at)),

                            ])
                            ->columns(2),
                    ]),
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
            RelationManagers\OrderItemRelationManager::class,
            RelationManagers\StatusHistoryRelationManager::class,
            RelationManagers\PaymentsRelationManager::class,
            OrderHistoryLogsRelationManager::class
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

    public static function getNavigationBadge(): ?string
    {
        $modelClass = static::$model;

        return (string) $modelClass::where('current_status', 'pending')->count();
    }
}
