<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('alumni_id', 20)->unique()->nullable()->after('whatsapp_number')
                ->comment('Auto-generated: BCA-{intake}-{shift}-{id}');
            $table->string('facebook_url')->nullable()->after('alumni_id');
            $table->string('linkedin_url')->nullable()->after('facebook_url');
            $table->string('website_url')->nullable()->after('linkedin_url');
        });

        // Backfill alumni IDs for existing users
        DB::table('users')->orderBy('id')->each(function (object $user): void {
            $shiftCode = $user->shift === 'evening' ? 'E' : 'D';
            $alumniId = \App\Models\User::generateAlumniId($user->intake, $shiftCode, $user->id);
            //sprintf('BCA-%03d-%s-%06d', $user->intake, $shiftCode, $user->id);

            DB::table('users')->where('id', $user->id)->update(['alumni_id' => $alumniId]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['alumni_id', 'facebook_url', 'linkedin_url', 'website_url']);
        });
    }
};
