<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/introduction', fn() => view('introduction'))->name('introduction');
Route::get('/s/{slug}', function (string $slug) {
    $project = Project::with([
        'slides'        => fn($q) => $q->orderBy('order_index'),
        'slides.blocks' => fn($q) => $q->orderBy('order_index'),
        'user',
    ])->where('slug', $slug)->firstOrFail();

    $slides = $project->slides->map(fn($slide) => [
        'id'     => $slide->id,
        'blocks' => $slide->blocks->map(fn($b) => [
            'type'        => $b->type,
            'content'     => $b->type === 'code' ? ($b->content['code'] ?? '') : ($b->content['html'] ?? ''),
            'detectedLang'=> $b->content['lang'] ?? '',
        ])->values()->all(),
    ])->values()->all();

    return view('preview', compact('project', 'slides'));
})->name('project.preview');

Route::get('/privacy', fn() => view('legal.privacy'))->name('privacy');
Route::get('/terms', fn() => view('legal.terms'))->name('terms');
Route::get('/security', fn() => view('legal.security'))->name('security');

Route::get('/dashboard', function () {
    $projects = auth()->user()->projects()
        ->with([
            'slides'        => fn($q) => $q->orderBy('order_index'),
            'slides.blocks' => fn($q) => $q->orderBy('order_index'),
        ])
        ->latest()
        ->get();
    return view('dashboard', compact('projects'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::post('/projects/import', [ProjectController::class, 'import'])->name('projects.import');
    Route::patch('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/editor/{slug}', [ProjectController::class, 'show'])->name('editor.show');
    Route::post('/editor/{project}/save', [ProjectController::class, 'save'])->name('editor.save');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
