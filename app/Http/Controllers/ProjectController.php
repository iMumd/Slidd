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
    public function show(Request $request, string $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        abort_if($project->user_id !== $request->user()->id, 403);

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

        // Compute max ID across all slides and blocks for _nextId seeding
        $allIds = collect($savedSlides)->flatMap(fn($s) => array_merge(
            [$s['id']],
            array_column($s['blocks'], 'id')
        ));
        $nextId = $allIds->filter()->isEmpty() ? 3 : ($allIds->max() + 1);

        return view('editor', compact('project', 'savedSlides', 'nextId'));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'type'  => ['required', 'in:slides,galaxy'],
            ]);

            $base  = trim($request->title);
            $title = $base;
            $i     = 1;

            while (Project::where('user_id', $request->user()->id)->where('title', $title)->exists()) {
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        try {
            abort_if($project->user_id !== $request->user()->id, 403);

            $request->validate([
                'title' => ['required', 'string', 'max:255'],
            ]);

            $base  = trim($request->title);
            $title = $base;
            $i     = 1;

            while (
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function save(Request $request, Project $project): JsonResponse
    {
        try {
            abort_if($project->user_id !== $request->user()->id, 403);

            $request->validate([
                'slides'                   => ['present', 'array'],
                'slides.*.blocks'          => ['present', 'array'],
                'slides.*.blocks.*.type'   => ['required', 'string'],
            ]);

            // Delete all existing slides (blocks cascade via DB foreign key / Eloquent)
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function import(Request $request): JsonResponse
    {
        try {
            $data = $request->json()->all();

            if (empty($data['slides']) || !is_array($data['slides'])) {
                return response()->json(['error' => 'Invalid .slidd file.'], 422);
            }

            $baseTitle = trim($data['project']['title'] ?? 'Imported Project');
            $type      = in_array($data['project']['type'] ?? '', ['slides', 'galaxy'])
                ? $data['project']['type']
                : 'slides';

            $title = $baseTitle;
            $i     = 1;
            while (Project::where('user_id', $request->user()->id)->where('title', $title)->exists()) {
                $title = "{$baseTitle} ({$i})";
                $i++;
            }

            $project = Project::create([
                'user_id' => $request->user()->id,
                'title'   => $title,
                'slug'    => (string) Str::uuid(),
                'type'    => $type,
            ]);

            foreach ($data['slides'] as $slideIndex => $slideData) {
                $slide = $project->slides()->create([
                    'title'       => 'Slide ' . ($slideIndex + 1),
                    'type'        => SlideType::SolidText,
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request, Project $project): JsonResponse
    {
        try {
            abort_if($project->user_id !== $request->user()->id, 403);
            $project->delete();
            return response()->json(['ok' => true]);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
