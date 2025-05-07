<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tenant_customizations', function (Blueprint $table) {
            $table->id();
            $table->string('logo_path')->nullable();
            $table->string('primary_color')->default('#3B82F6'); // Default blue
            $table->string('secondary_color')->default('#1E40AF'); // Default dark blue
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tenant_customizations');
    }
}; 