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
        Schema::create('brunches', function (Blueprint $table) {
            $table->id();
            $table->string('twoGisId')
                ->unique(true)
                ->nullable(false);
            $table->string('name')
            ->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brunches');
    }
};
