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

if (!function_exists('get_button_classes')) {
    /**
     * Get classes for buttons based on settings
     *
     * @param string $type primary|secondary|success|danger
     * @param string $size sm|md|lg
     * @return string
     */
    function get_button_classes($type = 'primary', $size = 'md')
    {
        $settings = get_all_settings();
        
        $baseClasses = 'inline-flex items-center justify-center font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors';
        
        // Size classes
        $sizeClasses = [
            'sm' => 'px-3 py-1.5 text-sm',
            'md' => 'px-4 py-2 text-base',
            'lg' => 'px-6 py-3 text-lg'
        ];
        
        // Type classes
        $typeClasses = [
            'primary' => [
                'bg' => $settings['primary_color'] ?? '#3b82f6',
                'hover' => $settings['hover_color'] ?? '#2563eb',
                'text' => '#ffffff'
            ],
            'secondary' => [
                'bg' => $settings['secondary_color'] ?? '#1e40af',
                'hover' => $settings['hover_color'] ?? '#1e3a8a',
                'text' => '#ffffff'
            ],
            'success' => [
                'bg' => $settings['success_color'] ?? '#10b981',
                'hover' => $settings['hover_color'] ?? '#059669',
                'text' => '#ffffff'
            ],
            'danger' => [
                'bg' => $settings['danger_color'] ?? '#ef4444',
                'hover' => $settings['hover_color'] ?? '#dc2626',
                'text' => '#ffffff'
            ]
        ];
        
        $selectedType = $typeClasses[$type] ?? $typeClasses['primary'];
        
        return sprintf(
            '%s %s bg-[%s] hover:bg-[%s] text-[%s] rounded-lg',
            $baseClasses,
            $sizeClasses[$size] ?? $sizeClasses['md'],
            $selectedType['bg'],
            $selectedType['hover'],
            $selectedType['text']
        );
    }
}

if (!function_exists('get_text_classes')) {
    /**
     * Get classes for text based on settings
     *
     * @param string $type heading|body|link
     * @param string $size sm|md|lg|xl
     * @return string
     */
    function get_text_classes($type = 'body', $size = 'md')
    {
        $settings = get_all_settings();
        
        $baseClasses = 'font-medium';
        
        // Size classes
        $sizeClasses = [
            'sm' => 'text-sm',
            'md' => 'text-base',
            'lg' => 'text-lg',
            'xl' => 'text-xl'
        ];
        
        // Type classes
        $typeClasses = [
            'heading' => [
                'color' => $settings['text_color'] ?? '#1f2937',
                'hover' => $settings['hover_text_color'] ?? '#3b82f6'
            ],
            'body' => [
                'color' => $settings['text_color'] ?? '#4b5563',
                'hover' => $settings['hover_text_color'] ?? '#3b82f6'
            ],
            'link' => [
                'color' => $settings['primary_color'] ?? '#3b82f6',
                'hover' => $settings['hover_text_color'] ?? '#2563eb'
            ]
        ];
        
        $selectedType = $typeClasses[$type] ?? $typeClasses['body'];
        
        return sprintf(
            '%s %s text-[%s] hover:text-[%s]',
            $baseClasses,
            $sizeClasses[$size] ?? $sizeClasses['md'],
            $selectedType['color'],
            $selectedType['hover']
        );
    }
}

if (!function_exists('get_input_classes')) {
    /**
     * Get classes for input fields based on settings
     *
     * @return string
     */
    function get_input_classes()
    {
        $settings = get_all_settings();
        
        return sprintf(
            'w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-[%s] focus:border-[%s]',
            $settings['primary_color'] ?? '#3b82f6',
            $settings['primary_color'] ?? '#3b82f6'
        );
    }
}

if (!function_exists('get_card_classes')) {
    /**
     * Get classes for cards based on settings
     *
     * @return string
     */
    function get_card_classes()
    {
        $settings = get_all_settings();
        
        return sprintf(
            'bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 border border-gray-100 hover:border-[%s]',
            $settings['primary_color'] ?? '#3b82f6'
        );
    }
} 