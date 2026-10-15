# System design

Laravel quote flow based on income, debt, amount, term, and credit band.

The system is one deployable unit. A request enters through the edge shown in the diagram, passes the rules for this product, and is stored as described in the database note. There is no shared login with the other portfolio repositories.

Stack: PHP, Laravel, SQLite or MySQL.
