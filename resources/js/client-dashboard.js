// Script para processar dados no dashboard do cliente
document.addEventListener('DOMContentLoaded', function() {
    // Função para detectar e corrigir dados JSON
    function fixJsonInElement(element) {
        if (!element) return;
        
        const text = element.textContent.trim();
        
        // Verifica se o conteúdo começa com [ ou { (possível JSON)
        if ((text.startsWith('[') && text.endsWith(']')) || 
            (text.startsWith('{') && text.endsWith('}'))) {
            
            try {
                // Tenta analisar como JSON
                const jsonData = JSON.parse(text);
                
                // Se for um array de tarefas
                if (Array.isArray(jsonData)) {
                    // Exibe o número de tarefas ao invés do JSON
                    element.textContent = jsonData.length;
                    
                    // Atualiza o texto secundário, se existir
                    const infoElem = element.closest('.bg-blue-50')?.querySelector('.text-sm.text-blue-600');
                    if (infoElem) {
                        const todoCount = jsonData.filter(task => task.status === 'todo').length;
                        const inProgressCount = jsonData.filter(task => task.status === 'in_progress').length;
                        
                        if (todoCount > 0 || inProgressCount > 0) {
                            infoElem.textContent = `${todoCount} para fazer | ${inProgressCount} em progresso`;
                        } else {
                            infoElem.textContent = 'Nenhuma tarefa pendente';
                        }
                    }
                    
                    console.log('Dados JSON corrigidos:', jsonData);
                }
            } catch (e) {
                console.error('Erro ao processar possível JSON:', e);
            }
        }
    }
    
    // Procura por todos os elementos de contagem numérica no resumo de atividades
    const counterElements = document.querySelectorAll('.bg-white .grid-cols-4 .text-3xl');
    counterElements.forEach(fixJsonInElement);
    
    // Procura especificamente pelo elemento de tarefas pendentes
    const pendingTasksElement = document.querySelector('.bg-blue-50 .text-3xl');
    if (pendingTasksElement) {
        fixJsonInElement(pendingTasksElement);
    }
    
    // Procura por elementos de texto que podem conter JSON
    const textElements = document.querySelectorAll('.text-sm.text-blue-600');
    textElements.forEach(element => {
        const text = element.textContent.trim();
        
        // Verifica se o texto contém um padrão que parece JSON
        if (text.includes('[{') || text.includes('"}]')) {
            try {
                // Extrai a parte JSON
                const jsonStart = text.indexOf('[');
                const jsonEnd = text.lastIndexOf(']') + 1;
                
                if (jsonStart >= 0 && jsonEnd > jsonStart) {
                    const jsonText = text.substring(jsonStart, jsonEnd);
                    const jsonData = JSON.parse(jsonText);
                    
                    // Substitui o JSON por uma contagem simples
                    const todoCount = jsonData.filter(task => task.status === 'todo').length;
                    const inProgressCount = jsonData.filter(task => task.status === 'in_progress').length;
                    
                    element.textContent = `${todoCount} para fazer | ${inProgressCount} em progresso`;
                }
            } catch (e) {
                console.warn('Erro ao extrair JSON do texto:', e);
            }
        }
    });
}); 