<?php

namespace ArnaldoTomo\LaravelLusophone;

class LusophoneManager
{
    protected RegionDetector $regionDetector;
    protected ValidationManager $validationManager;
    protected CurrencyFormatter $currencyFormatter;
    protected LusophoneTranslator $translator;

    public function __construct(
        RegionDetector $regionDetector,
        ValidationManager $validationManager,
        CurrencyFormatter $currencyFormatter,
        LusophoneTranslator $translator
    ) {
        $this->regionDetector = $regionDetector;
        $this->validationManager = $validationManager;
        $this->currencyFormatter = $currencyFormatter;
        $this->translator = $translator;
    }

    public function detectRegion(): string
    {
        return $this->regionDetector->detect();
    }

    public function getCountryInfo(string $region = null): array
    {
        return $this->regionDetector->getCountryInfo($region);
    }

    public function getAllCountries(): array
    {
        return $this->regionDetector->getAllCountries();
    }

    public function forceRegion(string $region): static
    {
        $this->regionDetector->forceRegion($region);
        return $this;
    }

    public function getTaxIdFieldName(string $region = null): string
    {
        return $this->validationManager->getTaxIdFieldName($region);
    }

    public function getPhoneFieldName(string $region = null): string
    {
        return $this->validationManager->getPhoneFieldName($region);
    }

    public function formatCurrency(float $amount, string $region = null): string
    {
        return $this->currencyFormatter->format($amount, $region);
    }

    public function getCurrencyInfo(string $region = null): array
    {
        return $this->regionDetector->getCurrencyInfo($region);
    }

    public function isLusophoneCountry(string $country): bool
    {
        return $this->regionDetector->isLusophoneCountry($country);
    }

    public function validateTaxId(string $value, string $region = null): bool
    {
        return $this->validationManager->validateTaxId($value, $region);
    }

    public function validatePhone(string $value, string $region = null): bool
    {
        return $this->validationManager->validatePhone($value, $region);
    }

    public function validatePostalCode(string $value, string $region = null): bool
    {
        return $this->validationManager->validatePostalCode($value, $region);
    }

    public function clearDetectionCache(): static
    {
        session()->forget('lusophone_region');
        return $this;
    }

    public function getValidationRules(string $region = null): array
    {
        return [
            'tax_id' => 'lusophone_tax_id',
            'phone' => 'lusophone_phone',
            'postal_code' => 'lusophone_postal',
        ];
    }

    // Translation methods
    public function translate(string $key, array $replace = [], string $region = null): string
    {
        return $this->translator->translate($key, $replace, $region);
    }

    public function contextualTranslate(string $key, string $context = 'general', array $replace = [], string $region = null): string
    {
        return $this->translator->contextualTranslate($key, $context, $replace, $region);
    }

    public function detectContext(): string
    {
        return $this->translator->detectContext();
    }

    public function hasTranslation(string $key, string $region = null): bool
    {
        return $this->translator->hasTranslation($key, $region);
    }

    public function getMissingTranslations(array $requiredKeys, string $region = null): array
    {
        return $this->translator->getMissingTranslations($requiredKeys, $region);
    }

    public function getAvailableRegions(): array
    {
        return $this->translator->getAvailableRegions();
    }
}
