# Application payment split

Applicants pay NGN 11,000: NGN 10,000 for the application and NGN 1,000 for administration. All API amounts are in kobo.

Create and verify the second bank account as a Paystack subaccount under the existing merchant account. Configure its code in the server environment:

```dotenv
PAYSTACK_ADMIN_SUBACCOUNT=ACCT_your_verified_subaccount_code
```

Use a test subaccount with test keys and a live subaccount with live keys. Keep the existing main settlement account. After changing the environment, refresh Laravel's configuration cache with `php artisan config:cache` on the deployment server.

Checkout sends `amount=1100000`, `transaction_charge=1000000`, the configured `subaccount`, and `bearer=subaccount`. Paystack reserves NGN 10,000 for the main account and deducts its fee for the entire transaction from the subaccount's NGN 1,000 share. The subaccount receives the remainder. Its share must cover Paystack's applicable fee; an insufficient share is rejected by Paystack.

An absent or malformed subaccount code blocks checkout instead of collecting an unsplit payment. No new keys or bank details are required in browser code.

Before live rollout, make a test-mode application purchase and verify the amount, main-account share, subaccount share, and fee bearer in Paystack. Confirm the application is created after successful verification and the receipt displays the breakdown. Check failed/cancelled payments as well. Automated tests mock Paystack and do not validate actual settlement.

Reference: https://paystack.com/docs/payments/split-payments/
