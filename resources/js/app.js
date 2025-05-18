import './bootstrap';
import Alpine from 'alpinejs';
import './navigation'; // Importar script de navegação suave
import '../css/app.css';
window.Alpine = Alpine;
Alpine.start();

// Script para melhorar a navegação e prevenir o piscar da tela
document.addEventListener('DOMContentLoaded', function() {
    // Armazenar a posição de rolagem para cada página
    let scrollPositions = {};
    let lastPath = window.location.pathname;
    
    // Tentar restaurar posições de rolagem do sessionStorage
    try {
        const savedPositions = sessionStorage.getItem('scrollPositions');
        if (savedPositions) {
            scrollPositions = JSON.parse(savedPositions);
            
            // Restaurar posição de rolagem na página atual
            if (scrollPositions[window.location.pathname]) {
                window.scrollTo(0, scrollPositions[window.location.pathname]);
            }
        }
    } catch (e) {
        console.error('Erro ao restaurar posições de rolagem:', e);
    }
    
    // Adicionar indicador de navegação
    const indicator = document.createElement('div');
    indicator.className = 'navigation-indicator';
    document.body.appendChild(indicator);
    
    // Adicionar estilos inline para o indicador
    const style = document.createElement('style');
    style.textContent = `
        .navigation-indicator {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 0;
            background: #4f46e5;
            z-index: 9999;
            transition: width 0.3s ease-out;
        }
        
        .navigating .navigation-indicator {
            width: 100%;
        }
        
        a, button {
            transition: all 0.2s ease-in-out;
        }
        
        main {
            transition: opacity 0.15s ease-in-out;
        }
    `;
    document.head.appendChild(style);
    
    // Salvar posição de rolagem ao navegar
    window.addEventListener('beforeunload', function() {
        scrollPositions[window.location.pathname] = window.scrollY;
        sessionStorage.setItem('scrollPositions', JSON.stringify(scrollPositions));
        sessionStorage.setItem('lastPath', lastPath);
    });
    
    // Interceptar cliques em links
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        
        if (!link) return;
        
        // Ignorar links especiais
        if (!link.href || 
            !link.href.startsWith(window.location.origin) ||
            link.target === '_blank' ||
            link.hasAttribute('download') ||
            link.getAttribute('rel') === 'external' ||
            e.ctrlKey || e.metaKey || e.shiftKey) {
            return;
        }
        
        // Se for o mesmo caminho atual, prevenir navegação
        const url = new URL(link.href);
        const currentPath = window.location.pathname;
        
        if (url.pathname === currentPath) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return;
        }
        
        // Antes de navegar para uma nova página
        scrollPositions[currentPath] = window.scrollY;
        sessionStorage.setItem('scrollPositions', JSON.stringify(scrollPositions));
        lastPath = currentPath;
        
        // Mostrar indicador de navegação
        document.body.classList.add('navigating');
    });
});
