<?php

namespace App\Models;

use App\Enums\ProjectVisibility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'visibility',
    ];

    protected function casts(): array
    {
        return [
            'visibility' => ProjectVisibility::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $project): void {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class)->orderBy('order_index');
    }
}
