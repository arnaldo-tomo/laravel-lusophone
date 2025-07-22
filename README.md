# 🌍 Laravel Lusophone

[![Latest Version on Packagist](https://img.shields.io/packagist/v/arnaldotomo/laravel-lusophone.svg?style=flat-square)](https://packagist.org/packages/arnaldotomo/laravel-lusophone)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/arnaldo-tomo/laravel-lusophone/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/arnaldo-tomo/laravel-lusophone/actions?query=workflow%3Atests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/arnaldotomo/laravel-lusophone.svg?style=flat-square)](https://packagist.org/packages/arnaldotomo/laravel-lusophone)

**The first truly comprehensive Portuguese localization package for Laravel applications.**

Laravel Lusophone automatically detects your users' location and adapts your application to their specific Portuguese variant, cultural context, and local validation requirements. Built in 🇲🇿 **Mozambique** for the entire **Lusophone world** 🌍.

---

## 🚀 **Why Laravel Lusophone?**

### **🎯 One Package, 260M+ Users**
- **🇵🇹 Portugal**: Formal European Portuguese, NIF validation, EUR formatting
- **🇧🇷 Brasil**: Informal Brazilian Portuguese, CPF validation, BRL formatting  
- **🇲🇿 Moçambique**: Mixed terminology, NUIT validation, MZN formatting
- **🇦🇴 Angola**: Formal Portuguese, Angolan NIF, AOA formatting
- **🇨🇻 Cabo Verde**: CV-specific validation, CVE formatting
- **+ 3 more countries** (Guinea-Bissau, São Tomé, Timor-Leste)

### **🧠 Smart & Automatic**
- ✅ **Zero Configuration**: Works out of the box
- ✅ **Auto Region Detection**: IP, headers, language preferences  
- ✅ **Cultural Context**: Adapts formality and terminology
- ✅ **Universal Validation**: One rule works across all countries
- ✅ **Performance Optimized**: Intelligent caching and lazy loading

### **🔥 Unique Features**
- 🌍 **First Laravel package** to serve the entire Lusophone world
- 🎭 **Cultural Context Awareness**: Business vs casual vs government
- ✅ **Smart Validators**: NIF, NUIT, CPF, phone numbers, postal codes
- �� **Currency Formatting**: Automatic local currency display
- 📱 **Mobile Optimized**: Touch-friendly interfaces

---

## 📥 **Installation**

### **1. Install via Composer**

```bash
composer require arnaldotomo/laravel-lusophone
cat > CHANGELOG.md << 'EOF'
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
