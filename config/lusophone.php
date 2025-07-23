<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Lusophone Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the Laravel Lusophone package.
    | This package automatically detects and adapts to all Portuguese-speaking
    | countries with smart region detection and cultural context awareness.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Smart Environment Detection
    |--------------------------------------------------------------------------
    |
    | When enabled, the package automatically detects:
    | - LOCAL environments: Defaults to Mozambique (MZ)
    | - ONLINE environments: Auto-detects user's country via IP/headers
    |
    */

    'auto_detect' => env('LUSOPHONE_AUTO_DETECT', true),

    /*
    |--------------------------------------------------------------------------
    | Auto Set Locale
    |--------------------------------------------------------------------------
    |
    | Automatically set Laravel's locale based on detected region.
    | When enabled, the package will set App::setLocale() automatically.
    | Perfect for Breeze and Jetstream integration.
    |
    */

    'auto_set_locale' => env('LUSOPHONE_AUTO_SET_LOCALE', true),

    /*
    |--------------------------------------------------------------------------
    | Default Region
    |--------------------------------------------------------------------------
    |
    | The default region for local development and fallback scenarios.
    | Changed to MZ (Mozambique) as primary development environment.
    | Supported regions: PT, BR, MZ, AO, CV, GW, ST, TL
    |
    */

    'default_region' => env('LUSOPHONE_DEFAULT_REGION', 'MZ'),

    /*
    |--------------------------------------------------------------------------
    | Force Region
    |--------------------------------------------------------------------------
    |
    | Force a specific region, useful for testing or single-country applications.
    | When set, this overrides all auto-detection.
    |
    */

    'force_region' => env('LUSOPHONE_FORCE_REGION'),

    /*
    |--------------------------------------------------------------------------
    | Breeze & Jetstream Integration
    |--------------------------------------------------------------------------
    |
    | Enable seamless integration with Laravel Breeze and Jetstream.
    | This automatically adapts authentication and form validation messages.
    |
    */

    'breeze_integration' => env('LUSOPHONE_BREEZE_INTEGRATION', true),
    'jetstream_integration' => env('LUSOPHONE_JETSTREAM_INTEGRATION', true),

    /*
    |--------------------------------------------------------------------------
    | Cultural Context
    |--------------------------------------------------------------------------
    |
    | Enable cultural context detection to adapt formality levels and
    | terminology based on the application context (business, casual, government).
    |
    */

    'cultural_context' => env('LUSOPHONE_CULTURAL_CONTEXT', true),

    /*
    |--------------------------------------------------------------------------
    | Smart Local Detection
    |--------------------------------------------------------------------------
    |
    | Configure how the package detects local vs online environments.
    | This ensures MZ defaults locally while maintaining online auto-detection.
    |
    */

    'local_detection' => [
        'enabled' => env('LUSOPHONE_LOCAL_DETECTION', true),
        'default_local_region' => env('LUSOPHONE_LOCAL_REGION', 'MZ'),
        'detect_dev_ports' => env('LUSOPHONE_DETECT_DEV_PORTS', true),
        'local_domains' => ['.local', '.test', '.localhost', 'localhost'],
        'dev_ports' => [8000, 8080, 3000, 5173, 5174, 9000],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Configure caching for region detection and translations to improve
    | performance. Cache TTL is in seconds.
    |
    */

    'cache_detections' => env('LUSOPHONE_CACHE', true),
    'cache_ttl' => env('LUSOPHONE_CACHE_TTL', 3600), // 1 hour

    /*
    |--------------------------------------------------------------------------
    | Validation Settings
    |--------------------------------------------------------------------------
    |
    | Configure validation behavior for different document types and regions.
    |
    */

    'validation' => [
        'strict_mode' => env('LUSOPHONE_STRICT_VALIDATION', true),
        'allow_international_phone' => env('LUSOPHONE_INTL_PHONE', true),
        'require_country_code' => env('LUSOPHONE_REQUIRE_COUNTRY_CODE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported Regions
    |--------------------------------------------------------------------------
    |
    | Configuration for all supported Lusophone regions.
    | You can customize these settings per region as needed.
    |
    */

    'regions' => [
        'MZ' => [
            'enabled' => true,
            'locale' => 'pt_MZ',
            'timezone' => 'Africa/Maputo',
            'formality_default' => 'mixed',
            'primary_development' => true, // Indicates this is the primary dev region
        ],
        'PT' => [
            'enabled' => true,
            'locale' => 'pt_PT',
            'timezone' => 'Europe/Lisbon',
            'formality_default' => 'formal',
        ],
        'BR' => [
            'enabled' => true,
            'locale' => 'pt_BR',
            'timezone' => 'America/Sao_Paulo',
            'formality_default' => 'informal',
        ],
        'AO' => [
            'enabled' => true,
            'locale' => 'pt_AO',
            'timezone' => 'Africa/Luanda',
            'formality_default' => 'formal',
        ],
        'CV' => [
            'enabled' => true,
            'locale' => 'pt_CV',
            'timezone' => 'Atlantic/Cape_Verde',
            'formality_default' => 'formal',
        ],
        'GW' => [
            'enabled' => true,
            'locale' => 'pt_GW',
            'timezone' => 'Africa/Bissau',
            'formality_default' => 'formal',
        ],
        'ST' => [
            'enabled' => true,
            'locale' => 'pt_ST',
            'timezone' => 'Africa/Sao_Tome',
            'formality_default' => 'formal',
        ],
        'TL' => [
            'enabled' => true,
            'locale' => 'pt_TL',
            'timezone' => 'Asia/Dili',
            'formality_default' => 'formal',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Integration
    |--------------------------------------------------------------------------
    |
    | Configure deep integration with Laravel's authentication and UI packages.
    |
    */

    'laravel_integration' => [
        'override_auth_translations' => env('LUSOPHONE_OVERRIDE_AUTH', true),
        'override_validation_translations' => env('LUSOPHONE_OVERRIDE_VALIDATION', true),
        'override_pagination_translations' => env('LUSOPHONE_OVERRIDE_PAGINATION', true),
        'override_password_translations' => env('LUSOPHONE_OVERRIDE_PASSWORDS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Debug Settings
    |--------------------------------------------------------------------------
    |
    | Debug options for development and troubleshooting.
    |
    */

    'debug' => [
        'log_detections' => env('LUSOPHONE_LOG_DETECTIONS', false),
        'show_detection_headers' => env('LUSOPHONE_DEBUG_HEADERS', false),
        'log_environment_detection' => env('LUSOPHONE_LOG_ENV_DETECTION', true),
    ],

];