<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loan Desk</title>
    <style>
        body { margin: 0; background: #f6f3ee; color: #1c1917; font-family: Georgia, serif; }
        main { max-width: 32rem; margin: 0 auto; padding: 1.5rem; }
        form, .card { background: #fff; border: 1px solid #e4ddd2; border-radius: 8px; padding: 1rem; }
        label { display: block; margin-bottom: 0.7rem; }
        input, select { display: block; width: 100%; margin-top: 0.25rem; font: inherit; padding: 0.4rem; box-sizing: border-box; }
        button { background: #1b3a4b; color: #fff; border: 0; padding: 0.5rem 0.9rem; font: inherit; }
        .error { color: #9f1239; margin: 0 0 0.6rem; }
    </style>
</head>
<body>
<main>
    <h1>Loan Desk</h1>
    <p>Check a sample quote from income, monthly debt, amount, and term. Amounts are in rupees.</p>
    <form method="post" action="{{ route('quotes.store') }}">
        @csrf
        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif
        <label>Name <input name="full_name" value="{{ old('full_name') }}" required></label>
        <label>Email <input name="email" type="email" value="{{ old('email') }}" required></label>
        <label>Annual income <input name="annual_income" inputmode="decimal" value="{{ old('annual_income') }}" required></label>
        <label>Monthly debt <input name="monthly_debt" inputmode="decimal" value="{{ old('monthly_debt', '0') }}" required></label>
        <label>Amount requested <input name="amount" inputmode="decimal" value="{{ old('amount') }}" required></label>
        <label>Term
            <select name="term_months">
                @foreach ([12, 24, 36, 48, 60] as $term)
                    <option value="{{ $term }}" @selected((string) old('term_months', '36') === (string) $term)>{{ $term }} months</option>
                @endforeach
            </select>
        </label>
        <label>Credit band
            <select name="credit_band">
                @foreach (['excellent' => 'Excellent', 'good' => 'Good', 'fair' => 'Fair'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('credit_band', 'good') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <button type="submit">Get quote</button>
    </form>
</main>
</body>
</html>
