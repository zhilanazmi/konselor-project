<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SchoolSetting extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("school_setting_{$key}", 3600, function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("school_setting_{$key}");
    }

    public static function getPrincipalName(): string
    {
        return static::get('principal_name', '');
    }

    public static function getPrincipalNip(): string
    {
        return static::get('principal_nip', '');
    }
}
