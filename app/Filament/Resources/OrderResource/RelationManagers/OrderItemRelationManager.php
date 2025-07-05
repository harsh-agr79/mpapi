<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\{TextInput, Select, Hidden};
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use App\Models\OrderItem;
use Filament\Actions\StaticAction;
use Filament\Tables\Actions\Action;

class OrderItemRelationManager extends RelationManager
{
    protected static string $relationship = 'OrderItem';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $this->updatePrices($state, $set)),

                TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->default(1)
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                        $this->calculateDiscountedTotal($set, $get)
                    ),

                TextInput::make('price')
                    ->label('Price')
                    ->prefix('रु')
                    ->numeric()
                    ->readOnly(), // Prevent manual input

                TextInput::make('discounted_price')
                    ->label('Discounted Price')
                    ->prefix('रु')
                    ->numeric()
                    ->readOnly(), // Prevent manual input

                Hidden::make('customer_id')
                    ->default(fn ($livewire) => $livewire->ownerRecord->customer_id),
    
                Hidden::make('order_id')
                    ->default(fn ($livewire) => $livewire->ownerRecord->id),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product.name')
            ->paginated(false) // ✅ Disable pagination
            ->columns([
                TextColumn::make('product.name')->label('Product'),
                TextColumn::make('color')->label('Color'),
                TextColumn::make('quantity')->label('Quantity'),
                TextColumn::make('price')->label('Price')->money('NPR'),
                TextColumn::make('discounted_price')->label('Discounted Price')->money('NPR'),
                TextColumn::make('total_price')
                ->label('Total Price')
                ->money('NPR')
                ->state(function (OrderItem $record): float {
                    $record->total_price = $record->price * $record->quantity;
                    return $record->price * $record->quantity;
                }),
                TextColumn::make('total_discounted_price')
                ->money('NPR')
                ->label('Total Discounted Price')
                ->state(function (OrderItem $record): float {
                    return $record->discounted_price * $record->quantity;
                }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Action::make('net_total_head')
                    ->label(fn ($livewire) => 'Net Total: ' . number_format(
                        $livewire->ownerRecord->net_total
                    ))
                    ->disabled()
                    ->color('gray'),
                Action::make('coupon_discount_head')
                    ->label(fn ($livewire) => 'Coupon: ' . number_format(
                        $livewire->ownerRecord->coupon_discount
                    ))
                    ->disabled()
                    ->color('gray'),
                Action::make('total_discounted_price_sum')
                    ->label(fn ($livewire) => 'Discounted Price: ' . number_format(
                        $livewire->getTableRecords()->sum(fn ($record) => $record->discounted_price * $record->quantity)
                    ))
                    ->disabled()
                    ->color('gray'),
                Action::make('total_price_sum')
                    ->label(fn ($livewire) => 'Total Price: ' . number_format(
                        $livewire->getTableRecords()->sum(fn ($record) => $record->price * $record->quantity)
                    ))
                    ->disabled()
                    ->color('gray'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    private function updatePrices($productId, callable $set)
    {
        if (!$productId) return;

        $product = \App\Models\Product::find($productId);
        if ($product) {
            $set('price', $product->price);
            $set('discounted_price', $product->discounted_price > 0 ? $product->discounted_price : $product->price);
            $set('discount', $product->price - $product->discounted_price);
        }
    }

    private function calculateDiscountedTotal(callable $set, callable $get)
    {
        // $quantity = $get('quantity') ?? 1;
        // $price = $get('price') ?? 0;
        // $discount = $get('discount') ?? 0;
        
        // $discountedPrice = ($price - $discount) * $quantity;

        $set('discounted_price', $get('discounted_price'));
    }

    private function addOrderDetails(array $data): array
    {
        $order = $this->getOwnerRecord(); // Get the parent order

        $data['order_id'] = $order->id;
        $data['customer_id'] = $order->customer_id;

        return $data;
    }
}
