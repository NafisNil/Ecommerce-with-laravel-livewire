<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['images'] = $this->record->images->pluck('image_path')->toArray();

        return $data;
    }

    protected function afterSave(): void
    {
        $images = array_values($this->form->getRawState()['images'] ?? []);

        $this->record->images()->delete();

        foreach ($images as $index => $imagePath) {
            $this->record->images()->create([
                'image_path' => $imagePath,
                'sort_order' => $index,
                'is_primary'  => $index === 0,
            ]);
        }
    }
}
