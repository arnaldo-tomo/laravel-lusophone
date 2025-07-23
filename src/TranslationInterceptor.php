<?php
namespace ArnaldoTomo\LaravelLusophone;

use Illuminate\Support\Facades\Lang;
use ArnaldoTomo\LaravelLusophone\Facades\Lusophone;

class TranslationInterceptor
{
    protected static array $commonTranslations = [
        // Dashboard & Authentication
        "You're logged in!" => [
            'MZ' => 'Está conectado!',
            'PT' => 'Está ligado!',
            'BR' => 'Você está logado!',
            'AO' => 'Está conectado!',
            'default' => 'Está conectado!'
        ],
        'Welcome!' => [
            'MZ' => 'Bem-vindo!',
            'PT' => 'Bem-vindo!', 
            'BR' => 'Bem-vindo!',
            'AO' => 'Bem-vindo!',
            'default' => 'Bem-vindo!'
        ],
        'Dashboard' => [
            'MZ' => 'Painel',
            'PT' => 'Painel de Controlo',
            'BR' => 'Painel',
            'AO' => 'Painel',
            'default' => 'Painel'
        ],
        'Profile' => [
            'MZ' => 'Perfil',
            'PT' => 'Perfil',
            'BR' => 'Perfil',  
            'AO' => 'Perfil',
            'default' => 'Perfil'
        ],
        'Settings' => [
            'MZ' => 'Configurações',
            'PT' => 'Definições',
            'BR' => 'Configurações',
            'AO' => 'Configurações', 
            'default' => 'Configurações'
        ],
        'Logout' => [
            'MZ' => 'Sair',
            'PT' => 'Terminar Sessão',
            'BR' => 'Sair',
            'AO' => 'Sair',
            'default' => 'Sair'
        ],
        
        // Common Actions
        'Save' => [
            'MZ' => 'Salvar',
            'PT' => 'Guardar',
            'BR' => 'Salvar',
            'AO' => 'Guardar',
            'default' => 'Salvar'
        ],
        'Cancel' => [
            'MZ' => 'Cancelar',
            'PT' => 'Cancelar',
            'BR' => 'Cancelar',
            'AO' => 'Cancelar',
            'default' => 'Cancelar'
        ],
        'Delete' => [
            'MZ' => 'Excluir',
            'PT' => 'Eliminar',
            'BR' => 'Excluir',
            'AO' => 'Eliminar',
            'default' => 'Excluir'
        ],
        'Edit' => [
            'MZ' => 'Editar',
            'PT' => 'Editar',
            'BR' => 'Editar',
            'AO' => 'Editar',
            'default' => 'Editar'
        ],
        'Create' => [
            'MZ' => 'Criar',
            'PT' => 'Criar',
            'BR' => 'Criar',
            'AO' => 'Criar',
            'default' => 'Criar'
        ],
        'Update' => [
            'MZ' => 'Actualizar',
            'PT' => 'Actualizar',
            'BR' => 'Atualizar',
            'AO' => 'Actualizar',
            'default' => 'Actualizar'
        ],
        
        // Status Messages
        'Success!' => [
            'MZ' => 'Sucesso!',
            'PT' => 'Sucesso!',
            'BR' => 'Sucesso!',
            'AO' => 'Sucesso!',
            'default' => 'Sucesso!'
        ],
        'Error!' => [
            'MZ' => 'Erro!',
            'PT' => 'Erro!',
            'BR' => 'Erro!',
            'AO' => 'Erro!',
            'default' => 'Erro!'
        ],
        'Loading...' => [
            'MZ' => 'Carregando...',
            'PT' => 'A carregar...',
            'BR' => 'Carregando...',
            'AO' => 'Carregando...',
            'default' => 'Carregando...'
        ],
        'Please wait...' => [
            'MZ' => 'Por favor aguarde...',
            'PT' => 'Por favor aguarde...',
            'BR' => 'Por favor aguarde...',
            'AO' => 'Por favor aguarde...',
            'default' => 'Por favor aguarde...'
        ],
        
        // Confirmation Messages
        'Are you sure?' => [
            'MZ' => 'Tem certeza?',
            'PT' => 'Tem a certeza?',
            'BR' => 'Tem certeza?',
            'AO' => 'Tem certeza?',
            'default' => 'Tem certeza?'
        ],
        'Confirm' => [
            'MZ' => 'Confirmar',
            'PT' => 'Confirmar',
            'BR' => 'Confirmar',
            'AO' => 'Confirmar',
            'default' => 'Confirmar'
        ],
        
        // File Management
        'Upload' => [
            'MZ' => 'Carregar',
            'PT' => 'Carregar',
            'BR' => 'Enviar',
            'AO' => 'Carregar',
            'default' => 'Carregar'
        ],
        'Download' => [
            'MZ' => 'Baixar',
            'PT' => 'Descarregar',
            'BR' => 'Baixar',
            'AO' => 'Baixar',
            'default' => 'Baixar'
        ],
        
        // Time & Dates
        'Today' => [
            'MZ' => 'Hoje',
            'PT' => 'Hoje',
            'BR' => 'Hoje',
            'AO' => 'Hoje',
            'default' => 'Hoje'
        ],
        'Yesterday' => [
            'MZ' => 'Ontem',
            'PT' => 'Ontem',
            'BR' => 'Ontem',
            'AO' => 'Ontem',
            'default' => 'Ontem'
        ],
        'Tomorrow' => [
            'MZ' => 'Amanhã',
            'PT' => 'Amanhã',
            'BR' => 'Amanhã',
            'AO' => 'Amanhã',
            'default' => 'Amanhã'
        ],
    ];

    /**
     * Intercept and translate common English phrases automatically
     */
    public static function intercept(string $key, array $replace = [], ?string $locale = null): ?string
    {
        // Get current region
        $region = Lusophone::detectRegion();
        
        // Check if we have a translation for this exact key
        $translation = static::getTranslation($key, $region);
        
        if ($translation) {
            // Replace placeholders if any
            foreach ($replace as $placeholder => $value) {
                $translation = str_replace(":{$placeholder}", $value, $translation);
            }
            
            return $translation;
        }
        
        return null; // Let Laravel handle it normally
    }

    /**
     * Get translation for a specific key and region
     */
    protected static function getTranslation(string $key, string $region): ?string
    {
        // Direct match
        if (isset(static::$commonTranslations[$key])) {
            $translations = static::$commonTranslations[$key];
            return $translations[$region] ?? $translations['default'] ?? null;
        }
        
        // Case-insensitive match
        $lowerKey = strtolower($key);
        foreach (static::$commonTranslations as $originalKey => $translations) {
            if (strtolower($originalKey) === $lowerKey) {
                return $translations[$region] ?? $translations['default'] ?? null;
            }
        }
        
        // Fuzzy matching for common variations
        return static::fuzzyMatch($key, $region);
    }

    /**
     * Fuzzy matching for common phrase variations
     */
    protected static function fuzzyMatch(string $key, string $region): ?string
    {
        // Remove punctuation and extra spaces
        $cleanKey = trim(preg_replace('/[^\w\s]/', '', $key));
        
        $fuzzyMappings = [
            'you are logged in' => "You're logged in!",
            'logged in successfully' => "You're logged in!",
            'login successful' => "You're logged in!",
            'welcome back' => 'Welcome!',
            'are you sure you want to delete' => 'Are you sure?',
            'confirm delete' => 'Are you sure?',
            'please wait' => 'Please wait...',
            'loading' => 'Loading...',
            'processing' => 'Loading...',
        ];
        
        $lowerCleanKey = strtolower($cleanKey);
        
        foreach ($fuzzyMappings as $pattern => $mappedKey) {
            if (str_contains($lowerCleanKey, $pattern)) {
                return static::getTranslation($mappedKey, $region);
            }
        }
        
        return null;
    }

    /**
     * Register additional translations dynamically
     */
    public static function registerTranslations(array $translations): void
    {
        static::$commonTranslations = array_merge(static::$commonTranslations, $translations);
    }

    /**
     * Get all available translations for debugging
     */
    public static function getAllTranslations(): array
    {
        return static::$commonTranslations;
    }

    /**
     * Check if a translation exists for a key
     */
    public static function hasTranslation(string $key): bool
    {
        return isset(static::$commonTranslations[$key]) || 
               static::fuzzyMatch($key, 'MZ') !== null;
    }
}