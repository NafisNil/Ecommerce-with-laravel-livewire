<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function afterCreate(): void
    {
        $images = array_values($this->form->getRawState()['images'] ?? []);

        foreach ($images as $index => $imagePath) {
            $this->record->images()->create([
                'image_path' => $imagePath,
                'sort_order' => $index,
                'is_primary'  => $index === 0,
            ]);
        }
    }
}
