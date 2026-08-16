<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::view('/about', 'about')->name('about');
Route::view('/pricing', 'pricing')->name('pricing');
Route::view('/contact', 'contact')->name('contact');
Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy-policy', 'privacy')->name('privacy');
Route::view('/refund-policy', 'refund')->name('refund');

Route::get('/dashboard', \App\Livewire\StudentDashboard::class)->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/progress', \App\Livewire\ProgressReport::class)->middleware(['auth', 'verified'])->name('progress');
Route::get('/subscribe', \App\Livewire\SubscriptionCheckout::class)->middleware(['auth'])->name('subscribe');
Route::get('/subscribe/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('subscribe.success');

Route::post('/webhooks/lemonsqueezy', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('webhooks.lemonsqueezy');

Route::get('/profile', \App\Livewire\ProfileSettings::class)
    ->middleware(['auth'])
    ->name('profile');

Route::get('admin/questions', \App\Livewire\Admin\QuestionManager::class)
    ->middleware(['auth', 'admin'])
    ->name('admin.questions');

Route::get('/admin/dashboard', function() {
    return redirect()->route('admin.analytics');
})->middleware(['auth', 'admin'])->name('admin.dashboard');

Route::get('admin/users', \App\Livewire\Admin\UserManager::class)
    ->middleware(['auth', 'admin'])
    ->name('admin.users');

Route::get('admin/users/{user}', \App\Livewire\Admin\UserDetail::class)
    ->middleware(['auth', 'admin'])
    ->name('admin.users.show');

Route::get('admin/analytics', \App\Livewire\Admin\Analytics::class)
    ->middleware(['auth', 'admin'])
    ->name('admin.analytics');

Route::get('admin/payments', \App\Livewire\Admin\PaymentManager::class)
    ->middleware(['auth', 'admin'])
    ->name('admin.payments');

Route::get('admin/payments/{purchase}', \App\Livewire\Admin\TransactionDetail::class)
    ->middleware(['auth', 'admin'])
    ->name('admin.payments.show');

Route::get('admin/settings', \App\Livewire\Admin\Settings::class)
    ->middleware(['auth', 'admin'])
    ->name('admin.settings');

Route::get('admin/messages', \App\Livewire\Admin\ContactSubmissions::class)
    ->middleware(['auth', 'admin'])
    ->name('admin.messages');

Route::get('admin/papers', \App\Livewire\Admin\PaperManager::class)
    ->middleware(['auth', 'admin'])
    ->name('admin.papers');

Route::get('papers', \App\Livewire\PaperCatalog::class)
    ->name('papers');

Route::get('paper/{paper}-{slug}', [\App\Http\Controllers\PublicPaperController::class, 'show'])
    ->name('paper.show');

// Quiz and Result routes
Route::get('quiz/{paper}', \App\Livewire\QuizTaker::class)
    ->middleware(['auth'])
    ->name('quiz.take');

Route::get('result/{attempt}', \App\Livewire\ResultSummary::class)
    ->middleware(['auth'])
    ->name('result.summary');

Route::get('result/{attempt}/review', \App\Livewire\DetailedReview::class)
    ->middleware(['auth'])
    ->name('result.review');

require __DIR__.'/auth.php';

Route::get('/sitemap.xml', function() {
    return response()->view('sitemap')->header('Content-Type', 'text/xml');
});
