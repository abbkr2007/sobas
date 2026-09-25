# Confirmation letter numbers

After deploying, run `php artisan migrate --force` before opening confirmation letters.

The first confirmation-letter download assigns and stores a number for confirmed applicants:

- Matric Science: `26100001`, `26100002`, etc.
- All Remedial programmes share a sequence: `2626001`, `2626002`, etc.

The two-digit year (26 for 2026) is the year of first issuance, using the application's timezone. Each programme code has a separate sequence starting at one each year. Codes `10` and `26` remain fixed. Padding is a minimum width; numbers continue beyond 999 or 9999 without truncation.

Saved numbers remain unchanged on repeat downloads, including in later years. Existing confirmed applicants receive numbers in first-download order. Other Matric programmes retain their existing letter numbering until codes are specified for them.

The new number replaces the matric number in both number positions on the confirmation letter only. User matric numbers, login, application links, admission letters, and exports are unchanged. Database transactions, row locks, and a unique number constraint protect allocation during concurrent requests. Automated tests use SQLite and do not simulate concurrent production MySQL connections.
