<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use App\Models\Product;
use App\Models\Category;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Coupons';
    protected static ?string $pluralLabel = 'Coupons';
    protected static ?string $modelLabel = 'Coupon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Coupon Code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),
                Forms\Components\Select::make('type')
                    ->label('Discount Type')
                    ->options([
                        'fixed' => 'Fixed Amount',
                        'percentage' => 'Percentage',
                        'free_shipping' => 'Free Shipping',
                    ])
                    ->required()
                    ->reactive() // <-- listen to changes
                    ->afterStateUpdated(function (callable $set, $state) {
                        // if ($state === 'free_shipping') {
                            $set('discount_amount', 0); // Set to 0 if free shipping selected
                        // }
                    }),
                
                    Forms\Components\TextInput::make('discount_amount')
                    ->label('Discount Value')
                    ->required()
                    ->numeric()
                    ->maxValue(fn (Get $get) => $get('type') === 'percentage' ? 100 : null)
                    ->rules(fn (Get $get) => match ($get('type')) {
                        'fixed' => ['gt:0'],
                        'percentage' => ['gt:0', 'max:100'],
                        'free_shipping' => [],
                        default => [],
                    })
                    ->readonly(fn (Get $get) => $get('type') === 'free_shipping')
                    ->default(0),
                Forms\Components\TextInput::make('minimum_order_amount')
                    ->label('Minimum Order Amount')
                    ->numeric()
                    ->nullable()
                    ->default(0)
                    ->rules('nullable', 'gte:0'),
                Forms\Components\DateTimePicker::make('start_date')
                    ->label('Start Date')
                    ->required()
                    ->rules('after_or_equal:today'),
                Forms\Components\DateTimePicker::make('end_date')
                    ->label('End Date')
                    ->required()
                    ->rules('after:start_date'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->maxLength(500)
                    ->nullable(), 
                Forms\Components\Select::make('applies_to_products')
                    ->label('Products To Apply to')
                    ->options(Product::all()->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->multiple(),
                Forms\Components\Select::make('applies_to_categories')
                    ->label('Categories To Apply To')
                    ->options(Category::all()->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->multiple()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                ->label('Code')
                ->searchable(),
            Tables\Columns\BadgeColumn::make('type')
                ->label('Type')
                ->color(fn (string $state): string => match ($state) {
                    'fixed'=>'success',
                    'percentage' => 'info',
                    'free_shipping' => 'danger',
                }),

            Tables\Columns\TextColumn::make('discount_amount')
                ->label('Discount')
                ->formatStateUsing(fn (string $state, Coupon $record) => 
                    $record->type === 'fixed' ? '₹' . $state : $state . '%'
            ),
            Tables\Columns\BooleanColumn::make('is_active')
                ->label('Active'),

            Tables\Columns\IconColumn::make('created_at')
                ->label('Time Validity')
                // ->badge()
                ->icon(function (String $state, Coupon $record): string{
                    $currentDate = now();

                    // Check if the current date is within the start and end date
                    if ($currentDate->greaterThanOrEqualTo($record->start_date) && $currentDate->lessThanOrEqualTo($record->end_date)) {
                        return 'heroicon-o-check-circle';
                    }
            
                    return 'heroicon-o-x-circle';
                })
                ->color(function (String $state, Coupon $record): string{
                    $currentDate = now();

                    // Check if the current date is within the start and end date
                    if ($currentDate->greaterThanOrEqualTo($record->start_date) && $currentDate->lessThanOrEqualTo($record->end_date)) {
                        return 'success';
                    }
            
                    return 'danger';
                }),

            Tables\Columns\TextColumn::make('start_date')
                ->label('Starts')
                ->dateTime(),
            Tables\Columns\TextColumn::make('end_date')
                ->label('Ends')
                ->dateTime(),
            ])
            ->filters([
                // Tables\Filters\TrashedFilter::make(),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
