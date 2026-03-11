<?php

namespace App\Http\Controllers;

use App\Enums\SlideType;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class ProjectController extends Controller
{
    /** Valid block types accepted from the frontend. */
    private const BLOCK_TYPES = ['text', 'code', 'image', 'note', 'heading', 'hr', 'divider'];

    public function show(Request $request, string $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        abort_if($project->user_id !== $request->user()->id, 403);

        // ── Galaxy Space ──────────────────────────────────────────
        if ($project->type === 'galaxy') {
            $slide      = $project->slides()->first();
            $savedEdges = $slide ? ($slide->meta['edges'] ?? []) : [];
            $savedNodes = $slide
                ? $slide->blocks()->orderBy('order_index')->get()->map(fn($b) => [
                    'id'          => $b->meta['node_id'] ?? $b->id,
                    'type'        => $b->type,
                    'x'           => $b->position['x']    ?? 100,
                    'y'           => $b->position['y']    ?? 100,
                    'w'           => $b->dimensions['w']  ?? 300,
                    'h'           => $b->dimensions['h']  ?? 180,
                    'content'     => match ($b->type) {
                                        'code'  => $b->content['code'] ?? '',
                                        'image' => '',
                                        default => $b->content['html'] ?? '',
                                     },
                    'src'         => $b->type === 'image' ? ($b->content['src'] ?? '') : '',
                    'color'       => $b->meta['color']    ?? '#ffffff',
                    'title'       => $b->meta['title']    ?? '',
                    'detectedLang'=> $b->content['lang']  ?? '',
                    'highlighted' => '',
                    'isEditing'   => false,
                ])->values()->all()
                : [];

            $allIds = collect($savedNodes)->pluck('id')->filter();
            $nextId = $allIds->isEmpty() ? 1 : ($allIds->max() + 1);

            return view('editor.galaxy', compact('project', 'savedNodes', 'savedEdges', 'nextId'));
        }

        // ── Solid Text ────────────────────────────────────────────
        $savedSlides = $project->slides()->orderBy('order_index')->get()->map(fn($slide) => [
            'id'     => $slide->id,
            'blocks' => $slide->blocks()->orderBy('order_index')->get()->map(fn($b) => [
                'id'                 => $b->id,
                'type'               => $b->type,
                'content'            => $b->type === 'code' ? ($b->content['code'] ?? '') : ($b->content['html'] ?? ''),
                'detectedLang'       => $b->content['lang'] ?? '',
                'highlightedContent' => '',
                'isEditing'          => false,
            ])->values()->all(),
        ])->values()->all();

        $allIds = collect($savedSlides)->flatMap(fn($s) => array_merge(
            [$s['id']],
            array_column($s['blocks'], 'id')
        ));
        $nextId = $allIds->filter()->isEmpty() ? 3 : ($allIds->max() + 1);

        return view('editor.slides', compact('project', 'savedSlides', 'nextId'));
    }

    public function store(Request $request): JsonResponse
    {
        // Validation runs outside try-catch so ValidationException propagates
        // as a proper 422 response rather than being swallowed as 500.
        $request->validate([
            'title' => ['required', 'string', 'min:1', 'max:255'],
            'type'  => ['required', 'in:slides,galaxy'],
        ]);

        try {
            $base  = trim($request->title);
            $title = $base;
            $i     = 1;

            while (
                $i <= 99 &&
                Project::where('user_id', $request->user()->id)->where('title', $title)->exists()
            ) {
                $title = "{$base} ({$i})";
                $i++;
            }

            $project = Project::create([
                'user_id' => $request->user()->id,
                'title'   => $title,
                'slug'    => (string) Str::uuid(),
                'type'    => $request->type,
            ]);

            return response()->json([
                'redirect_url' => route('editor.show', $project->slug),
            ]);
        } catch (Throwable $e) {
            report($e);
            return response()->json(['error' => 'Could not create project.'], 500);
        }
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        abort_if($project->user_id !== $request->user()->id, 403);

        $request->validate([
            'title' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        try {
            $base  = trim($request->title);
            $title = $base;
            $i     = 1;

            while (
                $i <= 99 &&
                Project::where('user_id', $request->user()->id)
                       ->where('title', $title)
                       ->where('id', '!=', $project->id)
                       ->exists()
            ) {
                $title = "{$base} ({$i})";
                $i++;
            }

            $project->update(['title' => $title]);

            return response()->json(['title' => $project->fresh()->title]);
        } catch (Throwable $e) {
            report($e);
            return response()->json(['error' => 'Could not update project.'], 500);
        }
    }

    public function save(Request $request, Project $project): JsonResponse
    {
        abort_if($project->user_id !== $request->user()->id, 403);

        $request->validate([
            'slides'                        => ['present', 'array', 'max:500'],
            'slides.*.blocks'               => ['present', 'array', 'max:300'],
            'slides.*.blocks.*.type'        => ['required', 'string', 'in:' . implode(',', self::BLOCK_TYPES)],
            'slides.*.blocks.*.content'     => ['nullable', 'string', 'max:200000'],
            'slides.*.blocks.*.detectedLang'=> ['nullable', 'string', 'max:50'],
        ]);

        try {
            foreach ($project->slides as $slide) {
                $slide->blocks()->delete();
            }
            $project->slides()->delete();

            foreach ($request->slides as $slideIndex => $slideData) {
                $slide = $project->slides()->create([
                    'title'       => 'Slide ' . ($slideIndex + 1),
                    'type'        => SlideType::SolidText,
                    'order_index' => $slideIndex,
                ]);

                foreach ($slideData['blocks'] as $blockIndex => $raw) {
                    $type    = $raw['type'];
                    $content = match ($type) {
                        'code'  => ['code' => $raw['content'] ?? '', 'lang' => $raw['detectedLang'] ?? ''],
                        default => ['html' => $raw['content'] ?? ''],
                    };

                    $slide->blocks()->create([
                        'type'        => $type,
                        'content'     => $content,
                        'order_index' => $blockIndex,
                        'position'    => ['x' => 0, 'y' => 0],
                        'dimensions'  => ['w' => 0, 'h' => 0],
                    ]);
                }
            }

            return response()->json(['ok' => true]);
        } catch (Throwable $e) {
            report($e);
            return response()->json(['error' => 'Could not save project.'], 500);
        }
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'project'               => ['nullable', 'array'],
            'project.title'         => ['nullable', 'string', 'max:255'],
            'project.type'          => ['nullable', 'string', 'in:slides,galaxy'],
            'slides'                => ['required', 'array', 'min:1', 'max:500'],
            'slides.*.blocks'       => ['present', 'array', 'max:300'],
            'slides.*.blocks.*.type'    => ['required', 'string', 'max:50'],
            'slides.*.blocks.*.content' => ['nullable', 'string', 'max:200000'],
        ]);

        try {
            $baseTitle = trim($request->input('project.title') ?? 'Imported Project') ?: 'Imported Project';
            $type      = $request->input('project.type', 'slides');

            $title = $baseTitle;
            $i     = 1;
            while (
                $i <= 99 &&
                Project::where('user_id', $request->user()->id)->where('title', $title)->exists()
            ) {
                $title = "{$baseTitle} ({$i})";
                $i++;
            }

            $project = Project::create([
                'user_id' => $request->user()->id,
                'title'   => $title,
                'slug'    => (string) Str::uuid(),
                'type'    => $type,
            ]);

            $slideType = $type === 'galaxy' ? SlideType::GalaxySpace : SlideType::SolidText;

            foreach ($request->input('slides') as $slideIndex => $slideData) {
                $slide = $project->slides()->create([
                    'title'       => 'Slide ' . ($slideIndex + 1),
                    'type'        => $slideType,
                    'order_index' => $slideIndex,
                ]);

                foreach ($slideData['blocks'] ?? [] as $blockIndex => $raw) {
                    $blockType = $raw['type'] ?? 'text';
                    $content   = match ($blockType) {
                        'code'  => ['code' => $raw['content'] ?? '', 'lang' => $raw['detectedLang'] ?? ''],
                        default => ['html' => $raw['content'] ?? ''],
                    };

                    $slide->blocks()->create([
                        'type'        => $blockType,
                        'content'     => $content,
                        'order_index' => $blockIndex,
                        'position'    => ['x' => 0, 'y' => 0],
                        'dimensions'  => ['w' => 0, 'h' => 0],
                    ]);
                }
            }

            return response()->json(['redirect_url' => route('editor.show', $project->slug)]);
        } catch (Throwable $e) {
            report($e);
            return response()->json(['error' => 'Could not import project.'], 500);
        }
    }

    public function saveGalaxy(Request $request, Project $project): JsonResponse
    {
        abort_if($project->user_id !== $request->user()->id, 403);

        $request->validate([
            'nodes'                         => ['present', 'array', 'max:500'],
            'nodes.*.type'                  => ['required', 'string', 'in:text,code,image,note'],
            'nodes.*.x'                     => ['required', 'numeric', 'between:-100000,100000'],
            'nodes.*.y'                     => ['required', 'numeric', 'between:-100000,100000'],
            'nodes.*.w'                     => ['required', 'numeric', 'min:10', 'max:5000'],
            'nodes.*.h'                     => ['required', 'numeric', 'min:10', 'max:5000'],
            'nodes.*.content'               => ['nullable', 'string', 'max:200000'],
            'nodes.*.src'                   => ['nullable', 'string', 'max:5000000'],
            'nodes.*.color'                 => ['nullable', 'string', 'max:50'],
            'nodes.*.title'                 => ['nullable', 'string', 'max:255'],
            'nodes.*.detectedLang'          => ['nullable', 'string', 'max:50'],
            'edges'                         => ['present', 'array', 'max:2000'],
            'edges.*.id'                    => ['required'],
            'edges.*.from'                  => ['required'],
            'edges.*.to'                    => ['required'],
        ]);

        try {
            $nodes = $request->input('nodes', []);
            $edges = $request->input('edges', []);

            foreach ($project->slides as $slide) {
                $slide->blocks()->delete();
            }
            $project->slides()->delete();

            $slide = $project->slides()->create([
                'title'       => 'Galaxy Canvas',
                'type'        => SlideType::GalaxySpace,
                'order_index' => 0,
                'meta'        => ['edges' => $edges],
            ]);

            foreach ($nodes as $i => $node) {
                $type    = $node['type'];
                $content = match ($type) {
                    'code'  => ['code' => $node['content'] ?? '', 'lang' => $node['detectedLang'] ?? ''],
                    'image' => ['src'  => $node['src'] ?? ''],
                    default => ['html' => $node['content'] ?? ''],
                };

                $slide->blocks()->create([
                    'type'        => $type,
                    'content'     => $content,
                    'order_index' => $i,
                    'position'    => ['x' => $node['x'] ?? 0,   'y' => $node['y'] ?? 0],
                    'dimensions'  => ['w' => $node['w'] ?? 300, 'h' => $node['h'] ?? 180],
                    'meta'        => [
                        'color'   => $node['color']   ?? '#ffffff',
                        'title'   => $node['title']   ?? '',
                        'node_id' => $node['id']      ?? null,
                    ],
                ]);
            }

            return response()->json(['ok' => true]);
        } catch (Throwable $e) {
            report($e);
            return response()->json(['error' => 'Could not save canvas.'], 500);
        }
    }

    public function destroy(Request $request, Project $project): JsonResponse
    {
        abort_if($project->user_id !== $request->user()->id, 403);

        try {
            $project->delete();
            return response()->json(['ok' => true]);
        } catch (Throwable $e) {
            report($e);
            return response()->json(['error' => 'Could not delete project.'], 500);
        }
    }
}
