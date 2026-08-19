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
    
    protected static ?\Illuminate\Support\Collection $loadedSettings = null;

    protected function casts(): array
    {
        return [
            'type' => SettingType::class,
        ];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            if (self::$loadedSettings === null) {
                self::$loadedSettings = Cache::remember('all_settings', 3600, function () {
                    return static::all()->keyBy('key');
                });
            }

            if (self::$loadedSettings->has($key)) {
                return self::$loadedSettings->get($key)->getCastedValue();
            }

            return $default;
        } catch (QueryException) {
            return $default;
        }
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        self::$loadedSettings = null;
        Cache::forget('all_settings');
        Cache::forget("setting:{$key}"); // Legacy fallback if needed
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
