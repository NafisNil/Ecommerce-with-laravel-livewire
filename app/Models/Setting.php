<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
#[Fillable(['key', 'value', 'type', 'group'])]

class Setting extends Model
{
    //
    #[Scope()]
    protected function group(Builder $query, string $group)
    {
        return $query->where('group', $group);
    }

    //helper
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? static::castValue($setting->value, $setting->type) : $default;
    }

    public static function setValue(string $key, $value, string $type = 'string', string $group = 'general')
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $group]
        );
        return $setting;
    }

    protected static function castValue($value, $type)
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'number' => is_numeric($value) ? +$value : $value,
            'json' => json_decode($value, true),
        };
    }
}
