<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Translator Provider Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the translator providers below you wish
    | to use as your default provider for translator.
    |
    */

    'default' => env('TRANSLATOR_PROVIDER', 'illuminate'),

    /*
    |--------------------------------------------------------------------------
    | Translator Providers
    |--------------------------------------------------------------------------
    |
    | Below are all of the translator providers defined for your application.
    | An example configuration is provided for each translator provider which
    | is supported. You're free to add / remove providers.
    |
    */

    'providers' => [

        'illuminate' => [
            'provider' => 'illuminate',
        ],

        'apilayer' => [
            'provider' => 'apilayer',
            'apikey' => env('TRANSLATOR_APILAYER_APIKEY'),
        ],

    ],

];
