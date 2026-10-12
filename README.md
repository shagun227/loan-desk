# Loan Desk

Portfolio project by Shagun Sharma. A Laravel application that quotes a loan from income, monthly debt, requested amount, term, and credit band, then stores the result. Original sample code, not a lender's system.

The application skeleton is Laravel 12. The quote flow, calculator, migration, and tests were added on top.

## Stack

PHP 8.2, Laravel 12, SQLite, Blade.

## Run

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve
```

Open `http://127.0.0.1:8000`.

## API

`POST /api/quotes` with JSON:

```json
{
  "full_name": "Asha Verma",
  "email": "asha@example.com",
  "annual_income": 1200000,
  "monthly_debt": 20000,
  "amount": 300000,
  "term_months": 36,
  "credit_band": "good"
}
```

Amounts in the form and API are rupees. The database stores paise in `*_cents` columns.

## Rules

- Credit bands: excellent 8.5%, good 11%, fair 14.5%
- Terms longer than 36 months add 0.5%
- Debt-to-income above 43% is not eligible
- A request above five times annual income is not eligible

## Tests

```bash
php artisan test
```

## Layout

```
app/Services/LoanCalculator.php     eligibility and monthly payment
app/Http/Controllers/QuoteController.php
app/Models/LoanQuote.php
database/migrations/*_create_loan_quotes_table.php
resources/views/quotes/             form and result
tests/Unit/LoanCalculatorTest.php   calculator without the database
tests/Feature/QuoteTest.php         form and JSON API
```
