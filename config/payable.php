<?php

return [
    'mollie' => [
        'key' => env('MOLLIE_KEY', 'test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'),
        'webhook_url' => env('MOLLIE_WEBHOOK_URL', '/webhooks/payments/mollie'),
    ],
];