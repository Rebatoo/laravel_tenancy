<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('updates')) {
            Schema::create('updates', function (Blueprint $table) {
                $table->id();
                $table->string('version');
                $table->string('title');
                $table->text('description');
                $table->json('changes')->nullable();
                $table->boolean('is_required')->default(false);
                $table->boolean('is_published')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->string('type')->default('feature'); // feature, bugfix, security
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('updates');
    }
};
