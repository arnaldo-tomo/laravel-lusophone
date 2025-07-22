# Changelog

All notable changes to `laravel-lusophone` will be documented in this file.

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
- 🇵🇹 Portugal (NIF validation, EUR formatting)
- 🇧🇷 Brasil (CPF validation, BRL formatting)
- 🇲🇿 Moçambique (NUIT validation, MZN formatting)
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
