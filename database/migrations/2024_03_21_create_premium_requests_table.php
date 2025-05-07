<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('premium_requests', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');  // Changed to string to match tenant ID format
            $table->text('message');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_response')->nullable();
            $table->timestamps();

            // Remove the foreign key constraint since we're in a multi-tenant setup
            // The relationship will be handled at the application level
        });
    }

    public function down()
    {
        Schema::dropIfExists('premium_requests');
    }
}; 