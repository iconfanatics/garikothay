<?php

declare(strict_types=1);

namespace App\Filament\Resources\BannerResource\Pages;

use App\Filament\Resources\BannerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBanner extends CreateRecord
{
    protected static string $resource = BannerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['translations']);
        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $translations = $this->data['translations'] ?? [];

        foreach ($translations as $locale => $translationData) {
            $record->setTranslation($locale, $translationData);
        }
    }
}
