<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();
        $record->load(['translations', 'images']);

        $data['translations'] = [];
        $data['image_paths'] = $record->images
            ->sortBy('sort_order')
            ->pluck('path')
            ->values()
            ->all();

        foreach (['en', 'bn'] as $locale) {
            $translation = $record->translations->firstWhere('locale', $locale);
            if ($translation) {
                $data['translations'][$locale] = $translation->toArray();
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['translations'], $data['image_paths']);
        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();
        $translations = $this->data['translations'] ?? [];
        
        foreach ($translations as $locale => $translationData) {
            $record->setTranslation($locale, $translationData);
        }

        $formState = $this->form->getRawState();
        $record->syncImages($formState['image_paths'] ?? []);
    }
}
