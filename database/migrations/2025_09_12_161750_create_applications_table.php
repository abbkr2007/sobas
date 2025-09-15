<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->nullable();
            $table->string('surname');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('dob'); // we'll accept dd/mm/YYYY string
            $table->string('place_of_birth')->nullable();
            $table->string('gender');
            $table->string('state');
            $table->string('lga');
            $table->string('town')->nullable();
            $table->string('country');
            $table->string('foreign_country')->nullable();
            $table->string('home_address')->nullable();
            $table->string('guardian')->nullable();
            $table->string('guardian_address')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('application_type');
            $table->string('photo')->nullable(); // store path
            $table->json('schools')->nullable();
            $table->json('first_sitting')->nullable();
            $table->json('second_sitting')->nullable();
            $table->string('jamb_no')->nullable();
            $table->string('jamb_score')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
