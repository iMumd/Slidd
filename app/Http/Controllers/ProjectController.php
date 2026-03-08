<?php

namespace App\Http\Controllers;

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
        return view('editor', compact('project'));
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
