<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->unsignedBigInteger('annual_income_cents');
            $table->unsignedBigInteger('monthly_debt_cents');
            $table->unsignedBigInteger('amount_cents');
            $table->unsignedSmallInteger('term_months');
            $table->string('credit_band');
            $table->boolean('eligible');
            $table->string('reason')->nullable();
            $table->decimal('annual_rate', 6, 4)->nullable();
            $table->unsignedBigInteger('monthly_payment_cents')->nullable();
            $table->decimal('debt_to_income', 8, 4);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_quotes');
    }
};
