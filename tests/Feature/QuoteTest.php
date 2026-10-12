<?php

namespace Tests\Feature;

use App\Models\LoanQuote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_quote_form_is_available(): void
    {
        $this->get('/')->assertOk()->assertSee('Loan Desk');
    }

    public function test_store_persists_an_eligible_quote(): void
    {
        $response = $this->post('/quotes', [
            'full_name' => 'Asha Verma',
            'email' => 'asha@example.com',
            'annual_income' => 1200000,
            'monthly_debt' => 20000,
            'amount' => 300000,
            'term_months' => 36,
            'credit_band' => 'good',
        ]);

        $response->assertOk()->assertSee('Eligible');
        $this->assertDatabaseHas('loan_quotes', [
            'email' => 'asha@example.com',
            'eligible' => true,
        ]);
        $this->assertSame(1, LoanQuote::query()->count());
    }

    public function test_api_rejects_a_quote_above_the_debt_limit(): void
    {
        $response = $this->postJson('/api/quotes', [
            'full_name' => 'Asha Verma',
            'email' => 'asha@example.com',
            'annual_income' => 600000,
            'monthly_debt' => 30000,
            'amount' => 100000,
            'term_months' => 24,
            'credit_band' => 'excellent',
        ]);

        $response->assertCreated()->assertJsonPath('eligible', false);
    }
}
