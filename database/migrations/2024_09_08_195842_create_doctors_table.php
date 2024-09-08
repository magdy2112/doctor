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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('photo')->nullable();
            $table->string('phone');
            $table->string('address');
          
            $table->integer('age');
            $table->integer('experience');
            $table->integer('price');


            $table->text('description')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->enum('status', ['active', 'inactive'])->default('inactive');


            $table->bigInteger('qualification_id');
            $table->bigInteger('specialization_id');
            $table->bigInteger('city_id');


            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
