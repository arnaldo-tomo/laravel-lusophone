<?php

namespace ArnaldoTomo\LaravelLusophone;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use ArnaldoTomo\LaravelLusophone\Integrations\BreezeIntegration;
use ArnaldoTomo\LaravelLusophone\Integrations\JetstreamIntegration;

class LaravelLusophoneServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../resources/lang' => $this->app->langPath(),
        ], 'lusophone-lang');

        $this->publishes([
            __DIR__.'/../config/lusophone.php' => config_path('lusophone.php'),
        ], 'lusophone-config');

        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\LusophoneSetupCommand::class,
                Console\Commands\LusophoneAnalyzeCommand::class,
                Console\Commands\LusophoneDetectCommand::class,
            ]);
        }

        $this->registerLusophoneValidators();
        $this->registerLusophoneMacros();
        $this->autoDetectAndSetLocale();
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'lusophone');
        $this->registerIntegrations();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/lusophone.php', 'lusophone');

        // Register core services as singletons
        $this->app->singleton(RegionDetector::class);
        $this->app->singleton(ValidationManager::class);
        $this->app->singleton(CurrencyFormatter::class);
        $this->app->singleton(LusophoneTranslator::class);

        // Register integrations
        $this->app->singleton(BreezeIntegration::class);
        $this->app->singleton(JetstreamIntegration::class);

        // Register main manager
        $this->app->bind('lusophone', function ($app) {
            return $app->make(LusophoneManager::class);
        });
    }

    protected function registerLusophoneValidators(): void
    {
        Validator::extend('lusophone_tax_id', function ($attribute, $value, $parameters, $validator) {
            $detector = app(RegionDetector::class);
            $region = $detector->detect();
            return app(ValidationManager::class)->validateTaxId($value, $region);
        }, 'O :attribute não é um documento fiscal válido.');

        Validator::extend('lusophone_phone', function ($attribute, $value, $parameters, $validator) {
            $detector = app(RegionDetector::class);
            $region = $detector->detect();
            return app(ValidationManager::class)->validatePhone($value, $region);
        }, 'O :attribute não é um número de telefone válido.');

        Validator::extend('lusophone_postal', function ($attribute, $value, $parameters, $validator) {
            $detector = app(RegionDetector::class);
            $region = $detector->detect();
            return app(ValidationManager::class)->validatePostalCode($value, $region);
        }, 'O :attribute não é um código postal válido.');

        Validator::extend('nif_portugal', function ($attribute, $value) {
            return app(ValidationManager::class)->validatePortugueseNIF($value);
        }, 'O :attribute deve ser um NIF português válido.');

        Validator::extend('nuit_mozambique', function ($attribute, $value) {
            return app(ValidationManager::class)->validateMozambiqueNUIT($value);
        }, 'O :attribute deve ser um NUIT moçambicano válido.');

        Validator::extend('cpf_brazil', function ($attribute, $value) {
            return app(ValidationManager::class)->validateBrazilCPF($value);
        }, 'O :attribute deve ser um CPF brasileiro válido.');
    }

    protected function registerLusophoneMacros(): void
    {
        \Illuminate\Support\Str::macro('lusophoneCurrency', function ($amount, $region = null) {
            return app(CurrencyFormatter::class)->format($amount, $region);
        });

        \Illuminate\Support\Str::macro('lusophoneNumber', function ($number, $decimals = 2, $region = null) {
            return app(CurrencyFormatter::class)->formatNumber($number, $decimals, $region);
        });

        // Add translation macro
        \Illuminate\Support\Str::macro('lusophoneTranslate', function ($key, $replace = [], $region = null) {
            return app(LusophoneTranslator::class)->translate($key, $replace, $region);
        });

        \Illuminate\Support\Collection::macro('lusophoneCountries', function () {
            return collect([
                'MZ' => 'Moçambique',     // MZ first as primary development region
                'PT' => 'Portugal',
                'BR' => 'Brasil', 
                'AO' => 'Angola',
                'CV' => 'Cabo Verde',
                'GW' => 'Guiné-Bissau',
                'ST' => 'São Tomé e Príncipe',
                'TL' => 'Timor-Leste',
            ]);
        });
    }

    protected function autoDetectAndSetLocale(): void
    {
        if (!config('lusophone.auto_detect', true)) {
            return;
        }

        $detector = app(RegionDetector::class);
        $region = $detector->detect();
        
        // Log environment detection for debugging
        if (config('lusophone.debug.log_environment_detection', true)) {
            \Illuminate\Support\Facades\Log::info('Laravel Lusophone: Environment detection', [
                'environment_type' => $detector->getEnvironmentType(),
                'detected_region' => $region,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
        
        $localeMap = [
            'MZ' => 'pt_MZ',    // Primary locale
            'PT' => 'pt_PT', 
            'AO' => 'pt_AO',
            'BR' => 'pt_BR', 
            'CV' => 'pt_CV', 
            'GW' => 'pt_GW',
            'ST' => 'pt_ST', 
            'TL' => 'pt_TL',
        ];

        $locale = $localeMap[$region] ?? 'pt_MZ';

        if (config('lusophone.auto_set_locale', true)) {
            App::setLocale($locale);
            
            // Also set fallback locale to ensure proper Portuguese is used
            App::setFallbackLocale('pt');
        }

        config(['lusophone.detected_region' => $region]);
        config(['lusophone.detected_locale' => $locale]);
        config(['lusophone.environment_type' => $detector->getEnvironmentType()]);
    }

    protected function registerIntegrations(): void
    {
        // Register Breeze integration if enabled
        if (config('lusophone.breeze_integration', true)) {
            $this->app->make(BreezeIntegration::class)->register();
        }

        // Register Jetstream integration if enabled  
        if (config('lusophone.jetstream_integration', true)) {
            $this->app->make(JetstreamIntegration::class)->register();
        }

        // Override Laravel's default translations if configured
        $this->overrideLaravelTranslations();
    }

    protected function overrideLaravelTranslations(): void
    {
        $integrationConfig = config('lusophone.laravel_integration', []);

        // Override auth translations
        if ($integrationConfig['override_auth_translations'] ?? true) {
            $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'auth');
        }

        // Override validation translations
        if ($integrationConfig['override_validation_translations'] ?? true) {
            $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'validation');
        }

        // Override pagination translations
        if ($integrationConfig['override_pagination_translations'] ?? true) {
            $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'pagination');
        }

        // Override password reset translations
        if ($integrationConfig['override_password_translations'] ?? true) {
            $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'passwords');
        }
    }
}