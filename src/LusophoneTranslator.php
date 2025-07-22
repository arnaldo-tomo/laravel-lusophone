<?php

namespace ArnaldoTomo\LaravelLusophone;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\App;

class LusophoneTranslator
{
    protected RegionDetector $regionDetector;
    protected array $regionalTerminology = [];
    protected array $contextualAdaptations = [];

    public function __construct(RegionDetector $regionDetector)
    {
        $this->regionDetector = $regionDetector;
        $this->initializeRegionalTerminology();
        $this->initializeContextualAdaptations();
    }

    /**
     * Translate a key with regional and cultural context
     */
    public function translate(string $key, array $replace = [], string $region = null): string
    {
        $region = $region ?: $this->regionDetector->detect();
        
        // Get base translation
        $translation = $this->getBaseTranslation($key);
        
        if (!$translation) {
            return $key; // Return key if no translation found
        }
        
        // Apply regional adaptations
        $translation = $this->applyRegionalAdaptations($translation, $region);
        
        // Apply cultural context
        $translation = $this->applyCulturalContext($translation, $region);
        
        // Replace placeholders
        $translation = $this->replacePlaceholders($translation, $replace, $region);
        
        return $translation;
    }

    /**
     * Get base translation from Laravel's translation system
     */
    protected function getBaseTranslation(string $key): ?string
    {
        // Try current locale first
        if (Lang::has($key)) {
            return Lang::get($key);
        }
        
        // Try Portuguese as fallback
        if (Lang::has($key, 'pt')) {
            return Lang::get($key, [], 'pt');
        }
        
        // Try loading from package translations
        $packageKey = "lusophone::{$key}";
        if (Lang::has($packageKey)) {
            return Lang::get($packageKey);
        }
        
        return null;
    }

    /**
     * Apply regional terminology adaptations
     */
    protected function applyRegionalAdaptations(string $translation, string $region): string
    {
        if (!isset($this->regionalTerminology[$region])) {
            return $translation;
        }
        
        $adaptations = $this->regionalTerminology[$region];
        
        foreach ($adaptations as $search => $replace) {
            // Case-insensitive replacement
            $translation = preg_replace('/\b' . preg_quote($search, '/') . '\b/i', $replace, $translation);
        }
        
        return $translation;
    }

    /**
     * Apply cultural context (formality, politeness, etc.)
     */
    protected function applyCulturalContext(string $translation, string $region): string
    {
        $countryInfo = $this->regionDetector->getCountryInfo($region);
        $formality = $countryInfo['formality'] ?? 'formal';
        
        if (!isset($this->contextualAdaptations[$formality])) {
            return $translation;
        }
        
        $adaptations = $this->contextualAdaptations[$formality];
        
        foreach ($adaptations as $search => $replace) {
            $translation = str_ireplace($search, $replace, $translation);
        }
        
        return $translation;
    }

    /**
     * Replace placeholders with regional awareness
     */
    protected function replacePlaceholders(string $translation, array $replace, string $region): string
    {
        foreach ($replace as $key => $value) {
            $placeholder = ":{$key}";
            
            // Apply regional terminology to attribute names
            if ($key === 'attribute') {
                $value = $this->translateAttribute($value, $region);
            }
            
            $translation = str_replace($placeholder, $value, $translation);
        }
        
        return $translation;
    }

    /**
     * Translate attribute names based on region
     */
    protected function translateAttribute(string $attribute, string $region): string
    {
        $attributeTranslations = [
            'PT' => [
                'email' => 'correio electrónico',
                'mobile' => 'telemóvel',
                'phone' => 'telefone',
                'password' => 'palavra-passe',
                'file' => 'ficheiro',
                'address' => 'morada',
                'user' => 'utilizador',
                'username' => 'nome de utilizador',
            ],
            'BR' => [
                'email' => 'e-mail',
                'mobile' => 'celular',
                'phone' => 'telefone',
                'password' => 'senha',
                'file' => 'arquivo',
                'address' => 'endereço',
                'user' => 'usuário',
                'username' => 'nome de usuário',
            ],
            'MZ' => [
                'email' => 'email', // Mixed terminology
                'mobile' => 'celular',
                'phone' => 'telefone',
                'password' => 'senha',
                'file' => 'arquivo',
                'address' => 'endereço',
                'user' => 'usuário',
                'username' => 'nome de usuário',
                'nuit' => 'NUIT',
            ],
            'AO' => [
                'email' => 'correio electrónico',
                'mobile' => 'telemóvel',
                'phone' => 'telefone',
                'password' => 'palavra-passe',
                'file' => 'ficheiro',
                'address' => 'endereço',
                'user' => 'utilizador',
                'username' => 'nome de utilizador',
            ],
        ];
        
        $translations = $attributeTranslations[$region] ?? $attributeTranslations['PT'];
        
        return $translations[$attribute] ?? $attribute;
    }

    /**
     * Get contextual translation based on time of day, user agent, etc.
     */
    public function contextualTranslate(string $key, string $context = 'general', array $replace = [], string $region = null): string
    {
        $region = $region ?: $this->regionDetector->detect();
        
        // Try context-specific translation first
        $contextKey = "{$key}.{$context}";
        if (Lang::has($contextKey)) {
            return $this->translate($contextKey, $replace, $region);
        }
        
        // Fallback to regular translation
        $translation = $this->translate($key, $replace, $region);
        
        // Apply context-specific adaptations
        return $this->applyContextualModifications($translation, $context, $region);
    }

    /**
     * Apply context-specific modifications (business hours, casual time, etc.)
     */
    protected function applyContextualModifications(string $translation, string $context, string $region): string
    {
        $contextModifications = [
            'business' => [
                'olá' => 'bom dia',
                'oi' => 'bom dia',
                'tchau' => 'até logo',
                'obrigado' => 'muito obrigado',
            ],
            'casual' => [
                'bom dia' => 'oi',
                'boa tarde' => 'olá',
                'muito obrigado' => 'obrigado',
                'excelência' => 'você',
            ],
            'government' => [
                'você' => 'Vossa Excelência',
                'obrigado' => 'muito obrigado',
                'olá' => 'respeitosos cumprimentos',
            ],
        ];
        
        if (!isset($contextModifications[$context])) {
            return $translation;
        }
        
        $modifications = $contextModifications[$context];
        
        foreach ($modifications as $search => $replace) {
            $translation = str_ireplace($search, $replace, $translation);
        }
        
        return $translation;
    }

    /**
     * Detect context based on current request/environment
     */
    public function detectContext(): string
    {
        $request = request();
        
        if (!$request) {
            return 'general';
        }
        
        // Check URL patterns
        $url = $request->url();
        
        if (str_contains($url, '/admin') || str_contains($url, '/dashboard')) {
            return 'business';
        }
        
        if (str_contains($url, '/gov') || str_contains($url, '/government')) {
            return 'government';
        }
        
        // Check time of day (business hours)
        $hour = now()->hour;
        if ($hour >= 9 && $hour <= 17 && now()->isWeekday()) {
            return 'business';
        }
        
        // Check user agent for business context
        $userAgent = $request->userAgent();
        if (str_contains($userAgent, 'corporate') || str_contains($userAgent, 'business')) {
            return 'business';
        }
        
        return 'casual';
    }

    /**
     * Get available regions with their language variants
     */
    public function getAvailableRegions(): array
    {
        return [
            'PT' => [
                'name' => 'Portugal',
                'locale' => 'pt_PT',
                'variant' => 'European Portuguese',
                'formality' => 'formal',
            ],
            'BR' => [
                'name' => 'Brasil', 
                'locale' => 'pt_BR',
                'variant' => 'Brazilian Portuguese',
                'formality' => 'informal',
            ],
            'MZ' => [
                'name' => 'Moçambique',
                'locale' => 'pt_MZ', 
                'variant' => 'Mozambican Portuguese',
                'formality' => 'mixed',
            ],
            'AO' => [
                'name' => 'Angola',
                'locale' => 'pt_AO',
                'variant' => 'Angolan Portuguese', 
                'formality' => 'formal',
            ],
            'CV' => [
                'name' => 'Cabo Verde',
                'locale' => 'pt_CV',
                'variant' => 'Cape Verdean Portuguese',
                'formality' => 'formal',
            ],
        ];
    }

    /**
     * Initialize regional terminology mappings
     */
    protected function initializeRegionalTerminology(): void
    {
        $this->regionalTerminology = [
            'PT' => [
                'e-mail' => 'correio electrónico',
                'email' => 'correio electrónico',
                'celular' => 'telemóvel',
                'senha' => 'palavra-passe',
                'arquivo' => 'ficheiro',
                'endereço' => 'morada',
                'usuário' => 'utilizador',
                'aplicativo' => 'aplicação',
            ],
            'BR' => [
                'correio electrónico' => 'e-mail',
                'telemóvel' => 'celular',
                'palavra-passe' => 'senha',
                'ficheiro' => 'arquivo',
                'morada' => 'endereço',
                'utilizador' => 'usuário',
                'aplicação' => 'aplicativo',
            ],
            'MZ' => [
                // Mixed PT/BR terminology - keep most BR terms but some PT
                'correio electrónico' => 'email',
                'telemóvel' => 'celular',
                'palavra-passe' => 'senha',
                'ficheiro' => 'arquivo',
                'morada' => 'endereço',
                'utilizador' => 'usuário',
            ],
            'AO' => [
                // Similar to PT but with some local variations
                'e-mail' => 'correio electrónico',
                'celular' => 'telemóvel',
                'senha' => 'palavra-passe',
                'arquivo' => 'ficheiro',
                'endereço' => 'morada',
                'usuário' => 'utilizador',
            ],
        ];
    }

    /**
     * Initialize contextual adaptations for different formality levels
     */
    protected function initializeContextualAdaptations(): void
    {
        $this->contextualAdaptations = [
            'informal' => [
                'vossa excelência' => 'você',
                'muito obrigado' => 'obrigado',
                'cordiais cumprimentos' => 'até mais',
                'estimado' => 'caro',
            ],
            'formal' => [
                'você' => 'o senhor/a senhora',
                'obrigado' => 'muito obrigado',
                'oi' => 'bom dia',
                'tchau' => 'até logo',
            ],
            'mixed' => [
                // Mozambican style - moderate formality
                'vossa excelência' => 'estimado cliente',
                'você' => 'o senhor/a senhora',
            ],
        ];
    }

    /**
     * Check if a translation key exists in any supported locale
     */
    public function hasTranslation(string $key, string $region = null): bool
    {
        $region = $region ?: $this->regionDetector->detect();
        
        $locales = [
            "pt_{$region}",
            'pt',
            App::getLocale(),
        ];
        
        foreach ($locales as $locale) {
            if (Lang::has($key, $locale)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get all missing translation keys for a given region
     */
    public function getMissingTranslations(array $requiredKeys, string $region = null): array
    {
        $region = $region ?: $this->regionDetector->detect();
        $missing = [];
        
        foreach ($requiredKeys as $key) {
            if (!$this->hasTranslation($key, $region)) {
                $missing[] = $key;
            }
        }
        
        return $missing;
    }

    /**
     * Preload translations for better performance
     */
    public function preloadTranslations(array $keys, string $region = null): void
    {
        $region = $region ?: $this->regionDetector->detect();
        
        // This would typically cache frequently used translations
        // For now, just ensure they're loaded
        foreach ($keys as $key) {
            $this->translate($key, [], $region);
        }
    }
}
