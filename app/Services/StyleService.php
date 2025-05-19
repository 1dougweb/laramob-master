<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Services\SettingsService;

class StyleService
{
    /**
     * Get the application styles based on settings
     *
     * @return array
     */
    public function getStyles(): array
    {
        // Usar o SettingsService para obter as configurações
        $settingsService = app(SettingsService::class);
        $settings = $settingsService->getSettingsByGroup('style');
        
        return [
            'primary_color' => $settings['primary_color'] ?? '#ea5d00',
            'secondary_color' => $settings['secondary_color'] ?? '#1e40af',
            'success_color' => $settings['success_color'] ?? '#10b981',
            'danger_color' => $settings['danger_color'] ?? '#ef4444',
            'warning_color' => $settings['warning_color'] ?? '#f59e0b',
            'info_color' => $settings['info_color'] ?? '#3b82f6',
            'font_family' => $this->getFontFamily($settings['font_family'] ?? 'figtree'),
            'border_radius' => $this->getBorderRadius($settings['border_radius'] ?? 'default'),
            'sidebar_style' => $settings['sidebar_style'] ?? 'light',
            'sidebar_color' => $settings['sidebar_color'] ?? '#ffffff',
            'logo_bg_color' => $settings['logo_bg_color'] ?? '#0a1d33',
            'icon_color' => $settings['icon_color'] ?? '#ea5d00',
            'text_color' => $settings['text_color'] ?? '#4b5563',
            'hover_color' => $settings['hover_color'] ?? '#ea5d00',
            'hover_text_color' => $settings['hover_text_color'] ?? '#ea5d00',
            'hover_icon_color' => $settings['hover_icon_color'] ?? '#ea5d00',
            'hover_bg_color' => $settings['hover_bg_color'] ?? '#ebf5ff',
            'active_text_color' => $settings['active_text_color'] ?? '#ea5d00',
            'active_icon_color' => $settings['active_icon_color'] ?? '#ea5d00',
            'active_bg_color' => $settings['active_bg_color'] ?? '#dbeafe',
            'button_style' => $settings['button_style'] ?? 'default',
            'heading_color' => $settings['heading_color'] ?? '#4b5563',
            'background_color' => $settings['background_color'] ?? '#f3f4f6',
            'card_background' => $settings['card_background'] ?? '#ffffff',
            'card_border_color' => $settings['card_border_color'] ?? '#e5e7eb',
            'input_border_color' => $settings['input_border_color'] ?? '#d1d5db',
            'input_focus_color' => $settings['input_focus_color'] ?? $settings['primary_color'] ?? '#ea5d00',
        ];
    }
    
    /**
     * Generate CSS variables for the application
     *
     * @return string
     */
    public function generateCssVariables(): string
    {
        $styles = $this->getStyles();
        
        $css = ":root {\n";
        $css .= "  --primary-color: {$styles['primary_color']};\n";
        $css .= "  --primary-color-light: " . $this->lightenColor($styles['primary_color'], 0.2) . ";\n";
        $css .= "  --primary-color-dark: " . $this->darkenColor($styles['primary_color'], 0.2) . ";\n";
        $css .= "  --secondary-color: {$styles['secondary_color']};\n";
        $css .= "  --secondary-color-light: " . $this->lightenColor($styles['secondary_color'], 0.2) . ";\n";
        $css .= "  --secondary-color-dark: " . $this->darkenColor($styles['secondary_color'], 0.2) . ";\n";
        $css .= "  --success-color: " . ($styles['success_color'] ? $styles['success_color'] : '#10b981') . ";\n";
        $css .= "  --danger-color: " . ($styles['danger_color'] ? $styles['danger_color'] : '#ef4444') . ";\n";
        $css .= "  --warning-color: " . ($styles['warning_color'] ? $styles['warning_color'] : '#f59e0b') . ";\n";
        $css .= "  --info-color: " . ($styles['info_color'] ? $styles['info_color'] : '#3b82f6') . ";\n";
        $css .= "  --logo-bg-color: {$styles['logo_bg_color']};\n";
        $css .= "  --sidebar-color: {$styles['sidebar_color']};\n";
        $css .= "  --icon-color: {$styles['icon_color']};\n";
        $css .= "  --text-color: {$styles['text_color']};\n";
        $css .= "  --hover-color: {$styles['hover_color']};\n";
        $css .= "  --hover-text-color: {$styles['hover_text_color']};\n";
        $css .= "  --hover-icon-color: {$styles['hover_icon_color']};\n";
        $css .= "  --hover-bg-color: {$styles['hover_bg_color']};\n";
        $css .= "  --active-text-color: {$styles['active_text_color']};\n";
        $css .= "  --active-icon-color: {$styles['active_icon_color']};\n";
        $css .= "  --active-bg-color: {$styles['active_bg_color']};\n";
        $css .= "  --font-family: {$styles['font_family']};\n";
        $css .= "  --border-radius: {$styles['border_radius']};\n";
        $css .= "  --heading-color: " . (isset($styles['heading_color']) ? $styles['heading_color'] : $styles['text_color']) . ";\n";
        $css .= "  --background-color: " . (isset($styles['background_color']) ? $styles['background_color'] : '#f3f4f6') . ";\n";
        $css .= "  --card-background: " . (isset($styles['card_background']) ? $styles['card_background'] : '#ffffff') . ";\n";
        $css .= "  --card-border-color: " . (isset($styles['card_border_color']) ? $styles['card_border_color'] : '#e5e7eb') . ";\n";
        $css .= "  --input-border-color: " . (isset($styles['input_border_color']) ? $styles['input_border_color'] : '#d1d5db') . ";\n";
        $css .= "  --input-focus-color: " . (isset($styles['input_focus_color']) ? $styles['input_focus_color'] : $styles['primary_color']) . ";\n";
        $css .= "}\n";
        
        // Button styles
        $css .= $this->generateButtonStyles($styles['button_style']);
        
        // Sidebar styles
        $css .= $this->generateSidebarStyles(
            $styles['sidebar_style'], 
            $styles['primary_color'], 
            $styles['secondary_color'], 
            $styles['sidebar_color'], 
            $styles['logo_bg_color'],
            $styles['icon_color'],
            $styles['text_color'],
            $styles['hover_text_color'],
            $styles['hover_icon_color'],
            $styles['hover_bg_color'],
            $styles['active_text_color'],
            $styles['active_icon_color'],
            $styles['active_bg_color']
        );
        
        // Frontend specific styles
        $css .= $this->generateFrontendStyles($styles);
        
        return $css;
    }
    
    /**
     * Generate button styles based on selected style
     *
     * @param string $buttonStyle
     * @return string
     */
    private function generateButtonStyles(string $buttonStyle): string
    {
        $css = ".btn, button[type='submit'], .button {\n";
        
        switch ($buttonStyle) {
            case 'rounded':
                $css .= "  border-radius: 0.5rem;\n";
                break;
            case 'pill':
                $css .= "  border-radius: 9999px;\n";
                break;
            case 'square':
                $css .= "  border-radius: 0;\n";
                break;
            default:
                $css .= "  border-radius: 0.25rem;\n";
        }
        
        $css .= "  transition: all 0.2s ease-in-out;\n";
        $css .= "}\n";
        
        return $css;
    }
    
    /**
     * Generate sidebar styles based on selected style
     *
     * @param string $sidebarStyle
     * @param string $primaryColor
     * @param string $secondaryColor
     * @param string $sidebarColor
     * @param string $logoBgColor
     * @param string $iconColor
     * @param string $textColor
     * @param string $hoverTextColor
     * @param string $hoverIconColor
     * @param string $hoverBgColor
     * @param string $activeTextColor
     * @param string $activeIconColor
     * @param string $activeBgColor
     * @return string
     */
    private function generateSidebarStyles(
        string $sidebarStyle, 
        string $primaryColor, 
        string $secondaryColor, 
        string $sidebarColor, 
        string $logoBgColor,
        string $iconColor,
        string $textColor,
        string $hoverTextColor,
        string $hoverIconColor,
        string $hoverBgColor,
        string $activeTextColor,
        string $activeIconColor,
        string $activeBgColor
    ): string
    {
        $css = "";
        
        // Logo background color styling
        $css .= "aside .flex.items-center.justify-between.p-6.bg-blue-700,\n";
        $css .= "aside .flex.items-center.justify-between.p-6[style*='background-color'] {\n";
        $css .= "  background-color: $logoBgColor !important;\n";
        $css .= "}\n";
        
        // Icon and text color - usar !important para superar estilos inline e classes Tailwind
        $css .= "aside nav a i, aside nav a svg, aside nav button svg, aside nav button i {\n";
        $css .= "  color: $iconColor !important;\n";
        $css .= "}\n";
        
        $css .= "aside nav a, aside nav a span:not(.mr-2), aside nav button, aside nav button span:not(.mr-2) {\n";
        $css .= "  color: $textColor !important;\n";
        $css .= "}\n";
        
        // Section titles
        $css .= "aside nav .text-gray-500, aside nav p.uppercase {\n";
        $css .= "  color: " . $this->adjustColor($textColor, 0.3, false) . " !important;\n";
        $css .= "}\n";
        
        // Hover effect
        $css .= "aside nav a:hover, aside nav button:hover {\n";
        $css .= "  background-color: $hoverBgColor !important;\n";
        $css .= "}\n";
        
        $css .= "aside nav a:hover span:not(.mr-2), aside nav button:hover span:not(.mr-2) {\n";
        $css .= "  color: $hoverTextColor !important;\n";
        $css .= "}\n";
        
        $css .= "aside nav a:hover i, aside nav a:hover svg, aside nav button:hover i, aside nav button:hover svg {\n";
        $css .= "  color: $hoverIconColor !important;\n";
        $css .= "}\n";
        
        // Selected menu item (active state)
        $css .= "aside nav a.bg-blue-100, aside nav .bg-blue-100 {\n";
        $css .= "  background-color: $activeBgColor !important;\n";
        $css .= "}\n";
        
        $css .= "aside nav a.bg-blue-100 span:not(.mr-2), aside nav .bg-blue-100 span:not(.mr-2) {\n";
        $css .= "  color: $activeTextColor !important;\n";
        $css .= "}\n";
        
        $css .= "aside nav a.bg-blue-100 i, aside nav a.bg-blue-100 svg, aside nav .bg-blue-100 i, aside nav .bg-blue-100 svg {\n";
        $css .= "  color: $activeIconColor !important;\n";
        $css .= "}\n";
        
        // Override Tailwind defaults used in sidebar
        $css .= "aside nav .text-gray-700 {\n";
        $css .= "  color: $textColor !important;\n";
        $css .= "}\n";
        
        $css .= "aside .hover\\:bg-gray-100:hover {\n";
        $css .= "  background-color: $hoverBgColor !important;\n";
        $css .= "}\n";

        $css .= "aside .hover\\:text-gray-900:hover {\n";
        $css .= "  color: $hoverTextColor !important;\n";
        $css .= "}\n";
        
        // Additional sidebar style specific settings
        switch ($sidebarStyle) {
            case 'dark':
                $css .= "aside {\n";
                $css .= "  background-color: #1f2937 !important;\n";
                $css .= "}\n";
                
                // For dark style, adjust text and icon colors if not explicitly set
                $css .= "aside nav .text-gray-500, aside nav p.uppercase {\n";
                $css .= "  color: #9ca3af !important;\n";
                $css .= "}\n";
                break;
                
            case 'colored':
                $css .= "aside {\n";
                $css .= "  background-color: " . $this->lightenColor($primaryColor, 0.4) . " !important;\n";
                $css .= "}\n";
                
                // For colored sidebar, set default colors if not explicitly set
                if ($iconColor === '#4b5563') { // If using default
                    $css .= "aside nav a i, aside nav a svg, aside nav button svg, aside nav button i {\n";
                    $css .= "  color: " . $this->darkenColor($primaryColor, 0.3) . " !important;\n";
                    $css .= "}\n";
                }
                
                if ($textColor === '#4b5563') { // If using default
                    $css .= "aside nav a, aside nav a span:not(.mr-2), aside nav button, aside nav button span:not(.mr-2) {\n";
                    $css .= "  color: " . $this->darkenColor($primaryColor, 0.5) . " !important;\n";
                    $css .= "}\n";
                }
                break;
                
            default:
                // Light (default)
                $css .= "aside {\n";
                $css .= "  background-color: $sidebarColor !important;\n";
                $css .= "}\n";
                break;
        }
        
        return $css;
    }
    
    /**
     * Get font family string based on setting
     *
     * @param string $fontFamily
     * @return string
     */
    private function getFontFamily(string $fontFamily): string
    {
        switch ($fontFamily) {
            case 'inter':
                return "'Inter', sans-serif";
            case 'roboto':
                return "'Roboto', sans-serif";
            case 'open-sans':
                return "'Open Sans', sans-serif";
            case 'montserrat':
                return "'Montserrat', sans-serif";
            default:
                return "'Figtree', sans-serif";
        }
    }
    
    /**
     * Get border radius value based on setting
     *
     * @param string $borderRadius
     * @return string
     */
    private function getBorderRadius(string $borderRadius): string
    {
        switch ($borderRadius) {
            case 'none':
                return "0";
            case 'sm':
                return "0.125rem";
            case 'md':
                return "0.375rem";
            case 'lg':
                return "0.5rem";
            case 'xl':
                return "0.75rem";
            case 'full':
                return "9999px";
            default:
                return "0.375rem";
        }
    }
    
    /**
     * Lighten a color by a given percentage
     *
     * @param string $hex
     * @param float $percent
     * @return string
     */
    private function lightenColor(string $hex, float $percent): string
    {
        return $this->adjustColor($hex, $percent, true);
    }
    
    /**
     * Darken a color by a given percentage
     *
     * @param string $hex
     * @param float $percent
     * @return string
     */
    private function darkenColor(string $hex, float $percent): string
    {
        return $this->adjustColor($hex, $percent, false);
    }
    
    /**
     * Adjust a color by a given percentage
     *
     * @param string $hex
     * @param float $percent
     * @param bool $lighten
     * @return string
     */
    private function adjustColor(string $hex, float $percent, bool $lighten): string
    {
        // Remove # if present
        $hex = ltrim($hex, '#');
        
        // Parse hex to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Adjust color
        if ($lighten) {
            $r = round($r + (255 - $r) * $percent);
            $g = round($g + (255 - $g) * $percent);
            $b = round($b + (255 - $b) * $percent);
        } else {
            $r = round($r * (1 - $percent));
            $g = round($g * (1 - $percent));
            $b = round($b * (1 - $percent));
        }
        
        // Ensure values are within valid range
        $r = max(0, min(255, $r));
        $g = max(0, min(255, $g));
        $b = max(0, min(255, $b));
        
        // Convert back to hex
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
    
    /**
     * Generate frontend-specific styles
     *
     * @param array $styles
     * @return string
     */
    private function generateFrontendStyles(array $styles): string
    {
        $css = "\n/* Frontend-specific styles */\n";
        
        // Button classes
        $css .= ".btn-primary {\n";
        $css .= "  background-color: var(--primary-color);\n";
        $css .= "  color: white;\n";
        $css .= "  border-radius: var(--border-radius);\n";
        $css .= "  transition: background-color 0.2s;\n";
        $css .= "}\n";
        
        $css .= ".btn-primary:hover {\n";
        $css .= "  background-color: var(--primary-color-dark);\n";
        $css .= "}\n";
        
        $css .= ".btn-secondary {\n";
        $css .= "  background-color: var(--secondary-color);\n";
        $css .= "  color: white;\n";
        $css .= "  border-radius: var(--border-radius);\n";
        $css .= "  transition: background-color 0.2s;\n";
        $css .= "}\n";
        
        $css .= ".btn-secondary:hover {\n";
        $css .= "  background-color: var(--secondary-color-dark);\n";
        $css .= "}\n";
        
        // Card styles
        $css .= ".card {\n";
        $css .= "  background-color: var(--card-background);\n";
        $css .= "  border: 1px solid var(--card-border-color);\n";
        $css .= "  border-radius: var(--border-radius);\n";
        $css .= "}\n";
        
        // Form input styles
        $css .= "input[type='text'], input[type='email'], input[type='password'], textarea, select {\n";
        $css .= "  border-color: var(--input-border-color);\n";
        $css .= "  border-radius: var(--border-radius);\n";
        $css .= "}\n";
        
        $css .= "input[type='text']:focus, input[type='email']:focus, input[type='password']:focus, textarea:focus, select:focus {\n";
        $css .= "  border-color: var(--input-focus-color);\n";
        $css .= "  box-shadow: 0 0 0 3px " . $this->lightenColor($styles['primary_color'], 0.8) . ";\n";
        $css .= "}\n";
        
        return $css;
    }
} 