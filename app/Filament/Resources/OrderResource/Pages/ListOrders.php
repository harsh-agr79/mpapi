<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;

class ListOrders extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'pending' => Tab::make()->query(fn ($query) => $query->where('current_status', 'pending')),
            'approved' => Tab::make()->query(fn ($query) => $query->where('current_status', 'approved')),
            'packing' => Tab::make()->query(fn ($query) => $query->where('current_status', 'packing')),
            'shipped' => Tab::make()->query(fn ($query) => $query->where('current_status', 'shipped')),
            'delivered' => Tab::make()->query(fn ($query) => $query->where('current_status', 'delivered')),
            'cancelled' => Tab::make()->query(fn ($query) => $query->where('current_status', 'cancelled')),
            'returned' => Tab::make()->query(fn ($query) => $query->where('current_status', 'returned')),
            'refunded' => Tab::make()->query(fn ($query) => $query->where('current_status', 'refunded')),
        ];
    }
}
