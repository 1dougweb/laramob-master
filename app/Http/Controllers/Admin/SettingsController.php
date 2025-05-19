<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\SettingsService;
use App\Services\StyleService;
use App\Services\SitemapService;

class SettingsController extends Controller
{
    protected $settingsService;
    protected $styleService;

    public function __construct(SettingsService $settingsService, StyleService $styleService)
    {
        $this->settingsService = $settingsService;
        $this->styleService = $styleService;
    }

    /**
     * Exibir a página de configurações gerais.
     */
    public function general()
    {
        $settings = $this->settingsService->getSettingsByGroup('general');
        
        return view('admin.settings.general', compact('settings'));
    }

    /**
     * Exibir a página de configurações de e-mail.
     */
    public function email()
    {
        $settings = $this->settingsService->getSettingsByGroup('email');
        
        return view('admin.settings.email', compact('settings'));
    }

    /**
     * Exibir a página de configurações de SEO.
     */
    public function seo()
    {
        $settings = $this->settingsService->getSettingsByGroup('seo');
        
        return view('admin.settings.seo', compact('settings'));
    }

    /**
     * Exibir a página de configurações de segurança.
     */
    public function security()
    {
        $settings = $this->settingsService->getSettingsByGroup('security');
        
        return view('admin.settings.security', compact('settings'));
    }

    /**
     * Exibir a página de configurações de estilos.
     */
    public function styles()
    {
        $settings = $this->settingsService->getSettingsByGroup('style');
        
        return view('admin.settings.styles', compact('settings'));
    }

    /**
     * Salvar as configurações
     */
    public function save(Request $request)
    {
        $group = $request->input('group', 'general');
        $settings = $request->except(['_token', 'group']);
        
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
        
        // Converter checkboxes para booleanos
        $checkboxFields = [
            'enable_registrations', 'enable_blog', 'enable_2fa',
            'force_password_change', 'require_uppercase', 'require_number',
            'require_special_char', 'enable_recaptcha', 'generate_sitemap'
        ];
        
        foreach ($checkboxFields as $field) {
            $settings[$field] = $request->has($field);
        }
        
        // Salvar as configurações
        $this->settingsService->setMultipleSettings($settings);
        
        // Regenerar os estilos CSS
        try {
            $css = $this->styleService->generateCssVariables();
            
            // Save to storage
            Storage::disk('public')->put('css/dynamic-styles.css', $css);
            
            // Also save to public directory for direct access
            if (!file_exists(public_path('css'))) {
                mkdir(public_path('css'), 0755, true);
            }
            
            file_put_contents(public_path('css/dynamic-styles.css'), $css);
        } catch (\Exception $e) {
            \Log::error('Failed to generate styles: ' . $e->getMessage());
        }

        // Generate sitemap if enabled
        if ($group === 'seo' && ($settings['generate_sitemap'] ?? false)) {
            try {
                $sitemapService = new SitemapService();
                $sitemapService->generate();
            } catch (\Exception $e) {
                \Log::error('Failed to generate sitemap: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Configurações salvas, mas houve um erro ao gerar o sitemap.');
            }
        }

        // Generate robots.txt
        if ($group === 'seo') {
            try {
                $robotsContent = $settings['robots_txt'] ?? "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /login\nDisallow: /register\n\nSitemap: " . config('app.url') . "/sitemap.xml";
                file_put_contents(public_path('robots.txt'), $robotsContent);
            } catch (\Exception $e) {
                \Log::error('Failed to generate robots.txt: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Configurações salvas, mas houve um erro ao gerar o robots.txt.');
            }
        }
        
        // Se for uma requisição AJAX, retorna JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Configurações salvas com sucesso.'
            ]);
        }
        
        // Retorno normal para requisições não-AJAX
        return redirect()->back()->with('success', 'Configurações salvas com sucesso.');
    }
} 