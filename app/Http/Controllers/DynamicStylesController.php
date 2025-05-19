<?php

namespace App\Http\Controllers;

use App\Services\StyleService;
use Illuminate\Http\Response;

class DynamicStylesController extends Controller
{
    protected $styleService;

    public function __construct(StyleService $styleService)
    {
        $this->styleService = $styleService;
    }

    /**
     * Gera o CSS dinamicamente baseado nas configurações de estilo
     *
     * @return Response
     */
    public function getCss()
    {
        $css = $this->styleService->generateCssVariables();
        
        return response($css)
            ->header('Content-Type', 'text/css')
            ->header('Cache-Control', 'public, max-age=3600'); // Cache por 1 hora
    }
} 