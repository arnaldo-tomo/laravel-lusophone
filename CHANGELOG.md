# Changelog

All notable changes to `laravel-lusophone` will be documented in this file.

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