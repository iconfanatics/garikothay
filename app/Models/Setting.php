<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SettingType;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type'];

    protected function casts(): array
    {
        return [
            'type' => SettingType::class,
        ];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        static $memoryCache = [];

        if (array_key_exists($key, $memoryCache)) {
            return $memoryCache[$key];
        }

        try {
            return $memoryCache[$key] = Cache::remember("setting:{$key}", 3600, function () use ($key, $default) {
                $setting = static::where('key', $key)->first();
                return $setting ? $setting->getCastedValue() : $default;
            });
        } catch (QueryException) {
            return $memoryCache[$key] = $default;
        }
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        Cache::forget("setting:{$key}");
    }

    public function getCastedValue(): mixed
    {
        return match($this->type) {
            SettingType::Boolean => (bool) $this->value,
            SettingType::Number => (float) $this->value,
            SettingType::Json => json_decode($this->value, true),
            default => $this->value,
        };
    }
}
