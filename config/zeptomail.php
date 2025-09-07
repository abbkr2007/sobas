<?php

// config/zeptomail.php

return [
    'api_key' => env('ZEPTOMAIL_API_KEY'),
    'base_url' => env('ZEPTOMAIL_BASE_URL', 'https://api.zeptomail.com/v1.1/'),
    'mailbox' => env('ZEPTOMAIL_MAILBOX'),
];
