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
        Schema::create('module_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('tagline')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->unsignedInteger('attachment_id')->nullable();
            $table->unsignedBigInteger('user_id');         
            $table->boolean('status')->default(0);
            $table->softDeletes();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_profiles');
    }
};
