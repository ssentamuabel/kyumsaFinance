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
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->smallInteger('month');
            $table->string('telephone');
            $table->string('network');
            $table->unsignedBigInteger('user_year_id');
            $table->Boolean('message')->default(true);
            $table->timestamps();

            $table->foreign('user_year_id')->references('id')->on('user_years')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
