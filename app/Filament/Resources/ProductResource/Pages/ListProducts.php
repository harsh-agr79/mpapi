<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use App\Models\Category;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;

class ListProducts extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [];
        $categories = Category::all();
        foreach ($categories as $category) {
            $tabs["category_{$category->id}"] = Tab::make($category->name)
                ->query(fn ($query) => $query->where('category_id', $category->id));
        }

        return $tabs;
    }
}
