<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class ProjectController extends Controller
{
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
}
