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
        Schema::create('vietqr_informations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('consumer_account_information');
            $table->string('bank_name')->nullable();
            $table->string('bank_branch_name')->nullable();
            $table->string('bank_account_type')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_holder')->nullable();
            $table->text('datas')->nullable();
            $table->boolean('status')->default(1);
            $table->string('hash_type')->default('CRC-CCITT (0xFFFF)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vietqr_informations');
    }
};
