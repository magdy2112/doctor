<?php

use App\Models\Doctor;
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
        Schema::create('appointments', function (Blueprint $table) {

            $table->id();
            $table->foreignIdFor(Doctor::class)->constrained();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('max_patients')->default(1000);
            $table->integer('count')->default(0);
            $table->enum('status', [ 'active', 'completed','cancelled'])->default('active');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
