<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Block extends Model
{
    protected $fillable = [
        'slide_id',
        'type',
        'content',
        'position',
        'dimensions',
        'edges',
        'meta',
        'order_index',
    ];

    protected function casts(): array
    {
        return [
            // All JSON columns are decoded to plain PHP arrays.
            // Cast to AsArrayObject if you need dot-notation mutation tracking.
            'content'     => 'array',
            'position'    => 'array',
            'dimensions'  => 'array',
            'edges'       => 'array',
            'meta'        => 'array',
            'order_index' => 'integer',
        ];
    }

    public function slide(): BelongsTo
    {
        return $this->belongsTo(Slide::class);
    }

    // Convenience accessor — resolves outgoing edge targets as Block instances.
    // Usage: $block->resolveEdgeTargets() returns a Collection of sibling Blocks.
    public function resolveEdgeTargets(): \Illuminate\Database\Eloquent\Collection
    {
        $ids = collect($this->edges ?? [])->pluck('target')->filter()->all();

        return static::whereIn('id', $ids)->get();
    }
}
