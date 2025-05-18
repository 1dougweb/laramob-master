<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Property routes
Route::prefix('properties')->name('properties.')->group(function () {
    Route::get('/', [PropertyController::class, 'index'])->name('index');
    Route::get('/{slug}', [PropertyController::class, 'show'])->name('show');
    Route::post('/{id}/contact', [PropertyController::class, 'contact'])->name('contact');
});

// Authentication routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('client.dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Página de cliente
    Route::get('/client', function () {
        return view('client');
    })->name('client');
});

// Rotas do administrador
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    // Rotas de propriedades
    Route::resource('properties', AdminPropertyController::class);
    Route::delete('property-images/{id}', [AdminPropertyController::class, 'destroyImage'])->name('property-images.destroy');
    // Rotas de tipos de propriedades
    Route::resource('property-types', \App\Http\Controllers\Admin\PropertyTypeController::class);
    // Rotas de Cidades
    Route::resource('cities', \App\Http\Controllers\Admin\CityController::class);
    // Rotas de bairros
    Route::resource('districts', \App\Http\Controllers\Admin\DistrictController::class);
    // Gestão de pessoas
    Route::resource('people', \App\Http\Controllers\Admin\PersonController::class);
    // Manuseio de contatos
    Route::resource('contacts', \App\Http\Controllers\Admin\ContactController::class);
    // Manuseio de contratos
    Route::resource('contracts', \App\Http\Controllers\Admin\ContractController::class);
    // Manuseio de contas
    Route::resource('bank-accounts', \App\Http\Controllers\Admin\BankAccountController::class);
    // Manuseio de transações
    Route::resource('transactions', \App\Http\Controllers\Admin\TransactionController::class);
    // Manuseio de comissões
    Route::resource('commissions', \App\Http\Controllers\Admin\CommissionController::class);
    
    // Contas a receber
    Route::resource('accounts-receivable', \App\Http\Controllers\Admin\AccountsReceivableController::class);
    Route::post('accounts-receivable/{id}/register-payment', [\App\Http\Controllers\Admin\AccountsReceivableController::class, 'registerPayment'])->name('accounts-receivable.register-payment');
    
    // Contas a pagar
    Route::resource('accounts-payable', \App\Http\Controllers\Admin\AccountsPayableController::class);
    Route::post('accounts-payable/{id}/register-payment', [\App\Http\Controllers\Admin\AccountsPayableController::class, 'registerPayment'])->name('accounts-payable.register-payment');
    
    // Manuseio de documentos
    Route::prefix('people/{person}')->name('people.')->group(function () {
        Route::resource('documents', \App\Http\Controllers\Admin\DocumentController::class);
        Route::get('documents/{document}/download', [\App\Http\Controllers\Admin\DocumentController::class, 'download'])->name('documents.download');
        Route::put('documents/{document}/share', [\App\Http\Controllers\Admin\DocumentController::class, 'share'])->name('documents.share');
        Route::put('documents/{document}/unshare', [\App\Http\Controllers\Admin\DocumentController::class, 'unshare'])->name('documents.unshare');
    });

    // Kanban para administradores
    Route::get('/kanban', [App\Http\Controllers\Admin\KanbanController::class, 'index'])->name('kanban.index');
    Route::get('/kanban/tasks', [App\Http\Controllers\Admin\TaskController::class, 'index'])->name('kanban.tasks');
    Route::get('/kanban/meetings', [App\Http\Controllers\Admin\MeetingController::class, 'index'])->name('kanban.meetings');
    Route::get('/kanban/tasks/{task}', [App\Http\Controllers\Admin\TaskController::class, 'getTask'])->name('kanban.tasks.show');
    Route::post('/kanban/tasks', [App\Http\Controllers\Admin\TaskController::class, 'storeTask'])->name('kanban.tasks.store');
    Route::put('/kanban/tasks/{task}', [App\Http\Controllers\Admin\TaskController::class, 'updateTask'])->name('kanban.tasks.update');
    Route::delete('/kanban/tasks/{task}', [App\Http\Controllers\Admin\TaskController::class, 'destroyTask'])->name('kanban.tasks.destroy');
    Route::patch('/kanban/tasks/{task}/status', [App\Http\Controllers\Admin\TaskController::class, 'updateTaskStatus'])->name('kanban.tasks.update-status');
    
    // Reuniões do Kanban
    Route::post('/kanban/meetings', [App\Http\Controllers\Admin\MeetingController::class, 'storeMeeting'])->name('kanban.meetings.store');
    Route::put('/kanban/meetings/{meeting}', [App\Http\Controllers\Admin\MeetingController::class, 'updateMeeting'])->name('kanban.meetings.update');
    Route::delete('/kanban/meetings/{meeting}', [App\Http\Controllers\Admin\MeetingController::class, 'destroyMeeting'])->name('kanban.meetings.destroy');
    Route::patch('/kanban/meetings/{meeting}/status', [App\Http\Controllers\Admin\MeetingController::class, 'updateMeetingStatus'])->name('kanban.meetings.update-status');

    // Blog management routes
    Route::resource('blog-categories', \App\Http\Controllers\Admin\BlogCategoryController::class);
    Route::post('blog-categories/reorder', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'reorder'])->name('blog-categories.reorder');
    
    Route::resource('blog-posts', \App\Http\Controllers\Admin\BlogPostController::class);
    
    Route::resource('blog-comments', \App\Http\Controllers\Admin\BlogCommentController::class)->except(['create', 'store']);
    Route::post('blog-comments/{id}/toggle-approval', [\App\Http\Controllers\Admin\BlogCommentController::class, 'toggleApproval'])->name('blog-comments.toggle-approval');
    Route::post('blog-comments/approve-multiple', [\App\Http\Controllers\Admin\BlogCommentController::class, 'approveMultiple'])->name('blog-comments.approve-multiple');
    Route::post('blog-comments/delete-multiple', [\App\Http\Controllers\Admin\BlogCommentController::class, 'deleteMultiple'])->name('blog-comments.delete-multiple');

    // Configurações do sistema
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/general', [App\Http\Controllers\Admin\SettingsController::class, 'general'])->name('general');
        Route::get('/email', [App\Http\Controllers\Admin\SettingsController::class, 'email'])->name('email');
        Route::get('/seo', [App\Http\Controllers\Admin\SettingsController::class, 'seo'])->name('seo');
        Route::get('/security', [App\Http\Controllers\Admin\SettingsController::class, 'security'])->name('security');
        Route::get('/styles', [App\Http\Controllers\Admin\SettingsController::class, 'styles'])->name('styles');
        Route::post('/save', [App\Http\Controllers\Admin\SettingsController::class, 'save'])->name('save');
        Route::post('/styles/save', [App\Http\Controllers\Admin\SettingsController::class, 'saveStyles'])->name('styles.save');
    });
});

// Rotas do cliente
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');
    // Documentos
    Route::get('/documents', [App\Http\Controllers\Client\DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}', [App\Http\Controllers\Client\DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/download', [App\Http\Controllers\Client\DocumentController::class, 'download'])->name('documents.download');
    // Properties
    Route::get('/properties', [App\Http\Controllers\Client\PropertyController::class, 'index'])->name('properties.index');
    Route::get('/properties/{slug}', [App\Http\Controllers\Client\PropertyController::class, 'show'])->name('properties.show');
    Route::post('/properties/{id}/contact', [App\Http\Controllers\Client\PropertyController::class, 'contact'])->name('properties.contact');
    Route::post('/properties/{id}/favorite', [App\Http\Controllers\Client\PropertyController::class, 'toggleFavorite'])->name('properties.toggle-favorite');
    Route::get('/favorites', [App\Http\Controllers\Client\PropertyController::class, 'favorites'])->name('properties.favorites');    
    // Rota de teste
    Route::get('/test-route', function() {
        return "Esta é uma rota de teste!";
    });
});

require __DIR__.'/auth.php';

// Rota de teste para favoritos (sem grupo)
Route::get('/test-favorites', [App\Http\Controllers\Client\PropertyController::class, 'favorites'])->name('test.favorites');

// Blog routes
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [App\Http\Controllers\BlogController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [App\Http\Controllers\BlogController::class, 'category'])->name('category');
    Route::get('/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('show');
    Route::post('/{id}/comment', [App\Http\Controllers\BlogController::class, 'storeComment'])->name('comment.store');
});

// Rota para estilos dinâmicos (acessível sem autenticação)
Route::get('/dynamic-styles.css', [\App\Http\Controllers\Admin\SettingsController::class, 'dynamicStyles'])->name('dynamic.styles');
