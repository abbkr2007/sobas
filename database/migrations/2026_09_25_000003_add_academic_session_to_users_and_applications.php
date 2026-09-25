<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('academic_session_id')->nullable()->after('mat_id')->constrained('academic_sessions')->nullOnDelete();
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('academic_session_id')->nullable()->after('application_id')->constrained('academic_sessions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['academic_session_id']);
            $table->dropColumn('academic_session_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['academic_session_id']);
            $table->dropColumn('academic_session_id');
        });
    }
};