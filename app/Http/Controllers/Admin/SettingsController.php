<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Services\StyleService;

class SettingsController extends Controller
{
    /**
     * Exibir a página de configurações gerais.
     */
    public function general()
    {
        $settings = $this->getSettings();
        
        return view('admin.settings.general', compact('settings'));
    }

    /**
     * Exibir a página de configurações de e-mail.
     */
    public function email()
    {
        $settings = $this->getSettings();
        
        return view('admin.settings.email', compact('settings'));
    }

    /**
     * Exibir a página de configurações de SEO.
     */
    public function seo()
    {
        $settings = $this->getSettings();
        
        return view('admin.settings.seo', compact('settings'));
    }

    /**
     * Exibir a página de configurações de segurança.
     */
    public function security()
    {
        $settings = $this->getSettings();
        
        return view('admin.settings.security', compact('settings'));
    }

    /**
     * Exibir a página de configurações de estilos.
     */
    public function styles()
    {
        $settings = $this->getSettings();
        
        return view('admin.settings.styles', compact('settings'));
    }

    /**
     * Salvar as configurações
     */
    public function save(Request $request)
    {
        $settings = $this->getSettings();
        
        // Mesclar as novas configurações com as existentes
        $settings = array_merge($settings, $request->except('_token'));
        
        // Processar upload de logo se houver
        if ($request->hasFile('site_logo')) {
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            $settings['site_logo'] = $logoPath;
        }
        
        // Processar upload de imagem OG se houver
        if ($request->hasFile('og_image')) {
            $ogImagePath = $request->file('og_image')->store('settings', 'public');
            $settings['og_image'] = $ogImagePath;
        }
        
        // Garantir que as cores estejam no formato correto
        if ($request->has('primary_color_hex')) {
            $settings['primary_color'] = $request->input('primary_color_hex');
        }
        
        if ($request->has('secondary_color_hex')) {
            $settings['secondary_color'] = $request->input('secondary_color_hex');
        }
        
        if ($request->has('success_color_hex')) {
            $settings['success_color'] = $request->input('success_color_hex');
        }
        
        if ($request->has('danger_color_hex')) {
            $settings['danger_color'] = $request->input('danger_color_hex');
        }
        
        if ($request->has('sidebar_color_hex')) {
            $settings['sidebar_color'] = $request->input('sidebar_color_hex');
        }
        
        if ($request->has('logo_bg_color_hex')) {
            $settings['logo_bg_color'] = $request->input('logo_bg_color_hex');
        }
        
        if ($request->has('icon_color_hex')) {
            $settings['icon_color'] = $request->input('icon_color_hex');
        }
        
        if ($request->has('text_color_hex')) {
            $settings['text_color'] = $request->input('text_color_hex');
        }
        
        if ($request->has('hover_color_hex')) {
            $settings['hover_color'] = $request->input('hover_color_hex');
        }
        
        if ($request->has('hover_text_color_hex')) {
            $settings['hover_text_color'] = $request->input('hover_text_color_hex');
        }
        
        if ($request->has('hover_icon_color_hex')) {
            $settings['hover_icon_color'] = $request->input('hover_icon_color_hex');
        }
        
        if ($request->has('hover_bg_color_hex')) {
            $settings['hover_bg_color'] = $request->input('hover_bg_color_hex');
        }
        
        if ($request->has('active_text_color_hex')) {
            $settings['active_text_color'] = $request->input('active_text_color_hex');
        }
        
        if ($request->has('active_icon_color_hex')) {
            $settings['active_icon_color'] = $request->input('active_icon_color_hex');
        }
        
        if ($request->has('active_bg_color_hex')) {
            $settings['active_bg_color'] = $request->input('active_bg_color_hex');
        }
        
        // Converter checkboxes para booleanos
        $checkboxFields = [
            'enable_registrations', 'enable_blog', 'enable_2fa',
            'force_password_change', 'require_uppercase', 'require_number',
            'require_special_char', 'enable_recaptcha', 'generate_sitemap'
        ];
        
        foreach ($checkboxFields as $field) {
            $settings[$field] = $request->has($field);
        }
        
        // Armazenar as configurações em um arquivo JSON
        Storage::put('settings.json', json_encode($settings, JSON_PRETTY_PRINT));
        
        // Limpar o cache
        Cache::forget('app_settings');
        
        // Regenerar os estilos CSS
        try {
            $styleService = new StyleService();
            $css = $styleService->generateCssVariables();
            
            // Save to storage
            Storage::disk('public')->put('css/dynamic-styles.css', $css);
            
            // Also save to public directory for direct access
            if (!file_exists(public_path('css'))) {
                mkdir(public_path('css'), 0755, true);
            }
            
            file_put_contents(public_path('css/dynamic-styles.css'), $css);
        } catch (\Exception $e) {
            // Log error but don't stop the process
            \Log::error('Failed to generate styles: ' . $e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Configurações salvas com sucesso.');
    }

    /**
     * Obter todas as configurações
     */
    private function getSettings()
    {
        // Tentar obter do cache primeiro
        if (Cache::has('app_settings')) {
            return Cache::get('app_settings');
        }
        
        // Se não estiver no cache, obter do arquivo
        if (Storage::exists('settings.json')) {
            $settings = json_decode(Storage::get('settings.json'), true);
        } else {
            // Configurações padrão se o arquivo não existir
            $settings = [
                'site_name' => config('app.name', 'LaraMob'),
                'site_description' => 'Sistema imobiliário',
                'site_email' => 'contato@exemplo.com',
                'phone' => '(00) 00000-0000',
                'address' => 'Endereço da empresa',
                'primary_color' => '#3b82f6',
                'secondary_color' => '#1e40af',
                'success_color' => '#10b981',
                'danger_color' => '#ef4444',
                'font_family' => 'figtree',
                'border_radius' => 'default',
                'sidebar_style' => 'light',
                'sidebar_color' => '#ffffff',
                'logo_bg_color' => '#3b82f6',
                'icon_color' => '#4b5563',
                'text_color' => '#4b5563',
                'hover_color' => '#3b82f6',
                'hover_text_color' => '#3b82f6',
                'hover_icon_color' => '#3b82f6',
                'hover_bg_color' => '#ebf5ff',
                'active_text_color' => '#3b82f6',
                'active_icon_color' => '#3b82f6',
                'active_bg_color' => '#dbeafe',
                'button_style' => 'default',
                'card_style' => 'default',
                'heading_size' => 'normal',
                'text_size' => 'normal',
            ];
        }
        
        // Armazenar no cache
        Cache::put('app_settings', $settings, now()->addDay());
        
        return $settings;
    }

    /**
     * Gerar CSS dinâmico com base nas configurações
     */
    public function dynamicStyles()
    {
        $styleService = new StyleService();
        $css = $styleService->generateCssVariables();
        
        return response($css)->header('Content-Type', 'text/css');
    }

    /**
     * Salvar as configurações de estilos
     */
    public function saveStyles(Request $request)
    {
        $settings = $this->getSettings();
        
        // Process color settings
        $colorFields = [
            'primary_color', 'secondary_color', 'sidebar_bg_color', 
            'text_color', 'icon_color', 'hover_bg_color',
            'hover_text_color', 'hover_icon_color', 'active_bg_color',
            'active_text_color', 'active_icon_color'
        ];
        
        foreach ($colorFields as $field) {
            if ($request->has($field)) {
                $settings[$field] = $request->input($field);
            }
        }
        
        // Process interface settings
        if ($request->has('font_family')) {
            $settings['font_family'] = $request->input('font_family');
        }
        
        if ($request->has('border_radius')) {
            $settings['border_radius'] = $request->input('border_radius');
        }
        
        if ($request->has('sidebar_style')) {
            $settings['sidebar_style'] = $request->input('sidebar_style');
        }
        
        // Save settings
        $this->saveSettings($settings);
        
        return redirect()->route('admin.settings.styles')->with('success', 'Configurações de estilo atualizadas com sucesso.');
    }
} 