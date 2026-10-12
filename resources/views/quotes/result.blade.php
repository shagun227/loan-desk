<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quote · Loan Desk</title>
    <style>
        body { margin: 0; background: #f6f3ee; color: #1c1917; font-family: Georgia, serif; }
        main { max-width: 32rem; margin: 0 auto; padding: 1.5rem; }
        .card { background: #fff; border: 1px solid #e4ddd2; border-radius: 8px; padding: 1rem; }
        a { color: #1f6f8b; }
    </style>
</head>
<body>
<main>
    <h1>{{ $quote->eligible ? 'Eligible' : 'Not eligible' }}</h1>
    <div class="card">
        <p>{{ $quote->full_name }} · {{ $quote->email }}</p>
        <p>Debt-to-income {{ number_format($quote->debt_to_income * 100, 1) }}%</p>
        @if ($quote->eligible)
            <p>Annual rate {{ number_format($quote->annual_rate * 100, 2) }}%</p>
            <p>Monthly payment ₹{{ number_format($quote->monthly_payment_cents / 100, 2) }}</p>
        @else
            <p>{{ $quote->reason }}</p>
        @endif
    </div>
    <p><a href="/">Check another quote</a></p>
</main>
</body>
</html>
