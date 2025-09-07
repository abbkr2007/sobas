<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    use HasFactory;
}

public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('fname');
        $table->string('oname');
        $table->string('phone_number')->unique();
        $table->string('email')->unique();
        $table->string('password');
        $table->timestamps();
    });
}
