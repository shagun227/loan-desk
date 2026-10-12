<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Models\LoanQuote;
use App\Services\LoanCalculator;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class QuoteController extends Controller
{
    public function __construct(private LoanCalculator $calculator) {}

    public function create(): View
    {
        return view('quotes.create');
    }

    public function store(QuoteRequest $request): View
    {
        $quote = $this->persist($request);

        return view('quotes.result', ['quote' => $quote]);
    }

    public function api(QuoteRequest $request): JsonResponse
    {
        $quote = $this->persist($request);

        return response()->json([
            'id' => $quote->id,
            'eligible' => $quote->eligible,
            'reason' => $quote->reason,
            'annual_rate' => $quote->annual_rate,
            'monthly_payment_cents' => $quote->monthly_payment_cents,
            'debt_to_income' => $quote->debt_to_income,
        ], 201);
    }

    private function persist(QuoteRequest $request): LoanQuote
    {
        $decision = $this->calculator->quote(
            $request->annualIncomeCents(),
            $request->monthlyDebtCents(),
            $request->amountCents(),
            (int) $request->integer('term_months'),
            (string) $request->string('credit_band'),
        );

        return LoanQuote::query()->create([
            'full_name' => $request->string('full_name'),
            'email' => $request->string('email'),
            'annual_income_cents' => $request->annualIncomeCents(),
            'monthly_debt_cents' => $request->monthlyDebtCents(),
            'amount_cents' => $request->amountCents(),
            'term_months' => (int) $request->integer('term_months'),
            'credit_band' => (string) $request->string('credit_band'),
            'eligible' => $decision->eligible,
            'reason' => $decision->reason,
            'annual_rate' => $decision->annualRate,
            'monthly_payment_cents' => $decision->monthlyPaymentCents,
            'debt_to_income' => $decision->debtToIncome,
        ]);
    }
}
