<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.welcome');
});

Route::get('/introduction', fn() => view('pages.introduction'))->name('introduction');
Route::get('/s/{slug}', function (string $slug) {
    $project = Project::with([
        'slides'        => fn($q) => $q->orderBy('order_index'),
        'slides.blocks' => fn($q) => $q->orderBy('order_index'),
        'user',
    ])->where('slug', $slug)->first();

    if (! $project) {
        return response(view('share.not-found'), 404);
    }

    if ($project->type === 'galaxy') {
        $slide      = $project->slides->first();
        $savedEdges = $slide ? ($slide->meta['edges'] ?? []) : [];
        $savedNodes = $slide
            ? $slide->blocks->map(fn($b) => [
                'id'          => $b->meta['node_id'] ?? $b->id,
                'type'        => $b->type,
                'x'           => $b->position['x']   ?? 100,
                'y'           => $b->position['y']   ?? 100,
                'w'           => $b->dimensions['w']  ?? 300,
                'h'           => $b->dimensions['h']  ?? 180,
                'content'     => match ($b->type) {
                                    'code'  => $b->content['code'] ?? '',
                                    'image' => '',
                                    default => $b->content['html'] ?? '',
                                 },
                'src'         => $b->type === 'image' ? ($b->content['src'] ?? '') : '',
                'color'       => $b->meta['color']   ?? '#ffffff',
                'title'       => $b->meta['title']   ?? '',
                'detectedLang'=> $b->content['lang'] ?? '',
                'highlighted' => '',
            ])->values()->all()
            : [];

        return view('share.galaxy', compact('project', 'savedNodes', 'savedEdges'));
    }

    $slides = $project->slides->map(fn($slide) => [
        'id'     => $slide->id,
        'blocks' => $slide->blocks->map(fn($b) => [
            'type'        => $b->type,
            'content'     => $b->type === 'code' ? ($b->content['code'] ?? '') : ($b->content['html'] ?? ''),
            'detectedLang'=> $b->content['lang'] ?? '',
        ])->values()->all(),
    ])->values()->all();

    return view('share.slides', compact('project', 'slides'));
})->name('project.preview');

Route::get('/privacy', fn() => view('legal.privacy'))->name('privacy');
Route::get('/terms', fn() => view('legal.terms'))->name('terms');
Route::get('/security', fn() => view('legal.security'))->name('security');

Route::get('/sitemap.xml', function () {
    $lastmod = now()->toDateString();
    $urls = [
        ['loc' => url('/'),             'changefreq' => 'weekly',  'priority' => '1.0', 'lastmod' => $lastmod],
        ['loc' => url('/introduction'), 'changefreq' => 'monthly', 'priority' => '0.8', 'lastmod' => $lastmod],
        ['loc' => url('/privacy'),      'changefreq' => 'yearly',  'priority' => '0.2', 'lastmod' => $lastmod],
        ['loc' => url('/terms'),        'changefreq' => 'yearly',  'priority' => '0.2', 'lastmod' => $lastmod],
        ['loc' => url('/security'),     'changefreq' => 'yearly',  'priority' => '0.2', 'lastmod' => $lastmod],
    ];
    return response()
        ->view('sitemap', compact('urls'))
        ->header('Content-Type', 'application/xml; charset=utf-8');
})->name('sitemap');

Route::get('/dashboard', function () {
    $projects = auth()->user()->projects()
        ->with([
            'slides'        => fn($q) => $q->orderBy('order_index'),
            'slides.blocks' => fn($q) => $q->orderBy('order_index'),
        ])
        ->latest()
        ->get();
    return view('app.dashboard', compact('projects'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::post('/projects/import', [ProjectController::class, 'import'])->name('projects.import');
    Route::patch('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/editor/{slug}', [ProjectController::class, 'show'])->name('editor.show');
    Route::post('/editor/{project}/save', [ProjectController::class, 'save'])->name('editor.save');
    Route::post('/editor/{project}/save-galaxy', [ProjectController::class, 'saveGalaxy'])->name('editor.save-galaxy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::fallback(fn() => abort(404));

require __DIR__.'/auth.php';
