<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanQuote extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'annual_income_cents',
        'monthly_debt_cents',
        'amount_cents',
        'term_months',
        'credit_band',
        'eligible',
        'reason',
        'annual_rate',
        'monthly_payment_cents',
        'debt_to_income',
    ];

    protected function casts(): array
    {
        return [
            'eligible' => 'boolean',
            'annual_rate' => 'float',
            'debt_to_income' => 'float',
        ];
    }
}
