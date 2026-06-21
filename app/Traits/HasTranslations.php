<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;

trait HasTranslations
{
    protected array $pendingTranslations = [];

    public static function bootHasTranslations()
    {
        static::saved(function ($model) {
            if (!empty($model->pendingTranslations)) {
                foreach ($model->pendingTranslations as $locale => $data) {
                    $model->setTranslation($locale, $data);
                }
                $model->pendingTranslations = [];
            }
        });
    }

    public function setTranslationsAttribute(array $value)
    {
        $this->pendingTranslations = $value;
    }

    public function getTranslationsArrayAttribute(): array
    {
        $result = [];
        // Use relation if loaded, otherwise query
        $translations = $this->relationLoaded('translations') ? $this->translations : $this->translations()->get();
        foreach ($translations as $translation) {
            $result[$translation->locale] = $translation->toArray();
        }
        return $result;
    }

    public function translate(string $locale = null): ?object
    {
        $locale = $locale ?? App::getLocale();
        $relation = $this->translations ?? collect();

        return $relation->firstWhere('locale', $locale)
            ?? $relation->firstWhere('locale', config('app.fallback_locale', 'en'));
    }

    public function getTranslation(string $field, string $locale = null): ?string
    {
        return $this->translate($locale)?->{$field};
    }

    public function setTranslation(string $locale, array $data): void
    {
        $this->translations()->updateOrCreate(
            ['locale' => $locale],
            $data
        );
    }

    protected function getTranslationModelClass(): string
    {
        return static::class . 'Translation';
    }
}
