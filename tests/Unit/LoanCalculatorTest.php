<?php

namespace Tests\Unit;

use App\Services\LoanCalculator;
use PHPUnit\Framework\TestCase;

class LoanCalculatorTest extends TestCase
{
    public function test_good_band_is_eligible_under_the_debt_limit(): void
    {
        $decision = (new LoanCalculator())->quote(
            annualIncomeCents: 1_200_000_00,
            monthlyDebtCents: 20_000_00,
            amountCents: 300_000_00,
            termMonths: 36,
            band: 'good',
        );

        $this->assertTrue($decision->eligible);
        $this->assertSame(0.11, $decision->annualRate);
        $this->assertNotNull($decision->monthlyPaymentCents);
        $this->assertLessThan(0.43, $decision->debtToIncome);
    }

    public function test_high_debt_to_income_is_rejected(): void
    {
        $decision = (new LoanCalculator())->quote(
            annualIncomeCents: 600_000_00,
            monthlyDebtCents: 30_000_00,
            amountCents: 100_000_00,
            termMonths: 24,
            band: 'excellent',
        );

        $this->assertFalse($decision->eligible);
        $this->assertSame('Debt-to-income is above 43%.', $decision->reason);
    }

    public function test_longer_term_adds_half_a_percent(): void
    {
        $decision = (new LoanCalculator())->quote(
            annualIncomeCents: 2_000_000_00,
            monthlyDebtCents: 0,
            amountCents: 400_000_00,
            termMonths: 60,
            band: 'excellent',
        );

        $this->assertTrue($decision->eligible);
        $this->assertEqualsWithDelta(0.09, (float) $decision->annualRate, 0.0001);
    }
}
