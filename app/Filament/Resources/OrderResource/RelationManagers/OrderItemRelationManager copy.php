<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\{TextInput, Select};
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\Summarizers\Sum;

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
                ->prefix('₹')
                ->numeric()
                ->disabled(), // Prevent manual input

            TextInput::make('discounted_price')
                ->label('Discounted Price')
                ->prefix('₹')
                ->numeric()
                ->disabled(), // Prevent manual input

            TextInput::make('discount')
                ->label('Discount')
                ->prefix('₹')
                ->numeric()
                ->disabled(), // Prevent manual input
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
                TextColumn::make('price')->label('Price')->money('INR')->summarize(Sum::make()),
                TextColumn::make('discounted_price')->label('Discounted Price')->money('INR')->summarize(Sum::make()),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    private function updatePrices($productId, callable $set)
    {
        if (!$productId) return;

        $product = \App\Models\Product::find($productId);
        if ($product) {
            $set('price', $product->price);
            $set('discount', $product->discount);
            $set('discounted_price', $product->price - $product->discount);
        }
    }

    private function calculateDiscountedTotal(callable $set, callable $get)
    {
        $quantity = $get('quantity') ?? 1;
        $price = $get('price') ?? 0;
        $discount = $get('discount') ?? 0;
        
        $discountedPrice = ($price - $discount) * $quantity;

        $set('discounted_price', $discountedPrice);
    }
}
