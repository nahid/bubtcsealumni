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
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('website_url');
            $table->string('designation')->nullable()->after('company_name');
            $table->string('company_website')->nullable()->after('designation');
            $table->string('current_city')->nullable()->after('company_website');
            $table->decimal('latitude', 10, 7)->nullable()->after('current_city');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'designation', 'company_website', 'current_city', 'latitude', 'longitude']);
        });
    }
};
