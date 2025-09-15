<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            // Link to users.mat_id
            $table->string('application_id', 255);
            $table->foreign('application_id')
                  ->references('mat_id')
                  ->on('users')
                  ->onDelete('cascade');

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
            $table->string('foreign_country')->nullable();
            $table->string('home_address')->nullable();
            $table->string('guardian')->nullable();
            $table->string('guardian_address')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('application_type');
            $table->string('photo')->nullable();

            $table->json('schools')->nullable();

            $table->string('first_exam_type')->nullable();
            $table->string('first_exam_year')->nullable();
            $table->string('first_exam_number')->nullable();
            $table->string('first_center_number')->nullable();
            $table->json('first_subjects')->nullable();
            $table->json('first_grades')->nullable();

            $table->string('second_exam_type')->nullable();
            $table->string('second_exam_year')->nullable();
            $table->string('second_exam_number')->nullable();
            $table->string('second_center_number')->nullable();
            $table->json('second_subjects')->nullable();
            $table->json('second_grades')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
