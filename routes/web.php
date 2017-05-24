<?php

Route::name('webhooks.payments.mollie')->post(config('payable.mollie.webhook_url', '/webhooks/payments/mollie'), '\SanderVanHooft\PayableRedirect\Http\Controllers\WebhookController@handle');
