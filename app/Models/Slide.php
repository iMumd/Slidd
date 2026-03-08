<?php

namespace App\Models;

use App\Enums\SlideType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slide extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'type',
        'order_index',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'type'        => SlideType::class,
            'order_index' => 'integer',
            'meta'        => 'array',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class)->orderBy('order_index');
    }

    public function isGalaxySpace(): bool
    {
        return $this->type === SlideType::GalaxySpace;
    }
}
