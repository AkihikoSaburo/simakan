<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        try {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        } catch (\Illuminate\Database\QueryException $e) {
            return $default;
        }
    }

    /**
     * Set setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @return \App\Models\Setting|null
     */
    public static function set(string $key, $value)
    {
        try {
            return self::updateOrCreate(['key' => $key], ['value' => $value]);
        } catch (\Illuminate\Database\QueryException $e) {
            return null;
        }
    }
}
