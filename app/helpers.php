<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

if (!function_exists('get_setting')) {
    /**
     * Get a setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get_setting($key, $default = null)
    {
        $settings = Cache::remember('app_settings', now()->addDay(), function() {
            if (Storage::exists('settings.json')) {
                return json_decode(Storage::get('settings.json'), true);
            }
            return [];
        });
        
        return $settings[$key] ?? $default;
    }
}

if (!function_exists('get_all_settings')) {
    /**
     * Get all settings
     *
     * @return array
     */
    function get_all_settings()
    {
        return Cache::remember('app_settings', now()->addDay(), function() {
            if (Storage::exists('settings.json')) {
                return json_decode(Storage::get('settings.json'), true);
            }
            return [];
        });
    }
}

if (!function_exists('primary_button_classes')) {
    /**
     * Get classes for primary buttons based on settings
     *
     * @return string
     */
    function primary_button_classes()
    {
        $buttonStyle = get_setting('button_style', 'default');
        $baseClasses = 'px-4 py-2 bg-blue-600 text-white font-semibold hover:bg-blue-700';
        
        switch ($buttonStyle) {
            case 'rounded':
                return $baseClasses . ' rounded-lg';
            case 'pill':
                return $baseClasses . ' rounded-full';
            case 'square':
                return $baseClasses;
            default:
                return $baseClasses . ' rounded';
        }
    }
}

if (!function_exists('secondary_button_classes')) {
    /**
     * Get classes for secondary buttons based on settings
     *
     * @return string
     */
    function secondary_button_classes()
    {
        $buttonStyle = get_setting('button_style', 'default');
        $baseClasses = 'px-4 py-2 bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300';
        
        switch ($buttonStyle) {
            case 'rounded':
                return $baseClasses . ' rounded-lg';
            case 'pill':
                return $baseClasses . ' rounded-full';
            case 'square':
                return $baseClasses;
            default:
                return $baseClasses . ' rounded';
        }
    }
}

if (!function_exists('danger_button_classes')) {
    /**
     * Get classes for danger buttons based on settings
     *
     * @return string
     */
    function danger_button_classes()
    {
        $buttonStyle = get_setting('button_style', 'default');
        $baseClasses = 'px-4 py-2 bg-red-600 text-white font-semibold hover:bg-red-700';
        
        switch ($buttonStyle) {
            case 'rounded':
                return $baseClasses . ' rounded-lg';
            case 'pill':
                return $baseClasses . ' rounded-full';
            case 'square':
                return $baseClasses;
            default:
                return $baseClasses . ' rounded';
        }
    }
} 