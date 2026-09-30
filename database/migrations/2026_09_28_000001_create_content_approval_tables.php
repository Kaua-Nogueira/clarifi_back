<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('segment')->nullable();
            $table->timestamps();
        });

        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('channel');
            $table->string('type');
            $table->string('status')->default('pending')->index();
            $table->dateTime('publication_date')->index();
            $table->string('responsible');
            $table->text('objective');
            $table->string('editorial_line');
            $table->string('cta');
            $table->string('audience');
            $table->text('caption')->nullable();
            $table->text('agency_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('content_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->string('kind')->default('image');
            $table->string('url');
            $table->string('alt_text');
            $table->unsignedInteger('position')->default(1);
            $table->timestamps();
        });

        Schema::create('content_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version_number');
            $table->text('notes');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->foreignId('content_asset_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();
            $table->string('author_name');
            $table->string('author_role')->default('Cliente');
            $table->text('body');
            $table->string('type')->default('general');
            $table->string('status')->default('open');
            $table->decimal('position_x', 5, 2)->nullable();
            $table->decimal('position_y', 5, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('approval_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->string('action');
            $table->string('actor_name');
            $table->text('comment')->nullable();
            $table->string('priority')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_actions');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('content_versions');
        Schema::dropIfExists('content_assets');
        Schema::dropIfExists('contents');
        Schema::dropIfExists('clients');
    }
};
