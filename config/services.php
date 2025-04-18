<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sms' => [
        'driver' => env('SMS_DRIVER', 'twilio'), // or any other provider
        'key' => env('SMS_API_KEY'),
        'secret' => env('SMS_API_SECRET'),
        'endpoint' => env('SMS_API_ENDPOINT'),
        'from' => env('SMS_FROM_NUMBER'),
        'debug' => env('SMS_DEBUG', false),
        
        // Twilio specific settings
        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'from' => env('TWILIO_FROM_NUMBER'),
        ],
        
        // Other provider specific settings can be added here
        'nexmo' => [
            'key' => env('NEXMO_KEY'),
            'secret' => env('NEXMO_SECRET'),
            'from' => env('NEXMO_FROM_NUMBER'),
        ],

        // SMS sending options
        'options' => [
            'retry_attempts' => env('SMS_RETRY_ATTEMPTS', 3),
            'retry_delay' => env('SMS_RETRY_DELAY', 5), // seconds
            'timeout' => env('SMS_TIMEOUT', 30), // seconds
            'verify_ssl' => env('SMS_VERIFY_SSL', true),
        ],

        // Rate limiting
        'rate_limit' => [
            'enabled' => env('SMS_RATE_LIMIT_ENABLED', true),
            'max_attempts' => env('SMS_RATE_LIMIT_MAX_ATTEMPTS', 10),
            'decay_minutes' => env('SMS_RATE_LIMIT_DECAY_MINUTES', 1),
        ],
    ],

    // Add other third-party service configurations here
    'google' => [
        'maps' => [
            'api_key' => env('GOOGLE_MAPS_API_KEY'),
        ],
    ],
];
