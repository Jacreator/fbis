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

  'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => 'http://your-callback-url',
  ],
  'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => 'http://your-callback-url',
  ],
  'apple' => [
    'client_id' => env('APPLE_CLIENT_ID'),
    'client_secret' => env('APPLE_CLIENT_SECRET'),
    'redirect' => 'http://your-callback-url',
  ],
  'monnify' => [
    'api_key' => env('MONNIFY_API_KEY'),
    'secret_key' => env('MONNIFY_SECRET_KEY'),
    'url' => env('MONNIFY_URL'),
    'account_number' => env('MONNIFY_ACCOUNT_NUMBER'),
    'contract' => env('MONNIFY_CONTRACT_CODE'),
  ],
  'shago' => [
    'url' => env('SHAGO_URL'),
    'key' => env('SHAGO_KEY'),
  ],
  'biller' => [
    'url' => env('BILLER_URL'),
    'key' => env('BILLER_KEY'),
    'agentId' => env('BILLER_AGENT_ID'),
    'agentReference' => env('BILLER_AGENT_REF'),
  ],

];
