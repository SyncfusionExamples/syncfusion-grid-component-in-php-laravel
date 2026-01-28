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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('StudentID')->unique();
            $table->string('FirstName');
            $table->string('LastName')->nullable();
            $table->string('Email')->unique();
            $table->string('Phone')->nullable();
            $table->string('Course');
            $table->enum('Status', ['Active', 'Inactive', 'Graduated'])->default('Active');
            $table->enum('Campus', ['Main', 'Downtown', 'North', 'South'])->default('Main');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
