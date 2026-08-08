<?php

declare(strict_types=1);

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['translations']);

        if (empty($data['author_id'])) {
            $data['author_id'] = Filament::auth()->id();
        }

        if (($data['is_published'] ?? false) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $translations = $this->data['translations'] ?? [];
        
        foreach ($translations as $locale => $translationData) {
            if (blank($translationData['title'] ?? null)) {
                continue;
            }

            $translationData['content'] ??= '';
            $record->setTranslation($locale, $translationData);
        }
    }
}
