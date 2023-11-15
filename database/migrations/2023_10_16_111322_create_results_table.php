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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained();
            $table->foreignId('state_id')->constrained();
            $table->foreignId('platform_id')->constrained();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->text('link');
            $table->text('photo')->nullable();
            $table->string('city')->nullable();
            $table->text('description')->nullable();
            $table->json('additional_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
