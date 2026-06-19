<?php

declare(strict_types=1);

namespace App\Filament\Resources\BlogCategoryResource\Pages;

use App\Filament\Resources\BlogCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlogCategory extends EditRecord
{
    protected static string $resource = BlogCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->hidden(fn (): bool => $this->record->blogs()->exists()),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->record->load('translations');

        foreach (['en', 'bn'] as $locale) {
            $translation = $this->record->translations->firstWhere('locale', $locale);

            if ($translation) {
                $data['translations'][$locale] = ['name' => $translation->name];
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['translations']);

        return $data;
    }

    protected function afterSave(): void
    {
        foreach ($this->data['translations'] ?? [] as $locale => $translationData) {
            if (blank($translationData['name'] ?? null)) {
                continue;
            }

            $this->record->setTranslation($locale, $translationData);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
