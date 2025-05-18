<?php

namespace App\Livewire;

use Livewire\Component;

class PropertyFeaturesRepeater extends Component
{
    public $features = [];
    public $iconOptions = [
        'home' => 'Casa',
        'office-building' => 'Prédio',
        'sun' => 'Sol',
        'sparkles' => 'Brilhos',
        'wifi' => 'Wi-Fi',
        'fire' => 'Fogo',
        'academic-cap' => 'Chapéu',
        'adjustments' => 'Ajustes',
        'badge-check' => 'Verificado',
        'beaker' => 'Béquer',
        'bell' => 'Sino',
        'bolt' => 'Raio',
        'cake' => 'Bolo',
        'camera' => 'Câmera',
        'cash' => 'Dinheiro',
        'clock' => 'Relógio',
        'cloud' => 'Nuvem',
        'cog' => 'Engrenagem',
        'cube' => 'Cubo',
        'desktop-computer' => 'Computador',
        'device-mobile' => 'Celular',
        'globe' => 'Globo',
        'heart' => 'Coração',
        'key' => 'Chave',
        'light-bulb' => 'Lâmpada',
        'lightning-bolt' => 'Raio',
        'location-marker' => 'Localização',
        'lock-closed' => 'Cadeado',
        'map' => 'Mapa',
        'moon' => 'Lua',
        'star' => 'Estrela',
        'truck' => 'Caminhão',
        'video-camera' => 'Filmadora',
        'view-grid' => 'Grade'
    ];

    public function mount($features = null)
    {
        if (is_array($features) && !empty($features)) {
            $this->features = $features;
        } else {
            // Add one empty feature by default
            $this->addFeature();
        }
    }

    public function addFeature()
    {
        $this->features[] = [
            'name' => '',
            'icon' => 'home'
        ];
    }

    public function removeFeature($index)
    {
        if (count($this->features) > 1 || (count($this->features) === 1 && empty($this->features[0]['name']))) {
            unset($this->features[$index]);
            // Re-index the array
            $this->features = array_values($this->features);
        }
    }

    public function render()
    {
        return view('livewire.property-features-repeater');
    }
}
