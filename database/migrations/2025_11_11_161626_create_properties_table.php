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
        Schema::create('properties', function (Blueprint $table) {

            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // owner (agent)
            $table->string('type'); // appartement, villa, terrain...
            $table->unsignedTinyInteger('nbr_piece')->nullable();
            $table->unsignedInteger('surface');
            $table->decimal('price', 12, 2);
            $table->string('city');
            $table->text('description');
            $table->enum('status', ['disponible', 'vendu', 'location'])->default('disponible');
            $table->boolean('published')->default(false);
            $table->string('title')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
