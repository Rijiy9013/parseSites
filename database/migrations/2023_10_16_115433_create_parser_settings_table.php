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
        Schema::create('parser_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_id')->constrained();
            $table->text("name");
            $table->text("price");
            $table->text("shipping_price")->nullable();
            $table->text("link");
            $table->text("country")->nullable();
            $table->text("city")->nullable();
            $table->text("shipping_price")->nullable();
            $table->text("image")->nullable();
            $table->text("description")->nullable();
            $table->text("pagination")->nullable();
            $table->integer("count")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parser_settings');
    }
};
