<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('surname');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('dob');
            $table->string('place_of_birth')->nullable();
            $table->string('gender');
            $table->string('state');
            $table->string('lga');
            $table->string('town')->nullable();
            $table->string('country');
            $table->string('home_address')->nullable();
            $table->string('guardian')->nullable();
            $table->string('guardian_address')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('application_type');
            $table->string('qualification')->nullable();
            $table->string('institution')->nullable();
            $table->string('graduation_year')->nullable();
            $table->string('photo')->nullable();
            $table->string('resume')->nullable();
            $table->string('supporting_document')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
