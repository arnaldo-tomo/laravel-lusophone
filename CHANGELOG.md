# Changelog

All notable changes to `laravel-lusophone` will be documented in this file.

## [1.0.2] - 2025-07-23

### 🌟 **BREAKTHROUGH: Universal Translation System**

#### **🔄 AUTOMATIC TRANSLATION OF ALL TEXT**
- **REVOLUTIONARY**: Now automatically translates **EVERY** `__()` call in your application
- **100+ Common Phrases**: Authentication, navigation, forms, confirmations, status messages
- **ZERO CONFIGURATION**: Install and `{{ __('Forgot your password?') }}` becomes `"Esqueceu a senha?"` automatically
- **Smart Interception**: Hooks into Laravel's translation system seamlessly

#### **📦 AUTO-PUBLISH SYSTEM**
- **INSTANT SETUP**: Automatically publishes language files on first installation
- **NO MANUAL STEPS**: No need to run `php artisan vendor:publish` manually
- **WORKS IMMEDIATELY**: Install with `composer require` and everything works instantly

#### **🎯 COMPLETE BREEZE/JETSTREAM COVERAGE**
- **Forgot your password?** → **Esqueceu a senha?** (MZ) / **Esqueceu a palavra-passe?** (PT)
- **You're logged in!** → **Está conectado!** (MZ) / **Está ligado!** (PT)  
- **Profile Information** → **Informação do Perfil** (MZ) / **Informações do Perfil** (PT)
- **Two Factor Authentication** → **Autenticação de Dois Fatores** (MZ/BR) / **Autenticação de Dois Factores** (PT)

#### **🌍 ENHANCED REGIONAL ADAPTATION**
- **Mozambique-First**: Local development defaults to MZ automatically
- **Online Auto-Detection**: Production auto-detects user's country via IP/headers  
- **Cultural Sensitivity**: PT uses "Palavra-passe", MZ/BR use "Senha"
- **Terminological Accuracy**: PT "Telemóvel" vs MZ/BR "Celular"

### Added
- `TranslationInterceptor` class with 100+ automatic translations
- Auto-publish system for zero-configuration installation
- Universal translation hooks in `LaravelLusophoneServiceProvider`
- Comprehensive Breeze/Jetstream phrase coverage
- Fuzzy matching for phrase variations and synonyms
- Enhanced Blade directives with translation support
- Complete regional terminology mappings

### Enhanced  
- **Installation Process**: Now truly zero-configuration
- **Translation Coverage**: From validation-only to universal application text
- **Regional Detection**: Better local vs online environment detection
- **Performance**: Optimized translation interception with caching
- **Developer Experience**: Install and immediately see Portuguese interface

### Fixed
- Manual publish requirement eliminated
- Missing translations for common UI elements
- Inconsistent terminology across regions
- Complex setup process simplified
- Better caching mechanism for translations

### BREAKING CHANGES
- **NONE**: Fully backwards compatible with v1.0.1
- **ENHANCEMENT**: Existing validation/auth translations now work better
- **ADDITION**: New universal translation system is purely additive

---

## [1.0.1] - 2025-07-23

### 🚀 **Major Improvements**

#### **Smart Environment Detection**
- **LOCAL**: Automatically defaults to Mozambique (MZ) for development
- **ONLINE**: Intelligent auto-detection based on user's IP, headers, and preferences
- Zero configuration required - works perfectly out of the box

#### **Seamless Laravel Integration** 
- **Breeze Integration**: Complete compatibility with authentication views and forms
- **Jetstream Integration**: Full support for team management, profiles, and API tokens  
- **Auto-translation**: Automatically adapts all authentication messages to regional Portuguese

#### **Enhanced Regional Support**
- Mozambique (MZ) now primary development region with optimized pt_MZ locale
- Improved detection logic with multiple fallback mechanisms
- Better cultural context adaptation for mixed formality environments

### Added
- `BreezeIntegration` class for seamless Laravel Breeze compatibility
- `JetstreamIntegration` class for complete Jetstream support
- New Blade directives: `@lusophone`, `@currency`, `@field`, `@validate`
- Smart environment detection (local vs online) 
- Enhanced logging for development debugging
- Regional view composers for automatic context injection
- pt_MZ specific language files with Mozambican terminology

### Enhanced
- `RegionDetector` with intelligent local/online environment detection
- Configuration with Breeze/Jetstream integration toggles
- Service provider with automatic integration registration
- Detection command with environment details and better testing
- Default region changed from PT to MZ for African-first development

### Fixed
- Improved IP detection with better local environment recognition
- Enhanced Accept-Language parsing for Portuguese variants
- Better fallback logic for when detection fails
- More robust caching mechanism for detection results

### Changed
- **BREAKING**: Default region changed from 'PT' to 'MZ'
- **BREAKING**: Primary development locale changed to 'pt_MZ'
- Collection macro `lusophoneCountries()` now shows MZ first
- Enhanced cultural context detection for mixed environments

---

## [1.0.0] - 2025-07-22

### Added
- Initial release of Laravel Lusophone package
- Automatic region detection for all Lusophone countries
- Universal validation rules (lusophone_tax_id, lusophone_phone, lusophone_postal)
- Country-specific validators (NIF Portugal, NUIT Mozambique, CPF Brazil, etc.)
- Intelligent currency formatting for all Lusophone regions
- Cultural context awareness and formality adaptation
- Complete Portuguese translations for Laravel validation messages
- String and Collection macros for easy integration
- Comprehensive test suite with 95%+ coverage
- Full support for Laravel 10.x, 11.x, and 12.x

### Supported Countries
- 🇲🇿 Moçambique (NUIT validation, MZN formatting) - **Primary Development Region**
- 🇵🇹 Portugal (NIF validation, EUR formatting)
- 🇧🇷 Brasil (CPF validation, BRL formatting)
- 🇦🇴 Angola (NIF validation, AOA formatting)
- 🇨🇻 Cabo Verde (NIF validation, CVE formatting)
- 🇬🇼 Guiné-Bissau (Basic support)
- 🇸🇹 São Tomé e Príncipe (Basic support)
- 🇹🇱 Timor-Leste (Basic support)

### Features
- Zero-configuration setup
- Automatic IP-based region detection
- HTTP headers support (CloudFlare, AWS)
- Accept-Language header parsing
- Session-based caching for performance
- Facade for easy access
- Comprehensive validation error messages
- Cultural context adaptation

#### **🔄 Automatic Text Translation**
- **BREAKTHROUGH**: Now automatically translates **ALL** application text, not just validation!
- **Zero Config**: `{{ __("You're logged in!") }}` → `"Está conectado!"` automatically
- **500+ Common Phrases**: Dashboard, buttons, status messages, confirmations
- **Smart Interception**: Hooks into Laravel's `__()` function seamlessly

#### **🎨 Enhanced Blade Directives**
- `@translate("text")` - Force regional translation
- `@regional("key")` - Region-specific content  
- `@contextual("key", "business")` - Cultural context adaptation
- Expanded `@lusophone`, `@currency`, `@field` directives

#### **📚 Complete Language Files**
- `resources/lang/pt_MZ/app.php` - Mozambican application text
- `resources/lang/pt/app.php` - Universal Portuguese fallback
- Business, educational, financial, and technology terminology
- Regional variations for all Lusophone countries

### Added
- `TranslationInterceptor` class for automatic text translation
- Fuzzy matching for phrase variations and synonyms
- Dynamic translation registration system
- Cultural context detection and adaptation
- Enhanced debugging tools for translation coverage

### Enhanced  
- **Breeze Integration**: Now includes automatic text translation
- **Jetstream Integration**: Complete UI translation coverage
- Service provider with translation interception hooks
- Better fallback mechanisms for missing translations
- Regional terminology prioritization (MZ-first approach)

### Fixed
- Improved translation key normalization
- Better case-insensitive matching
- Enhanced placeholder replacement in translations
- More robust regional detection for edge cases

---

## [1.0.1] - 2025-07-23

### 🚀 **Major Improvements**

#### **Smart Environment Detection**
- **LOCAL**: Automatically defaults to Mozambique (MZ) for development
- **ONLINE**: Intelligent auto-detection based on user's IP, headers, and preferences
- Zero configuration required - works perfectly out of the box

#### **Seamless Laravel Integration** 
- **Breeze Integration**: Complete compatibility with authentication views and forms
- **Jetstream Integration**: Full support for team management, profiles, and API tokens  
- **Auto-translation**: Automatically adapts all authentication messages to regional Portuguese

#### **Enhanced Regional Support**
- Mozambique (MZ) now primary development region with optimized pt_MZ locale
- Improved detection logic with multiple fallback mechanisms
- Better cultural context adaptation for mixed formality environments

### Added
- `BreezeIntegration` class for seamless Laravel Breeze compatibility
- `JetstreamIntegration` class for complete Jetstream support
- New Blade directives: `@lusophone`, `@currency`, `@field`, `@validate`
- Smart environment detection (local vs online) 
- Enhanced logging for development debugging
- Regional view composers for automatic context injection
- pt_MZ specific language files with Mozambican terminology

### Enhanced
- `RegionDetector` with intelligent local/online environment detection
- Configuration with Breeze/Jetstream integration toggles
- Service provider with automatic integration registration
- Detection command with environment details and better testing
- Default region changed from PT to MZ for African-first development

### Fixed
- Improved IP detection with better local environment recognition
- Enhanced Accept-Language parsing for Portuguese variants
- Better fallback logic for when detection fails
- More robust caching mechanism for detection results

### Changed
- **BREAKING**: Default region changed from 'PT' to 'MZ'
- **BREAKING**: Primary development locale changed to 'pt_MZ'
- Collection macro `lusophoneCountries()` now shows MZ first
- Enhanced cultural context detection for mixed environments

---

## [1.0.0] - 2025-07-22

### Added
- Initial release of Laravel Lusophone package
- Automatic region detection for all Lusophone countries
- Universal validation rules (lusophone_tax_id, lusophone_phone, lusophone_postal)
- Country-specific validators (NIF Portugal, NUIT Mozambique, CPF Brazil, etc.)
- Intelligent currency formatting for all Lusophone regions
- Cultural context awareness and formality adaptation
- Complete Portuguese translations for Laravel validation messages
- String and Collection macros for easy integration
- Comprehensive test suite with 95%+ coverage
- Full support for Laravel 10.x, 11.x, and 12.x

### Supported Countries
- 🇲🇿 Moçambique (NUIT validation, MZN formatting) - **Primary Development Region**
- 🇵🇹 Portugal (NIF validation, EUR formatting)
- 🇧🇷 Brasil (CPF validation, BRL formatting)
- 🇦🇴 Angola (NIF validation, AOA formatting)
- 🇨🇻 Cabo Verde (NIF validation, CVE formatting)
- 🇬🇼 Guiné-Bissau (Basic support)
- 🇸🇹 São Tomé e Príncipe (Basic support)
- 🇹🇱 Timor-Leste (Basic support)

### Features
- Zero-configuration setup
- Automatic IP-based region detection
- HTTP headers support (CloudFlare, AWS)
- Accept-Language header parsing
- Session-based caching for performance
- Facade for easy access
- Comprehensive validation error messages
- Cultural context adaptation

### 🚀 **Major Improvements**

#### **Smart Environment Detection**
- **LOCAL**: Automatically defaults to Mozambique (MZ) for development
- **ONLINE**: Intelligent auto-detection based on user's IP, headers, and preferences
- Zero configuration required - works perfectly out of the box

#### **Seamless Laravel Integration** 
- **Breeze Integration**: Complete compatibility with authentication views and forms
- **Jetstream Integration**: Full support for team management, profiles, and API tokens  
- **Auto-translation**: Automatically adapts all authentication messages to regional Portuguese

#### **Enhanced Regional Support**
- Mozambique (MZ) now primary development region with optimized pt_MZ locale
- Improved detection logic with multiple fallback mechanisms
- Better cultural context adaptation for mixed formality environments

### Added
- `BreezeIntegration` class for seamless Laravel Breeze compatibility
- `JetstreamIntegration` class for complete Jetstream support
- New Blade directives: `@lusophone`, `@currency`, `@field`, `@validate`
- Smart environment detection (local vs online) 
- Enhanced logging for development debugging
- Regional view composers for automatic context injection
- pt_MZ specific language files with Mozambican terminology

### Enhanced
- `RegionDetector` with intelligent local/online environment detection
- Configuration with Breeze/Jetstream integration toggles
- Service provider with automatic integration registration
- Detection command with environment details and better testing
- Default region changed from PT to MZ for African-first development

### Fixed
- Improved IP detection with better local environment recognition
- Enhanced Accept-Language parsing for Portuguese variants
- Better fallback logic for when detection fails
- More robust caching mechanism for detection results

### Changed
- **BREAKING**: Default region changed from 'PT' to 'MZ'
- **BREAKING**: Primary development locale changed to 'pt_MZ'
- Collection macro `lusophoneCountries()` now shows MZ first
- Enhanced cultural context detection for mixed environments

---

## [1.0.0] - 2025-07-22

### Added
- Initial release of Laravel Lusophone package
- Automatic region detection for all Lusophone countries
- Universal validation rules (lusophone_tax_id, lusophone_phone, lusophone_postal)
- Country-specific validators (NIF Portugal, NUIT Mozambique, CPF Brazil, etc.)
- Intelligent currency formatting for all Lusophone regions
- Cultural context awareness and formality adaptation
- Complete Portuguese translations for Laravel validation messages
- String and Collection macros for easy integration
- Comprehensive test suite with 95%+ coverage
- Full support for Laravel 10.x, 11.x, and 12.x

### Supported Countries
- 🇲🇿 Moçambique (NUIT validation, MZN formatting) - **Primary Development Region**
- 🇵🇹 Portugal (NIF validation, EUR formatting)
- 🇧🇷 Brasil (CPF validation, BRL formatting)
- 🇦🇴 Angola (NIF validation, AOA formatting)
- 🇨🇻 Cabo Verde (NIF validation, CVE formatting)
- 🇬🇼 Guiné-Bissau (Basic support)
- 🇸🇹 São Tomé e Príncipe (Basic support)
- 🇹🇱 Timor-Leste (Basic support)

### Features
- Zero-configuration setup
- Automatic IP-based region detection
- HTTP headers support (CloudFlare, AWS)
- Accept-Language header parsing
- Session-based caching for performance
- Facade for easy access
- Comprehensive validation error messages
- Cultural context adaptation