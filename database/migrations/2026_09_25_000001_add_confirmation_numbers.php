<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('confirmation_number')->nullable()->unique();
        });

        Schema::create('confirmation_sequences', function (Blueprint $table) {
            $table->unsignedSmallInteger('year');
            $table->string('programme_code', 2);
            $table->unsignedInteger('last_serial')->default(0);
            $table->primary(['year', 'programme_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('confirmation_sequences');
        Schema::table('applications', function (Blueprint $table) {
            $table->dropUnique(['confirmation_number']);
            $table->dropColumn('confirmation_number');
        });
    }
};
