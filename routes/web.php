<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('admin/questions', 'admin.questions')
    ->middleware(['auth', 'admin'])
    ->name('admin.questions');

Route::view('papers', 'papers')
    ->middleware(['auth'])
    ->name('papers');

// Placeholder routes for future prompts (QuizTaker, PayHere)
Route::get('quiz/{paper}', function (App\Models\Paper $paper) {
    return view('quiz', ['paper' => $paper]);
})->middleware(['auth'])->name('quiz.take');

Route::get('result/{attempt}', function (App\Models\Attempt $attempt) {
    return view('result', ['attempt' => $attempt]);
})->middleware(['auth'])->name('result.summary');

Route::get('buy/{paper}', fn () => abort(404, 'Payment coming soon'))
    ->middleware(['auth'])
    ->name('paper.buy');

require __DIR__.'/auth.php';


