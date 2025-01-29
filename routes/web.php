<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\ThemeManagerController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('registerSave');
Route::post('/login', [AuthController::class, 'login'])->name('loginSubmit');

Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');





// Guest Routes
Route::get('/', [GuestController::class, 'index'])->name('home');
Route::get('/article/{id}', [SubscriberController::class, 'showArticle'])->name('subscriber.article');
Route::get('/themes', [SubscriberController::class, 'viewThemes'])->name('subscriber.themes');


// Subscriber Routes
Route::prefix('subscriber')->middleware(['auth', 'role:subscriber'])->group(function () {
    Route::get('/dashboard', [SubscriberController::class, 'dashboard'])->name('subscriber.dashboard');
    Route::post('/subscribe', [SubscriberController::class, 'subscribeRequest'])->name('subscriber.subscribe');
    Route::post('/unsubscribe', [SubscriberController::class, 'unsubscribeRequest'])->name('subscriber.unsubscribe');
    Route::post('/cancel', [SubscriberController::class, 'cancelRequest'])->name('subscriber.cancel');
    Route::get('/history', [SubscriberController::class, 'viewHistory'])->name('subscriber.history');
	Route::post('/articles/rate', [SubscriberController::class, 'rateArticle'])->name('subscriber.rate');
    Route::get('/my-articles', [SubscriberController::class, 'viewMyArticles'])->name('subscriber.myArticles');
    Route::get('/my-articles/{id}', [SubscriberController::class, 'viewProposedArticle'])->name('subscriber.viewArticle');
    Route::get('/propose-article', [SubscriberController::class, 'showProposeArticleForm'])->name('subscriber.proposeArticle');
    Route::post('/propose-article', [SubscriberController::class, 'submitProposeArticle'])->name('subscriber.submitArticle');
    Route::post('/article/{id}/chat', [SubscriberController::class, 'storeMessage'])->name('subscriber.chat.store');
    Route::post('/subscriber/chat/{id}/delete', [SubscriberController::class, 'deleteMessage'])->name('subscriber.chat.delete');
});

// Theme Manager Routes
Route::prefix('theme-manager')->middleware(['auth', 'role:theme_manager'])->group(function () {
    Route::get('/theme-manager', [ThemeManagerController::class, 'dashboard'])->name('theme_manager.dashboard');

    Route::get('/themes', [ThemeManagerController::class, 'moderateThemes'])->name('theme_manager.themes');
    Route::post('/themes/{theme}/subscriptions/{subscription}/accept', [ThemeManagerController::class, 'acceptSubscription'])->name('theme_manager.subscriptions.accept');
    Route::post('/themes/{theme}/subscriptions/{subscription}/reject', [ThemeManagerController::class, 'rejectSubscription'])->name('theme_manager.subscriptions.reject');
    Route::get('/themes/{theme}/edit', [ThemeManagerController::class, 'editTheme'])->name('theme_manager.themes.edit');
    Route::post('/themes/{theme}/edit', [ThemeManagerController::class, 'updateTheme'])->name('theme_manager.themes.update');

    Route::get('/articles', [ThemeManagerController::class, 'moderateArticles'])->name('theme_manager.articles');
    Route::get('/articles/create', [ThemeManagerController::class, 'createArticle'])->name('theme_manager.articles.create');
    Route::post('/articles/create', [ThemeManagerController::class, 'storeArticle'])->name('theme_manager.articles.store');
    Route::get('/articles/{article}/edit', [ThemeManagerController::class, 'editArticle'])->name('theme_manager.articles.edit');
    Route::post('/articles/{article}/edit', [ThemeManagerController::class, 'updateArticle'])->name('theme_manager.articles.update');
    Route::post('/articles/{article}/delete', [ThemeManagerController::class, 'deleteArticle'])->name('theme_manager.articles.delete');
    Route::post('/article/chat/{id}/delete', [ThemeManagerController::class, 'deleteMessage'])->name('theme_manager.chat.delete');

    Route::get('/proposed-articles', [ThemeManagerController::class, 'proposedArticles'])->name('theme_manager.proposed_articles');
    Route::get('/proposed-articles/{article}', [ThemeManagerController::class, 'viewProposedArticle'])->name('theme_manager.proposed_articles.view');
    Route::post('/proposed-articles/{article}/accept', [ThemeManagerController::class, 'acceptProposedArticle'])->name('theme_manager.proposed_articles.accept');
    Route::post('/proposed-articles/{article}/reject', [ThemeManagerController::class, 'rejectProposedArticle'])->name('theme_manager.proposed_articles.reject');
});




// Editor Routes
Route::prefix('editor')->middleware(['auth', 'role:editor'])->group(function () {
    Route::get('/editor', [EditorController::class, 'dashboard'])->name('editor.dashboard');

    Route::get('/users', [EditorController::class, 'manageUsers'])->name('editor.users');
    Route::get('/users/create', [EditorController::class, 'createUser'])->name('editor.users.create');
    Route::post('/users/store', [EditorController::class, 'storeUser'])->name('editor.users.store');
    Route::get('/users/{user}/edit', [EditorController::class, 'editUser'])->name('editor.users.edit');
    Route::post('/users/{user}/update', [EditorController::class, 'updateUser'])->name('editor.users.update');
    Route::post('/users/{user}/delete', [EditorController::class, 'deleteUser'])->name('editor.users.delete');

    Route::get('/themes', [EditorController::class, 'manageThemes'])->name('editor.themes');
    Route::get('/themes/create', [EditorController::class, 'createTheme'])->name('editor.themes.create');
    Route::post('/themes/store', [EditorController::class, 'storeTheme'])->name('editor.themes.store');
    Route::get('/themes/{theme}/edit', [EditorController::class, 'editTheme'])->name('editor.themes.edit');
    Route::post('/themes/{theme}/update', [EditorController::class, 'updateTheme'])->name('editor.themes.update');
    Route::post('/themes/{theme}/delete', [EditorController::class, 'deleteTheme'])->name('editor.themes.delete');

    Route::get('/issues', [EditorController::class, 'manageIssues'])->name('editor.issues');
    Route::post('/issues/{issue}/publish', [EditorController::class, 'publishIssue'])->name('editor.issues.publish');
    Route::post('/issues/{issue}/disactivate', [EditorController::class, 'disactivateIssue'])->name('editor.issues.disactivate');
    Route::post('/issues/{issue}/activate', [EditorController::class, 'activateIssue'])->name('editor.issues.activate');
    Route::get('/issues/{issue}/edit', [EditorController::class, 'editIssue'])->name('editor.issues.edit');
    Route::post('/issues/{issue}/update', [EditorController::class, 'updateIssue'])->name('editor.issues.update');
});
