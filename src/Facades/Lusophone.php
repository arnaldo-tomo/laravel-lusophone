<?php

namespace ArnaldoTomo\LaravelLusophone\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string detectRegion()
 * @method static array getCountryInfo(string $region = null)
 * @method static array getAllCountries()
 * @method static static forceRegion(string $region)
 * @method static string getTaxIdFieldName(string $region = null)
 * @method static string getPhoneFieldName(string $region = null)
 * @method static string formatCurrency(float $amount, string $region = null)
 * @method static array getCurrencyInfo(string $region = null)
 * @method static bool isLusophoneCountry(string $country)
 * @method static bool validateTaxId(string $value, string $region = null)
 * @method static bool validatePhone(string $value, string $region = null)
 * @method static bool validatePostalCode(string $value, string $region = null)
 * @method static static clearDetectionCache()
 * @method static array getValidationRules(string $region = null)
 * @method static string translate(string $key, array $replace = [], string $region = null)
 * @method static string contextualTranslate(string $key, string $context = 'general', array $replace = [], string $region = null)
 * @method static string detectContext()
 * @method static bool hasTranslation(string $key, string $region = null)
 * @method static array getMissingTranslations(array $requiredKeys, string $region = null)
 * @method static array getAvailableRegions()
 *
 * @see \ArnaldoTomo\LaravelLusophone\LusophoneManager
 */
class Lusophone extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'lusophone';
    }
}
