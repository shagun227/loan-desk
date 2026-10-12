# Architecture

`LoanCalculator` is a plain PHP class. It does not read the database or the request. The controller converts rupee inputs to paise, asks the calculator for a decision, and stores both the inputs and the decision on `loan_quotes`.

The web form posts to `quotes.store`. The JSON API is `POST /api/quotes` and uses the same form request, so validation stays in one place. API routes are registered from `bootstrap/app.php`.

Unit tests instantiate the calculator directly and do not boot the HTTP kernel. Feature tests use `RefreshDatabase` against the in-memory SQLite database configured in `phpunit.xml`.
