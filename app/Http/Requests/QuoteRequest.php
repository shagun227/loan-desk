<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'annual_income' => ['required', 'numeric', 'min:1'],
            'monthly_debt' => ['required', 'numeric', 'min:0'],
            'amount' => ['required', 'numeric', 'min:1'],
            'term_months' => ['required', 'integer', 'in:12,24,36,48,60'],
            'credit_band' => ['required', 'in:excellent,good,fair'],
        ];
    }

    public function annualIncomeCents(): int
    {
        return $this->rupeesToCents('annual_income');
    }

    public function monthlyDebtCents(): int
    {
        return $this->rupeesToCents('monthly_debt');
    }

    public function amountCents(): int
    {
        return $this->rupeesToCents('amount');
    }

    private function rupeesToCents(string $field): int
    {
        return (int) round(((float) $this->input($field)) * 100);
    }
}
