<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slide_id')->constrained()->cascadeOnDelete();

            // Open string, not enum — new block types must not require a schema migration.
            // Known values: text | heading | code | table | image | embed | divider | shape
            $table->string('type', 64);

            // Content schema varies per type:
            //   heading : {text: string, level: 1|2|3}
            //   text    : {body: string, format: 'markdown'|'plain'}
            //   code    : {language: string, code: string, theme: string|null}
            //   table   : {headers: string[], rows: string[][], caption: string|null}
            //   image   : {src: string, alt: string|null, fit: 'cover'|'contain'}
            //   embed   : {url: string, provider: string|null}
            //   divider : {}
            $table->json('content');

            // Canvas position — used by both solid_text (y-axis flow) and galaxy_space (free x/y)
            $table->json('position');   // {x: float, y: float}
            $table->json('dimensions'); // {width: float, height: float}

            // Outgoing edges for galaxy_space connections.
            // [{id: string, target: int, type: 'default'|'step'|'bezier', animated: bool, label: string|null}]
            $table->json('edges')->nullable();

            // Visual overrides & canvas metadata.
            // {styles: {}, locked: bool, zIndex: int, opacity: float}
            $table->json('meta')->nullable();

            // Sequential order within solid_text slides; for galaxy_space this is rendering z-order fallback
            $table->unsignedSmallInteger('order_index')->default(0);

            $table->timestamps();

            $table->index(['slide_id', 'order_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
