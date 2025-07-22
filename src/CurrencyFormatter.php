<?php

namespace ArnaldoTomo\LaravelLusophone;

class CurrencyFormatter
{
    protected RegionDetector $regionDetector;

    public function __construct(RegionDetector $regionDetector)
    {
        $this->regionDetector = $regionDetector;
    }

    public function format(float $amount, string $region = null): string
    {
        $region = $region ?: $this->regionDetector->detect();
        $currencyInfo = $this->regionDetector->getCurrencyInfo($region);

        return match ($region) {
            'PT' => $this->formatEuropeanStyle($amount, $currencyInfo['symbol']),
            'BR' => $this->formatBrazilianStyle($amount, $currencyInfo['symbol']),
            'MZ' => $this->formatAfricanStyle($amount, $currencyInfo['symbol']),
            'AO' => $this->formatAfricanStyle($amount, $currencyInfo['symbol']),
            'CV' => $this->formatAfricanStyle($amount, $currencyInfo['symbol']),
            'GW' => $this->formatAfricanStyle($amount, $currencyInfo['symbol']),
            'ST' => $this->formatAfricanStyle($amount, $currencyInfo['symbol']),
            'TL' => $this->formatAmericanStyle($amount, $currencyInfo['symbol']),
            default => $this->formatEuropeanStyle($amount, $currencyInfo['symbol']),
        };
    }

    public function formatNumber(float $number, int $decimals = 2, string $region = null): string
    {
        $region = $region ?: $this->regionDetector->detect();

        return match ($region) {
            'TL' => number_format($number, $decimals, '.', ','), // American style: 1,500.50
            default => number_format($number, $decimals, ',', ' '), // European style: 1 500,50
        };
    }

    protected function formatEuropeanStyle(float $amount, string $symbol): string
    {
        $formatted = number_format($amount, 2, ',', ' ');
        return "{$formatted} {$symbol}";
    }

    protected function formatBrazilianStyle(float $amount, string $symbol): string
    {
        $formatted = number_format($amount, 2, ',', '.');
        return "{$symbol} {$formatted}";
    }

    protected function formatAfricanStyle(float $amount, string $symbol): string
    {
        $formatted = number_format($amount, 2, ',', '.');
        return "{$formatted} {$symbol}";
    }

    protected function formatAmericanStyle(float $amount, string $symbol): string
    {
        $formatted = number_format($amount, 2, '.', ',');
        return "{$symbol}{$formatted}";
    }

    public function parse(string $currencyString, string $region = null): float
    {
        $region = $region ?: $this->regionDetector->detect();
        
        // Remove currency symbols
        $symbols = ['€', 'R$', 'MT', 'Kz', 'Esc', 'CFA', 'Db', '$'];
        $clean = str_replace($symbols, '', $currencyString);
        $clean = trim($clean);

        return match ($region) {
            'TL' => (float) str_replace(',', '', $clean), // Remove thousands separator
            'BR' => (float) str_replace(['.', ','], ['', '.'], $clean), // BR format to float
            default => (float) str_replace([' ', ','], ['', '.'], $clean), // EU format to float
        };
    }

    public function getSymbol(string $region = null): string
    {
        $currencyInfo = $this->regionDetector->getCurrencyInfo($region);
        return $currencyInfo['symbol'];
    }

    public function getCode(string $region = null): string
    {
        $currencyInfo = $this->regionDetector->getCurrencyInfo($region);
        return $currencyInfo['code'];
    }

    public function formatRange(float $minAmount, float $maxAmount, string $region = null): string
    {
        $minFormatted = $this->format($minAmount, $region);
        $maxFormatted = $this->format($maxAmount, $region);
        
        return "{$minFormatted} - {$maxFormatted}";
    }

    public function formatForInput(float $amount, string $region = null): string
    {
        $region = $region ?: $this->regionDetector->detect();

        return match ($region) {
            'TL' => number_format($amount, 2, '.', ','), // 1,500.50
            'BR' => number_format($amount, 2, ',', '.'), // 1.500,50
            default => number_format($amount, 2, ',', ' '), // 1 500,50
        };
    }
}
