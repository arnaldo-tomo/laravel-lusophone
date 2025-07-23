<?php

namespace ArnaldoTomo\LaravelLusophone\Integrations;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use ArnaldoTomo\LaravelLusophone\Facades\Lusophone;

class JetstreamIntegration
{
    public function register(): void
    {
        if (!config('lusophone.jetstream_integration', true)) {
            return;
        }

        $this->registerViewComposer();
        $this->registerBladeDirectives();
        $this->overrideJetstreamTranslations();
    }

    protected function registerViewComposer(): void
    {
        // Compose views for Jetstream components
        View::composer([
            'profile.*',
            'teams.*', 
            'api.*',
            'dashboard',
            'navigation-menu',
            'welcome'
        ], function ($view) {
            $region = Lusophone::detectRegion();
            $countryInfo = Lusophone::getCountryInfo($region);
            
            $view->with([
                'lusophoneRegion' => $region,
                'lusophoneCountry' => $countryInfo['name'],
                'lusophoneCurrency' => $countryInfo['currency_symbol'],
                'lusophoneFormality' => $countryInfo['formality'],
                'lusophoneLabels' => $this->getJetstreamLabels(),
            ]);
        });
    }

    protected function registerBladeDirectives(): void
    {
        // @teamfield directive for team-related fields
        Blade::directive('teamfield', function ($expression) {
            $field = str_replace(['(', ')', "'", '"'], '', $expression);
            
            return match($field) {
                'team_name' => "<?php echo Lusophone::detectRegion() === 'PT' ? 'Nome da Equipa' : 'Nome da Equipe'; ?>",
                'owner' => "<?php echo Lusophone::detectRegion() === 'PT' ? 'Proprietário' : 'Dono'; ?>",
                'member' => "<?php echo Lusophone::detectRegion() === 'PT' ? 'Membro' : 'Membro'; ?>",
                'role' => "<?php echo Lusophone::detectRegion() === 'PT' ? 'Função' : 'Papel'; ?>",
                default => "<?php echo '{$field}'; ?>",
            };
        });

        // @profile directive for profile-related fields
        Blade::directive('profile', function ($expression) {
            $field = str_replace(['(', ')', "'", '"'], '', $expression);
            
            return match($field) {
                'two_factor' => "<?php echo Lusophone::detectRegion() === 'PT' ? 'Autenticação de Dois Factores' : 'Autenticação de Dois Fatores'; ?>",
                'browser_sessions' => "<?php echo Lusophone::detectRegion() === 'PT' ? 'Sessões do Navegador' : 'Sessões do Browser'; ?>",
                'delete_account' => "<?php echo Lusophone::detectRegion() === 'PT' ? 'Eliminar Conta' : 'Excluir Conta'; ?>",
                default => "<?php echo '{$field}'; ?>",
            };
        });
    }

    protected function overrideJetstreamTranslations(): void
    {
        $translations = [
            // Profile Management
            'Profile Information' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Informações do Perfil',
                    'BR' => 'Informações do Perfil',
                    'MZ' => 'Informação do Perfil',
                    default => 'Informação do Perfil',
                };
            },
            'Update your account\'s profile information and email address.' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Actualize as informações do perfil e endereço de correio electrónico da sua conta.',
                    'BR' => 'Atualize as informações do perfil e endereço de e-mail da sua conta.',
                    'MZ' => 'Actualize as informações do perfil e endereço de email da sua conta.',
                    default => 'Actualize as informações do perfil e endereço de email da sua conta.',
                };
            },
            
            // Team Management
            'Team Settings' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Configurações da Equipa',
                    'BR', 'MZ' => 'Configurações da Equipe',
                    default => 'Configurações da Equipe',
                };
            },
            'Create Team' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Criar Equipa',
                    'BR', 'MZ' => 'Criar Equipe',
                    default => 'Criar Equipe',
                };
            },
            'Team Members' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Membros da Equipa',
                    'BR', 'MZ' => 'Membros da Equipe',
                    default => 'Membros da Equipe',
                };
            },
            
            // API Management
            'API Tokens' => 'Tokens da API',
            'Create API Token' => 'Criar Token da API',
            'Manage API tokens that allow third-party services to access this application on your behalf.' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Gerir tokens da API que permitem a serviços terceiros aceder a esta aplicação em seu nome.',
                    'BR' => 'Gerenciar tokens da API que permitem a serviços terceiros acessar esta aplicação em seu nome.',
                    'MZ' => 'Gerir tokens da API que permitem a serviços terceiros acessar esta aplicação em seu nome.',
                    default => 'Gerir tokens da API que permitem a serviços terceiros acessar esta aplicação em seu nome.',
                };
            },
            
            // Two Factor Authentication
            'Two Factor Authentication' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Autenticação de Dois Factores',
                    'BR', 'MZ' => 'Autenticação de Dois Fatores',
                    default => 'Autenticação de Dois Fatores',
                };
            },
            'Add additional security to your account using two factor authentication.' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Adicione segurança extra à sua conta usando autenticação de dois factores.',
                    'BR' => 'Adicione segurança extra à sua conta usando autenticação de dois fatores.',
                    'MZ' => 'Adicione segurança extra à sua conta usando autenticação de dois fatores.',
                    default => 'Adicione segurança extra à sua conta usando autenticação de dois fatores.',
                };
            },
            
            // Browser Sessions
            'Browser Sessions' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Sessões do Navegador',
                    'BR', 'MZ' => 'Sessões do Browser',
                    default => 'Sessões do Browser',
                };
            },
            'Manage and log out your active sessions on other browsers and devices.' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Gira e termine as suas sessões activas noutros navegadores e dispositivos.',
                    'BR' => 'Gerencie e encerre suas sessões ativas em outros browsers e dispositivos.',
                    'MZ' => 'Gira e termine as suas sessões ativas noutros browsers e dispositivos.',
                    default => 'Gira e termine as suas sessões ativas noutros browsers e dispositivos.',
                };
            },
            
            // Delete Account
            'Delete Account' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Eliminar Conta',
                    'BR', 'MZ' => 'Excluir Conta',
                    default => 'Excluir Conta',
                };
            },
            'Permanently delete your account.' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Eliminar permanentemente a sua conta.',
                    'BR', 'MZ' => 'Excluir permanentemente a sua conta.',
                    default => 'Excluir permanentemente a sua conta.',
                };
            },
            
            // Common Actions
            'Save' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Guardar',
                    'BR', 'MZ' => 'Salvar',
                    default => 'Salvar',
                };
            },
            'Cancel' => 'Cancelar',
            'Delete' => function () {
                $region = Lusophone::detectRegion();
                return match($region) {
                    'PT', 'AO' => 'Eliminar',
                    'BR', 'MZ' => 'Excluir',
                    default => 'Excluir',
                };
            },
        ];

        foreach ($translations as $key => $value) {
            View::share($key, is_callable($value) ? $value() : $value);
        }
    }

    protected function getJetstreamLabels(): array
    {
        $region = Lusophone::detectRegion();
        
        return [
            // Profile fields
            'name' => 'Nome',
            'email' => match($region) {
                'PT', 'AO' => 'Correio Electrónico',
                'BR', 'MZ' => 'E-mail',
                default => 'E-mail',
            },
            'current_password' => match($region) {
                'PT', 'AO' => 'Palavra-passe Actual',
                'BR' => 'Senha Atual',
                'MZ' => 'Senha Actual',
                default => 'Senha Actual',
            },
            'new_password' => match($region) {
                'PT', 'AO' => 'Nova Palavra-passe',
                'BR', 'MZ' => 'Nova Senha',
                default => 'Nova Senha',
            },
            'confirm_password' => match($region) {
                'PT', 'AO' => 'Confirmar Palavra-passe',
                'BR', 'MZ' => 'Confirmar Senha',
                default => 'Confirmar Senha',
            },
            
            // Team fields
            'team_name' => match($region) {
                'PT', 'AO' => 'Nome da Equipa',
                'BR', 'MZ' => 'Nome da Equipe',
                default => 'Nome da Equipe',
            },
            'team_owner' => match($region) {
                'PT', 'AO' => 'Proprietário da Equipa',
                'BR' => 'Dono da Equipe',
                'MZ' => 'Proprietário da Equipe',
                default => 'Proprietário da Equipe',
            },
            
            // API Token fields
            'token_name' => 'Nome do Token',
            'permissions' => 'Permissões',
            'created' => function () use ($region) {
                return match($region) {
                    'PT', 'AO' => 'Criado',
                    'BR', 'MZ' => 'Criado',
                    default => 'Criado',
                };
            },
            'last_used' => function () use ($region) {
                return match($region) {
                    'PT', 'AO' => 'Último Uso',
                    'BR', 'MZ' => 'Último Uso',
                    default => 'Último Uso',
                };
            },
        ];
    }

    public function getJetstreamValidationRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'team_name' => 'required|string|max:255',
            'token_name' => 'required|string|max:255',
        ];
    }
}