/**
 * Funcionalidade de Arrastar e Soltar do Quadro Kanban
 */

// Função global para abrir o modal de tarefa
function openTaskModal(action, data = '') {
    const taskModal = document.getElementById('task-modal');
    if (!taskModal) {
        console.error('Elemento modal de tarefa não encontrado');
        return;
    }
    
    const modalTitle = document.getElementById('modal-title');
    const deleteButton = document.getElementById('delete-task');
    const taskForm = document.getElementById('task-form');
    
    if (!modalTitle || !taskForm) {
        console.error('Elementos obrigatórios do modal não encontrados');
        return;
    }
    
    // Resetar o formulário
    taskForm.reset();
    
    if (action === 'create') {
        // Definir título do modal
        modalTitle.textContent = 'Adicionar Nova Tarefa';
        
        // Definir status com padrão alternativo
        const statusInput = document.getElementById('task-status');
        if (statusInput) {
            // Se data for fornecido e for uma string, usar como status
            // Caso contrário, padrão para 'todo'
            statusInput.value = (data && typeof data === 'string') ? data : 'todo';
            console.log('Definindo status para:', statusInput.value);
        }
        
        // Ocultar botão de exclusão
        if (deleteButton) {
            deleteButton.classList.add('hidden');
        }
        
        // Limpar ID da tarefa
        const taskIdInput = document.getElementById('task-id');
        if (taskIdInput) {
            taskIdInput.value = '';
        }
    } 
    else if (action === 'edit' && data) {
        // Definir título do modal
        modalTitle.textContent = 'Editar Tarefa';
        
        // Mostrar botão de exclusão
        if (deleteButton) {
            deleteButton.classList.remove('hidden');
        }
        
        // Definir ID da tarefa
        const taskIdInput = document.getElementById('task-id');
        if (taskIdInput) {
            taskIdInput.value = data;
        }
        
        // Buscar dados da tarefa do backend
        const taskId = data;
        const isAdmin = window.location.pathname.includes('/admin/');
        const baseUrl = isAdmin ? '/admin' : '/client';
        
        fetch(`${baseUrl}/kanban/tasks/${taskId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Falha ao buscar dados da tarefa');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.task) {
                    const task = data.task;
                    
                    // Preencher o formulário com dados da tarefa
                    document.getElementById('title').value = task.title || '';
                    document.getElementById('description').value = task.description || '';
                    document.getElementById('priority').value = task.priority || 'medium';
                    
                    const statusInput = document.getElementById('task-status');
                    if (statusInput) {
                        statusInput.value = task.status || 'todo';
                    }
                    
                    // Formatar data para YYYY-MM-DD para input[type=date]
                    if (task.due_date) {
                        const dueDate = new Date(task.due_date);
                        const formattedDate = dueDate.toISOString().split('T')[0];
                        document.getElementById('due_date').value = formattedDate;
                    } else {
                        document.getElementById('due_date').value = '';
                    }
                }
            })
            .catch(error => {
                console.error('Erro ao buscar tarefa:', error);
                try {
                    showNotification('Erro ao buscar dados da tarefa', 'error');
                } catch (e) {
                    console.error('Erro ao exibir notificação:', e);
                }
            });
    }
    
    // Exibir o modal
    taskModal.style.display = 'block';
}

// Função global para fechar o modal de tarefa
function closeTaskModal() {
    const taskModal = document.getElementById('task-modal');
    if (taskModal) {
        taskModal.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    
    // Inicializar Sortable.js para listas de tarefas
    const taskLists = document.querySelectorAll('.task-list');
    if (taskLists.length > 0) {
        console.log(`Inicializando Sortable para ${taskLists.length} listas de tarefas`);
        taskLists.forEach(list => {
            console.log(`Lista: ${list.id}, Status: ${list.dataset.status}`);
        });
        initSortable(taskLists, 'tasks', updateTaskStatus);
    } else {
        console.error('Nenhuma lista de tarefas encontrada. Certifique-se de que os elementos têm a classe task-list');
    }
    
    // Inicializar Sortable.js para listas de reuniões
    const meetingLists = document.querySelectorAll('.meeting-list');
    if (meetingLists.length > 0) {
        initSortable(meetingLists, 'meetings', updateMeetingStatus);
    }
    
    // Inicializar os modais e botões de ação
    initTaskModals();
    initMeetingModals();
    
    /**
     * Inicializa o Sortable.js para as listas fornecidas
     */
    function initSortable(lists, groupName, updateCallback) {
        lists.forEach(list => {
            new Sortable(list, {
                group: groupName,
                animation: 150,
                ghostClass: 'bg-gray-200',
                dragClass: 'sortable-drag',
                chosenClass: 'sortable-chosen',
                onStart: function(evt) {
                    console.log(`Iniciando arrasto do item ${evt.item.dataset.id} da coluna ${evt.from.dataset.status}`);
                },
                onEnd: function(evt) {
                    console.log(`Terminando arrasto do item ${evt.item.dataset.id}`);
                    console.log(`De: ${evt.from.dataset.status}, Para: ${evt.to.dataset.status}`);
                    
                    if (evt.from !== evt.to) {
                        const itemId = evt.item.dataset.id;
                        const newStatus = evt.to.dataset.status;
                        
                        if (!newStatus) {
                            console.error(`Erro: Coluna destino (${evt.to.id}) não tem atributo data-status`);
                            showNotification('Erro ao mover tarefa: configuração da coluna incorreta', 'error');
                            return;
                        }
                        
                        // Adicionar classe de transição para feedback visual
                        evt.item.classList.add('transition-all', 'duration-300', 'bg-yellow-50');
                        
                        // Remover a classe após a transição
                        setTimeout(() => {
                            evt.item.classList.remove('transition-all', 'duration-300', 'bg-yellow-50');
                        }, 300);
                        
                        // Atualizar status via API
                        console.log(`Atualizando tarefa ${itemId} para status ${newStatus}`);
                        updateCallback(itemId, newStatus);
                    }
                }
            });
        });
    }
    
    /**
     * Atualiza o status de uma tarefa via AJAX
     */
    function updateTaskStatus(taskId, status) {
        const isAdmin = window.location.pathname.includes('/admin/');
        const baseUrl = isAdmin ? '/admin' : '/client';
        
        fetch(`${baseUrl}/kanban/tasks/${taskId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Falha ao atualizar a tarefa');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Atualizar contadores de tarefas (opcional)
                updateCounters();
                
                // Feedback visual de sucesso (opcional)
                showNotification('Tarefa atualizada com sucesso!', 'success');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showNotification('Erro ao atualizar tarefa. Recarregando a página...', 'error');
            
            // Em caso de erro, recarregar a página para garantir sincronização
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        });
    }
    
    /**
     * Atualiza o status de uma reunião via AJAX
     */
    function updateMeetingStatus(meetingId, status) {
        const isAdmin = window.location.pathname.includes('/admin/');
        const baseUrl = isAdmin ? '/admin' : '/client';
        
        let data = { status: status };
        
        // Para reuniões completadas ou canceladas, podemos adicionar um prompt para outcome ou motivo de cancelamento
        if (status === 'completed' || status === 'cancelled') {
            const reason = status === 'completed' 
                ? prompt('Registre o resultado da reunião (opcional):') 
                : prompt('Motivo do cancelamento (opcional):');
                
            if (status === 'completed') {
                data.outcome = reason || '';
            } else {
                data.cancellation_reason = reason || '';
            }
        }
        
        fetch(`${baseUrl}/kanban/meetings/${meetingId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Falha ao atualizar a reunião');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Atualizar contadores (opcional)
                updateCounters();
                
                // Feedback visual de sucesso
                showNotification('Reunião atualizada com sucesso!', 'success');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showNotification('Erro ao atualizar reunião. Recarregando a página...', 'error');
            
            // Em caso de erro, recarregar a página para garantir sincronização
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        });
    }
    
    /**
     * Inicializa os modais e botões relacionados às tarefas
     */
    function initTaskModals() {
        // Inicializar apenas se estivermos na página de tarefas
        if (!document.getElementById('taskModal')) return;
        
        const taskModal = document.getElementById('taskModal');
        const openTaskModal = document.getElementById('openTaskModal');
        const closeTaskModal = document.getElementById('closeTaskModal');
        const taskForm = document.getElementById('taskForm');
        const confirmDeleteModal = document.getElementById('confirmDeleteModal');
        const cancelDeleteModal = document.getElementById('cancelDeleteModal');
        
        // Abrir modal para nova tarefa
        if (openTaskModal) {
            openTaskModal.addEventListener('click', function() {
                taskForm.reset();
                const isAdmin = window.location.pathname.includes('/admin/');
                const baseUrl = isAdmin ? '/admin' : '/client';
                taskForm.action = `${baseUrl}/kanban/tasks`;
                document.getElementById('method').value = 'POST';
                document.getElementById('taskId').value = '';
                
                // Exibir modal
                taskModal.classList.remove('hidden');
            });
        }
        
        // Fechar modal
        if (closeTaskModal) {
            closeTaskModal.addEventListener('click', function() {
                taskModal.classList.add('hidden');
            });
        }
        
        // Preparar edição de tarefa
        document.querySelectorAll('.edit-task-btn').forEach(button => {
            button.addEventListener('click', function() {
                const taskId = this.dataset.id;
                
                // Preencher o formulário com dados do dataset
                document.getElementById('taskId').value = taskId;
                document.getElementById('title').value = this.dataset.title;
                document.getElementById('description').value = this.dataset.description || '';
                document.getElementById('priority').value = this.dataset.priority;
                document.getElementById('status').value = this.dataset.status;
                document.getElementById('person_id').value = this.dataset.personId || '';
                document.getElementById('due_date').value = this.dataset.dueDate || '';
                
                // Configurar formulário para edição
                const isAdmin = window.location.pathname.includes('/admin/');
                const baseUrl = isAdmin ? '/admin' : '/client';
                taskForm.action = `${baseUrl}/kanban/tasks/${taskId}`;
                document.getElementById('method').value = 'PUT';
                
                // Exibir modal
                taskModal.classList.remove('hidden');
            });
        });
        
        // Preparar exclusão de tarefa
        document.querySelectorAll('.delete-task-btn').forEach(button => {
            button.addEventListener('click', function() {
                const taskId = this.dataset.id;
                const isAdmin = window.location.pathname.includes('/admin/');
                const baseUrl = isAdmin ? '/admin' : '/client';
                document.getElementById('deleteTaskForm').action = `${baseUrl}/kanban/tasks/${taskId}`;
                confirmDeleteModal.classList.remove('hidden');
            });
        });
        
        // Cancelar exclusão
        if (cancelDeleteModal) {
            cancelDeleteModal.addEventListener('click', function() {
                confirmDeleteModal.classList.add('hidden');
            });
        }
    }
    
    // Adicionar event listener para submissão do formulário de tarefa
    const taskForm = document.getElementById('task-form');
    if (taskForm) {
        taskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Obter dados do formulário
            const formData = new FormData(this);
            const taskIdElement = document.getElementById('task-id');
            const taskStatusElement = document.getElementById('task-status');
            
            if (!taskIdElement) {
                console.error('Elemento ID da tarefa não encontrado');
                showNotification('Erro: Elementos do formulário ausentes', 'error');
                return;
            }
            
            // Logs de depuração mais detalhados
            console.log('Submissão do formulário - ID da Tarefa:', taskIdElement.value);
            
            if (!taskStatusElement) {
                console.error('Elemento Status da tarefa não encontrado no DOM');
                showNotification('Erro: Campo de status ausente no formulário', 'error');
                return;
            }
            
            console.log('Valor do Status da Tarefa:', taskStatusElement.value);
            
            const taskId = taskIdElement.value;
            const method = taskId ? 'PUT' : 'POST';
            const isAdmin = window.location.pathname.includes('/admin/');
            const baseUrl = isAdmin ? '/admin' : '/client';
            const url = taskId ? `${baseUrl}/kanban/tasks/${taskId}` : `${baseUrl}/kanban/tasks`;
            
            // Incluir token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('Token CSRF não encontrado');
                showNotification('Erro: Token CSRF ausente', 'error');
                return;
            }
            
            formData.append('_token', csrfToken.getAttribute('content'));
            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }
            
            // Garantir que o campo status esteja incluído - correção importante para o erro de validação
            if (!taskStatusElement.value) {
                console.error('Campo de status está vazio - definindo status padrão "todo"');
                formData.set('status', 'todo'); // Padrão para 'todo' se vazio
            } else {
                console.log('Usando status fornecido:', taskStatusElement.value);
                formData.set('status', taskStatusElement.value);
            }
            
            // Adicionar user_id do usuário autenticado se não estiver presente
            // Como não temos acesso direto ao ID do usuário logado no JavaScript,
            // vamos buscar do meta tag que será adicionado no layout
            const userIdMeta = document.querySelector('meta[name="user-id"]');
            if (userIdMeta) {
                const userId = userIdMeta.getAttribute('content');
                if (userId) {
                    console.log('Adicionando user_id:', userId);
                    formData.append('user_id', userId);
                }
            }
            
            // Debug: registrar todos os dados do formulário
            console.log('Dados do formulário sendo enviados:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            // Enviar requisição AJAX
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    // Obter código de status específico para melhor tratamento de erro
                    const statusCode = response.status;
                    let errorMessage = 'Falha ao salvar tarefa';
                    
                    // Tratar códigos de status específicos
                    if (statusCode === 500) {
                        errorMessage = 'Erro interno do servidor (500). Contate o administrador.';
                        console.error('Erro de servidor 500 ao salvar tarefa');
                    } else if (statusCode === 422) {
                        errorMessage = 'Dados inválidos. Verifique os campos do formulário.';
                    } else if (statusCode === 401 || statusCode === 403) {
                        // Melhorar mensagem para o erro 403 relacionado ao perfil de pessoa
                        errorMessage = 'Não autorizado a realizar esta ação.';
                    }
                    
                    // Tentar analisar a resposta de erro JSON se disponível
                    return response.text().then(text => {
                        try {
                            const errorData = JSON.parse(text);
                            if (errorData.message) {
                                // Verificar mensagem específica de perfil de pessoa
                                if (errorData.message.includes('person profile')) {
                                    errorMessage = 'Para criar tarefas, você precisa ter um perfil de pessoa associado à sua conta. Por favor, contate um administrador.';
                                } else {
                                    errorMessage = errorData.message;
                                }
                            }
                            if (errorData.errors) {
                                // Formatar erros de validação se disponíveis
                                const validationErrors = Object.values(errorData.errors).flat().join(', ');
                                errorMessage = `Erro de validação: ${validationErrors}`;
                            }
                        } catch (e) {
                            // Se não for JSON válido, use o texto como mensagem de erro se não for muito longo
                            if (text && text.length < 100) {
                                errorMessage = text;
                            }
                        }
                        throw new Error(errorMessage);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Fechar modal
                    closeTaskModal();
                    
                    // Mostrar notificação de sucesso
                    try {
                        showNotification('Tarefa salva com sucesso!', 'success');
                    } catch (e) {
                        console.error('Erro ao exibir notificação de sucesso:', e);
                    }
                    
                    // Recarregar página para refletir as alterações
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else if (data.message) {
                    // Tratar respostas de não-sucesso que possam ter uma mensagem
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                try {
                    // Mostrar a mensagem de erro específica para o usuário
                    showNotification(error.message || 'Erro ao salvar tarefa. Tente novamente.', 'error');
                } catch (e) {
                    console.error('Erro ao exibir notificação:', e);
                    // Alerta alternativo se a notificação falhar
                    alert('Erro ao salvar tarefa: ' + (error.message || 'Erro desconhecido'));
                }
            });
        });
    }
    
    // Adicionar event listener para o botão de exclusão
    const deleteTaskBtn = document.getElementById('delete-task');
    if (deleteTaskBtn) {
        deleteTaskBtn.addEventListener('click', function() {
            if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
                const taskIdElement = document.getElementById('task-id');
                
                if (!taskIdElement) {
                    console.error('Elemento ID da tarefa não encontrado');
                    try {
                        showNotification('Erro: ID da tarefa ausente', 'error');
                    } catch (e) {
                        console.error('Erro ao exibir notificação:', e);
                    }
                    return;
                }
                
                const taskId = taskIdElement.value;
                if (!taskId) {
                    try {
                        showNotification('Erro: Nenhuma tarefa selecionada', 'error');
                    } catch (e) {
                        console.error('Erro ao exibir notificação:', e);
                    }
                    return;
                }
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error('Token CSRF não encontrado');
                    try {
                        showNotification('Erro: Token CSRF ausente', 'error');
                    } catch (e) {
                        console.error('Erro ao exibir notificação:', e);
                    }
                    return;
                }
                
                const isAdmin = window.location.pathname.includes('/admin/');
                const baseUrl = isAdmin ? '/admin' : '/client';
                
                fetch(`${baseUrl}/kanban/tasks/${taskId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        // Obter código de status específico para melhor tratamento de erro
                        const statusCode = response.status;
                        let errorMessage = 'Falha ao excluir tarefa';
                        
                        // Tratar códigos de status específicos
                        if (statusCode === 500) {
                            errorMessage = 'Erro interno do servidor (500). Contate o administrador.';
                        } else if (statusCode === 404) {
                            errorMessage = 'Tarefa não encontrada.';
                        } else if (statusCode === 401 || statusCode === 403) {
                            errorMessage = 'Não autorizado a realizar esta ação.';
                        }
                        
                        // Tentar analisar a resposta de erro JSON se disponível
                        return response.text().then(text => {
                            try {
                                const errorData = JSON.parse(text);
                                if (errorData.message) {
                                    errorMessage = errorData.message;
                                }
                            } catch (e) {
                                // Se não for JSON válido, use o texto como mensagem de erro se não for muito longo
                                if (text && text.length < 100) {
                                    errorMessage = text;
                                }
                            }
                            throw new Error(errorMessage);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Fechar modal
                        closeTaskModal();
                        
                        // Mostrar notificação de sucesso
                        try {
                            showNotification('Tarefa excluída com sucesso!', 'success');
                        } catch (e) {
                            console.error('Erro ao exibir notificação:', e);
                        }
                        
                        // Recarregar página para refletir as alterações
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    try {
                        showNotification(error.message || 'Erro ao excluir tarefa. Tente novamente.', 'error');
                    } catch (e) {
                        console.error('Erro ao exibir notificação:', e);
                        // Alerta alternativo se a notificação falhar
                        alert('Erro ao excluir tarefa: ' + (error.message || 'Erro desconhecido'));
                    }
                });
            }
        });
    }
    
    /**
     * Inicializa os modais e botões relacionados às reuniões
     */
    function initMeetingModals() {
        // Implementar funcionalidade similar à initTaskModals para reuniões
        // Este código seria muito similar, adaptado para meetingModal, etc.
    }
    
    /**
     * Atualiza os contadores em cada coluna do Kanban
     */
    function updateCounters() {
        // Atualizar contadores de tarefas
        document.querySelectorAll('.kanban-column').forEach(column => {
            const statusContainer = column.querySelector('h3 span:last-child');
            const count = column.querySelectorAll('.task-card, .meeting-card').length;
            
            if (statusContainer) {
                statusContainer.textContent = count;
            }
        });
    }
    
    /**
     * Exibe uma notificação temporária
     */
    function showNotification(message, type = 'info') {
        try {
            // Use o container de toast se existir, caso contrário, volte para o body
            const toastContainer = document.getElementById('toast-container') || document.body;
            if (!toastContainer) {
                console.error('Nenhum container adequado encontrado para notificação');
                return;
            }
            
            // Se já existe uma notificação, remova-a
            const existingNotification = document.getElementById('kanban-notification');
            if (existingNotification && existingNotification.parentNode) {
                existingNotification.parentNode.removeChild(existingNotification);
            }
            
            // Criar elemento de notificação
            const notification = document.createElement('div');
            notification.id = 'kanban-notification';
            notification.classList.add('px-4', 'py-2', 'rounded-lg', 'shadow-lg', 'mb-2', 'transition-opacity', 'duration-300');
            
            // Definir estilo baseado no tipo
            if (type === 'success') {
                notification.classList.add('bg-green-500', 'text-white');
            } else if (type === 'error') {
                notification.classList.add('bg-red-500', 'text-white');
            } else {
                notification.classList.add('bg-blue-500', 'text-white');
            }
            
            notification.textContent = message;
            
            // Adicionar ao container
            toastContainer.appendChild(notification);
            
            // Remover após 3 segundos
            setTimeout(() => {
                notification.classList.add('opacity-0');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        } catch (error) {
            console.error('Erro ao mostrar notificação:', error);
        }
    }
}); 