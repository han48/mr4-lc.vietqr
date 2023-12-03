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
        Schema::create('vietqr_banks', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('bin');
            $table->string('name')->nullable();
            $table->string('shortName')->nullable();
            $table->string('logo')->nullable();
            $table->integer('transferSupported')->nullable();
            $table->integer('lookupSupported')->nullable();
            $table->integer('support')->nullable();
            $table->integer('isTransfer')->nullable();
            $table->string('swift_code')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vietqr_banks');
    }
};
