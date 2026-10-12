<?php

namespace App\Services;

class LoanDecision
{
    public function __construct(
        public bool $eligible,
        public ?string $reason,
        public ?float $annualRate,
        public ?int $monthlyPaymentCents,
        public float $debtToIncome,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'eligible' => $this->eligible,
            'reason' => $this->reason,
            'annual_rate' => $this->annualRate,
            'monthly_payment_cents' => $this->monthlyPaymentCents,
            'debt_to_income' => round($this->debtToIncome, 4),
        ];
    }
}

class LoanCalculator
{
    /** @var array<string, float> */
    private const RATES = [
        'excellent' => 0.085,
        'good' => 0.11,
        'fair' => 0.145,
    ];

    public function quote(
        int $annualIncomeCents,
        int $monthlyDebtCents,
        int $amountCents,
        int $termMonths,
        string $band,
    ): LoanDecision {
        $monthlyIncome = $annualIncomeCents / 12;
        $debtToIncome = $monthlyIncome > 0 ? $monthlyDebtCents / $monthlyIncome : 1.0;

        if (! isset(self::RATES[$band])) {
            return new LoanDecision(false, 'Unknown credit band.', null, null, $debtToIncome);
        }
        if (! in_array($termMonths, [12, 24, 36, 48, 60], true)) {
            return new LoanDecision(false, 'Term must be 12, 24, 36, 48, or 60 months.', null, null, $debtToIncome);
        }
        if ($debtToIncome > 0.43) {
            return new LoanDecision(false, 'Debt-to-income is above 43%.', null, null, $debtToIncome);
        }
        if ($amountCents > $annualIncomeCents * 5) {
            return new LoanDecision(false, 'Requested amount is above five times annual income.', null, null, $debtToIncome);
        }

        $annualRate = self::RATES[$band];
        if ($termMonths > 36) {
            $annualRate += 0.005;
        }

        return new LoanDecision(
            true,
            null,
            $annualRate,
            $this->monthlyPayment($amountCents, $annualRate, $termMonths),
            $debtToIncome,
        );
    }

    private function monthlyPayment(int $amountCents, float $annualRate, int $termMonths): int
    {
        $monthlyRate = $annualRate / 12;
        if ($monthlyRate === 0.0) {
            return (int) round($amountCents / $termMonths);
        }

        $factor = pow(1 + $monthlyRate, $termMonths);

        return (int) round($amountCents * $monthlyRate * $factor / ($factor - 1));
    }
}
