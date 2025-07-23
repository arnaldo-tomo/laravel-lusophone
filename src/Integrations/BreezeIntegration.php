<?php

namespace ArnaldoTomo\LaravelLusophone\Integrations;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use ArnaldoTomo\LaravelLusophone\Facades\Lusophone;

class BreezeIntegration
{
    public function register(): void
    {
        if (!config('lusophone.breeze_integration', true)) {
            return;
        }

        $this->registerViewComposer();
        $this->registerBladeDirectives();
        $this->overrideAuthTranslations();
    }

    protected function registerViewComposer(): void
    {
        View::composer(['auth.*', 'profile.*', 'dashboard'], function ($view) {
            $region = Lusophone::detectRegion();
            $countryInfo = Lusophone::getCountryInfo($region);
            
            $view->with([
                'lusophoneRegion' => $region,
                'lusophoneCountry' => $countryInfo['name'],
                'lusophoneCurrency' => $countryInfo['currency_symbol'],
                'lusophoneFormality' => $countryInfo['formality'],
            ]);
        });
    }

    protected function registerBladeDirectives(): void
    {
        // @lusophone directive for regional content
        Blade::directive('lusophone', function ($expression) {
            return "<?php if(Lusophone::detectRegion() === {$expression}): ?>";
        });

        Blade::directive('endlusophone', function () {
            return '<?php endif; ?>';
        });

        // @currency directive for money formatting
        Blade::directive('currency', function ($expression) {
            return "<?php echo Lusophone::formatCurrency({$expression}); ?>";
        });

        // @field directive for regional field names
        Blade::directive('field', function ($expression) {
            $parts = explode(',', str_replace(['(', ')', "'", '"'], '', $expression));
            $field = trim($parts[0]);
            
            if ($field === 'tax_id') {
                return "<?php echo Lusophone::getTaxIdFieldName(); ?>";
            }
            if ($field === 'phone') {
                return "<?php echo Lusophone::getPhoneFieldName(); ?>";
            }
            
            return "<?php echo '{$field}'; ?>";
        });

        // @translate directive for explicit regional translation
        Blade::directive('translate', function ($expression) {
            return "<?php echo ArnaldoTomo\\LaravelLusophone\\TranslationInterceptor::intercept({$expression}) ?? __({$expression}); ?>";
        });

        // @regional directive for region-specific content
        Blade::directive('regional', function ($expression) {
            return "<?php echo ArnaldoTomo\\LaravelLusophone\\Facades\\Lusophone::translate({$expression}); ?>";
        });

        // @contextual directive for cultural context
        Blade::directive('contextual', function ($expression) {
            $parts = explode(',', str_replace(['(', ')', "'", '"'], '', $expression));
            $key = trim($parts[0]);
            $context = trim($parts[1] ?? 'general');
            
            return "<?php echo ArnaldoTomo\\LaravelLusophone\\Facades\\Lusophone::contextualTranslate('{$key}', '{$context}'); ?>";
        });
    }

    protected function overrideAuthTranslations(): void
    {
        // Override common Breeze translation keys with regional variants
        $translations = [
            'Email' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Correio Electrónico',
                    'BR', 'MZ' => 'E-mail',
                    default => 'E-mail',
                };
            },
            'Password' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Palavra-passe',
                    'BR', 'MZ' => 'Senha',
                    default => 'Senha',
                };
            },
            'Remember me' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT' => 'Lembrar-me',
                    'BR' => 'Lembrar de mim',
                    'MZ', 'AO' => 'Lembrar-me',
                    default => 'Lembrar-me',
                };
            },
            'Log in' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Iniciar Sessão',
                    'BR' => 'Entrar',
                    'MZ' => 'Entrar',
                    default => 'Entrar',
                };
            },
            'Register' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Registar',
                    'BR', 'MZ' => 'Registrar',
                    default => 'Registrar',
                };
            },
            'Name' => 'Nome',
            'Confirm Password' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Confirmar Palavra-passe',
                    'BR', 'MZ' => 'Confirmar Senha',
                    default => 'Confirmar Senha',
                };
            },
            'Forgot your password?' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Esqueceu a palavra-passe?',
                    'BR' => 'Esqueceu sua senha?',
                    'MZ' => 'Esqueceu a senha?',
                    default => 'Esqueceu a senha?',
                };
            },
            'Already registered?' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT' => 'Já tem uma conta?',
                    'BR' => 'Já tem uma conta?',
                    'MZ', 'AO' => 'Já tem conta?',
                    default => 'Já tem conta?',
                };
            },
        ];

        // Register translation overrides
        foreach ($translations as $key => $value) {
            View::share($key, is_callable($value) ? $value() : $value);
        }
    }

    public function getRegionalLabels(): array
    {
        $region = Lusophone::detectRegion();
        
        return [
            'email' => match($region) {
                'PT', 'AO' => 'Correio Electrónico',
                'BR', 'MZ' => 'E-mail',
                default => 'E-mail',
            },
            'password' => match($region) {
                'PT', 'AO' => 'Palavra-passe',
                'BR', 'MZ' => 'Senha',
                default => 'Senha',
            },
            'name' => 'Nome',
            'phone' => Lusophone::getPhoneFieldName($region),
            'tax_id' => Lusophone::getTaxIdFieldName($region),
            'address' => match($region) {
                'PT', 'AO' => 'Morada',
                'BR', 'MZ' => 'Endereço',
                default => 'Endereço',
            },
            'city' => 'Cidade',
            'postal_code' => 'Código Postal',
            'country' => 'País',
        ];
    }

    public function getRegionalValidationRules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|lusophone_phone',
            'tax_id' => 'nullable|lusophone_tax_id',
            'postal_code' => 'nullable|lusophone_postal',
        ];
    }
}