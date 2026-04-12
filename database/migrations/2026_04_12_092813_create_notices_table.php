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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->comment('Admin who posted');
            $table->string('title');
            $table->text('body');
            $table->string('type', 20)->default('notice')->comment('notice or event');
            $table->date('event_date')->nullable()->comment('Only for type=event');
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index('type');
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
