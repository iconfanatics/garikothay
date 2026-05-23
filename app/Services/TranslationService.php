<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class TranslationService
{
    public function get(Model $model, string $field, ?string $locale = null): ?string
    {
        $locale = $locale ?? App::getLocale();

        $translation = $model->translations?->firstWhere('locale', $locale)
            ?? $model->translations?->firstWhere('locale', config('app.fallback_locale', 'en'));

        return $translation?->{$field};
    }

    public function set(Model $model, string $locale, array $data): void
    {
        $model->translations()->updateOrCreate(
            ['locale' => $locale],
            $data
        );
    }

    public function getAvailableLocales(): array
    {
        return ['bn' => 'বাংলা', 'en' => 'English'];
    }
}
