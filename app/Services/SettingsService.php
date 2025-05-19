<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingsService
{
    /**
     * Get all settings
     *
     * @return array
     */
    public function getAllSettings(): array
    {
        return Cache::remember('app_settings', now()->addDay(), function() {
            $settings = [];
            Setting::all()->each(function($setting) use (&$settings) {
                $settings[$setting->key] = $setting->value;
            });
            return $settings;
        });
    }

    /**
     * Get settings by group
     *
     * @param string $group
     * @return array
     */
    public function getSettingsByGroup(string $group): array
    {
        $settings = [];
        Setting::where('group', $group)->get()->each(function($setting) use (&$settings) {
            $settings[$setting->key] = $setting->value;
        });
        return $settings;
    }

    /**
     * Get a setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSetting(string $key, $default = null)
    {
        $settings = $this->getAllSettings();
        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @param string $type
     * @param string|null $description
     * @param bool $isPublic
     * @return void
     */
    public function setSetting(
        string $key, 
        $value, 
        string $group = 'general',
        string $type = 'text',
        ?string $description = null,
        bool $isPublic = false
    ): void {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type,
                'description' => $description,
                'is_public' => $isPublic
            ]
        );

        $this->clearCache();
    }

    /**
     * Set multiple settings at once
     *
     * @param array $settings
     * @return void
     */
    public function setMultipleSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->setSetting($key, $value);
        }
    }

    /**
     * Clear settings cache
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget('app_settings');
    }

    /**
     * Get default settings
     *
     * @return array
     */
    public function getDefaultSettings(): array
    {
        return [
            // General Settings
            'site_name' => [
                'value' => config('app.name', 'LaraMob'),
                'group' => 'general',
                'type' => 'text',
                'description' => 'Nome do site',
                'is_public' => true
            ],
            'site_description' => [
                'value' => 'Sistema imobiliário',
                'group' => 'general',
                'type' => 'textarea',
                'description' => 'Descrição do site',
                'is_public' => true
            ],
            'site_email' => [
                'value' => 'contato@exemplo.com',
                'group' => 'general',
                'type' => 'email',
                'description' => 'E-mail de contato',
                'is_public' => true
            ],
            'phone' => [
                'value' => '(00) 00000-0000',
                'group' => 'general',
                'type' => 'text',
                'description' => 'Telefone de contato',
                'is_public' => true
            ],
            'address' => [
                'value' => 'Endereço da empresa',
                'group' => 'general',
                'type' => 'textarea',
                'description' => 'Endereço da empresa',
                'is_public' => true
            ],

            // Style Settings
            'primary_color' => [
                'value' => '#3b82f6',
                'group' => 'style',
                'type' => 'color',
                'description' => 'Cor primária',
                'is_public' => true
            ],
            'secondary_color' => [
                'value' => '#1e40af',
                'group' => 'style',
                'type' => 'color',
                'description' => 'Cor secundária',
                'is_public' => true
            ],
            'success_color' => [
                'value' => '#10b981',
                'group' => 'style',
                'type' => 'color',
                'description' => 'Cor de sucesso',
                'is_public' => true
            ],
            'danger_color' => [
                'value' => '#ef4444',
                'group' => 'style',
                'type' => 'color',
                'description' => 'Cor de erro',
                'is_public' => true
            ],
            'font_family' => [
                'value' => 'figtree',
                'group' => 'style',
                'type' => 'select',
                'description' => 'Fonte principal',
                'is_public' => true
            ],
            'border_radius' => [
                'value' => 'default',
                'group' => 'style',
                'type' => 'select',
                'description' => 'Raio das bordas',
                'is_public' => true
            ],
            'sidebar_style' => [
                'value' => 'light',
                'group' => 'style',
                'type' => 'select',
                'description' => 'Estilo da barra lateral',
                'is_public' => true
            ],
            'sidebar_color' => [
                'value' => '#ffffff',
                'group' => 'style',
                'type' => 'color',
                'description' => 'Cor da barra lateral',
                'is_public' => true
            ],
            'logo_bg_color' => [
                'value' => '#3b82f6',
                'group' => 'style',
                'type' => 'color',
                'description' => 'Cor de fundo do logo',
                'is_public' => true
            ],
            'icon_color' => [
                'value' => '#4b5563',
                'group' => 'style',
                'type' => 'color',
                'description' => 'Cor dos ícones',
                'is_public' => true
            ],
            'text_color' => [
                'value' => '#4b5563',
                'group' => 'style',
                'type' => 'color',
                'description' => 'Cor do texto',
                'is_public' => true
            ],
            'hover_color' => [
                'value' => '#3b82f6',
                'group' => 'style',
                'type' => 'color',
                'description' => 'Cor de hover',
                'is_public' => true
            ],
            'hover_text_color' => [
                'value' => '#3b82f6',
                'group' => 'style',
                'type' => 'color',
                'description' => 'Cor do texto no hover',
                'is_public' => true
            ],
            'hover_icon_color' => [
                'value' => '#3b82f6',
                'group' => 'style',
                'type' => 'color',
                'description' => 'Cor do ícone no hover',
                'is_public' => true
            ],
            'hover_bg_color' => [
                'value' => '#ebf5ff',
                'group' => 'style',
                'type' => 'color',
                'description' => 'Cor de fundo no hover',
                'is_public' => true
            ],
            'active_text_color' => [
                'value' => '#3b82f6',
                'group' => 'style',
                'type' => 'color',
                'description' => 'Cor do texto ativo',
                'is_public' => true
            ],
            'active_icon_color' => [
                'value' => '#3b82f6',
                'group' => 'style',
                'type' => 'color',
                'description' => 'Cor do ícone ativo',
                'is_public' => true
            ],
            'active_bg_color' => [
                'value' => '#dbeafe',
                'group' => 'style',
                'type' => 'color',
                'description' => 'Cor de fundo ativo',
                'is_public' => true
            ],
            'button_style' => [
                'value' => 'default',
                'group' => 'style',
                'type' => 'select',
                'description' => 'Estilo dos botões',
                'is_public' => true
            ],
            'card_style' => [
                'value' => 'default',
                'group' => 'style',
                'type' => 'select',
                'description' => 'Estilo dos cards',
                'is_public' => true
            ],
            'heading_size' => [
                'value' => 'normal',
                'group' => 'style',
                'type' => 'select',
                'description' => 'Tamanho dos títulos',
                'is_public' => true
            ],
            'text_size' => [
                'value' => 'normal',
                'group' => 'style',
                'type' => 'select',
                'description' => 'Tamanho do texto',
                'is_public' => true
            ],

            // Security Settings
            'enable_registrations' => [
                'value' => true,
                'group' => 'security',
                'type' => 'boolean',
                'description' => 'Habilitar registros',
                'is_public' => false
            ],
            'enable_2fa' => [
                'value' => false,
                'group' => 'security',
                'type' => 'boolean',
                'description' => 'Habilitar autenticação de dois fatores',
                'is_public' => false
            ],
            'force_password_change' => [
                'value' => false,
                'group' => 'security',
                'type' => 'boolean',
                'description' => 'Forçar mudança de senha',
                'is_public' => false
            ],
            'require_uppercase' => [
                'value' => true,
                'group' => 'security',
                'type' => 'boolean',
                'description' => 'Exigir letra maiúscula na senha',
                'is_public' => false
            ],
            'require_number' => [
                'value' => true,
                'group' => 'security',
                'type' => 'boolean',
                'description' => 'Exigir número na senha',
                'is_public' => false
            ],
            'require_special_char' => [
                'value' => true,
                'group' => 'security',
                'type' => 'boolean',
                'description' => 'Exigir caractere especial na senha',
                'is_public' => false
            ],
            'enable_recaptcha' => [
                'value' => false,
                'group' => 'security',
                'type' => 'boolean',
                'description' => 'Habilitar reCAPTCHA',
                'is_public' => false
            ],

            // SEO Settings
            'generate_sitemap' => [
                'value' => true,
                'group' => 'seo',
                'type' => 'boolean',
                'description' => 'Gerar sitemap automaticamente',
                'is_public' => false
            ],
            'robots_txt' => [
                'value' => "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /login\nDisallow: /register\n\nSitemap: " . config('app.url') . "/sitemap.xml",
                'group' => 'seo',
                'type' => 'textarea',
                'description' => 'Conteúdo do robots.txt',
                'is_public' => false
            ],
        ];
    }

    /**
     * Initialize default settings
     *
     * @return void
     */
    public function initializeDefaultSettings(): void
    {
        $defaults = $this->getDefaultSettings();
        
        foreach ($defaults as $key => $setting) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $setting['value'],
                    'group' => $setting['group'],
                    'type' => $setting['type'],
                    'description' => $setting['description'],
                    'is_public' => $setting['is_public']
                ]
            );
        }

        $this->clearCache();
    }
} 