<?php

namespace ArnaldoTomo\LaravelLusophone;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RegionDetector
{
    protected array $lusophoneCountries = [
        'PT' => ['name' => 'Portugal', 'currency' => 'EUR', 'currency_symbol' => '€', 'phone_prefix' => '+351', 'formality' => 'formal'],
        'BR' => ['name' => 'Brasil', 'currency' => 'BRL', 'currency_symbol' => 'R$', 'phone_prefix' => '+55', 'formality' => 'informal'],
        'MZ' => ['name' => 'Moçambique', 'currency' => 'MZN', 'currency_symbol' => 'MT', 'phone_prefix' => '+258', 'formality' => 'mixed'],
        'AO' => ['name' => 'Angola', 'currency' => 'AOA', 'currency_symbol' => 'Kz', 'phone_prefix' => '+244', 'formality' => 'formal'],
        'CV' => ['name' => 'Cabo Verde', 'currency' => 'CVE', 'currency_symbol' => 'Esc', 'phone_prefix' => '+238', 'formality' => 'formal'],
        'GW' => ['name' => 'Guiné-Bissau', 'currency' => 'XOF', 'currency_symbol' => 'CFA', 'phone_prefix' => '+245', 'formality' => 'formal'],
        'ST' => ['name' => 'São Tomé e Príncipe', 'currency' => 'STN', 'currency_symbol' => 'Db', 'phone_prefix' => '+239', 'formality' => 'formal'],
        'TL' => ['name' => 'Timor-Leste', 'currency' => 'USD', 'currency_symbol' => '$', 'phone_prefix' => '+670', 'formality' => 'formal'],
    ];

    protected Request $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request ?: request();
    }

    public function detect(): string
    {
        if ($forced = config('lusophone.force_region')) {
            return $this->validateRegion($forced);
        }

        if ($cached = session('lusophone_region')) {
            return $this->validateRegion($cached);
        }

        $detectedRegion = $this->detectFromMultipleSources();
        session(['lusophone_region' => $detectedRegion]);

        return $detectedRegion;
    }

    protected function detectFromMultipleSources(): string
    {
        $methods = ['detectFromHeaders', 'detectFromIP', 'detectFromAcceptLanguage'];
        $votes = [];

        foreach ($methods as $method) {
            try {
                $result = $this->$method();
                if ($result && $this->isLusophoneCountry($result)) {
                    $votes[] = $result;
                }
            } catch (\Exception $e) {
                Log::warning("Lusophone detection method {$method} failed: " . $e->getMessage());
            }
        }

        if (empty($votes)) {
            return config('lusophone.default_region', 'PT');
        }

        $voteCounts = array_count_values($votes);
        arsort($voteCounts);
        return array_key_first($voteCounts);
    }

    protected function detectFromHeaders(): ?string
    {
        if ($country = $this->request->header('CF-IPCountry')) {
            return strtoupper($country);
        }
        if ($country = $this->request->header('CloudFront-Viewer-Country')) {
            return strtoupper($country);
        }
        return null;
    }

    protected function detectFromIP(): ?string
    {
        $ip = $this->request->ip();

        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
            return null;
        }

        $cacheKey = "lusophone_ip_detection_{$ip}";
        if ($cached = Cache::get($cacheKey)) {
            return $cached;
        }

        try {
            $response = Http::timeout(3)->get("https://ipapi.co/{$ip}/country/");
            
            if ($response->successful()) {
                $country = strtoupper(trim($response->body()));
                
                if ($this->isLusophoneCountry($country)) {
                    Cache::put($cacheKey, $country, now()->addDays(7));
                    return $country;
                }
            }
        } catch (\Exception $e) {
            Log::warning("External GeoIP detection failed: " . $e->getMessage());
        }

        return null;
    }

    protected function detectFromAcceptLanguage(): ?string
    {
        $acceptLanguage = $this->request->header('Accept-Language', '');
        
        $languages = [];
        if (preg_match_all('/([a-z]{2}(?:-[A-Z]{2})?),?/', $acceptLanguage, $matches)) {
            $languages = $matches[1];
        }

        $languageMap = [
            'pt-PT' => 'PT', 'pt-BR' => 'BR', 'pt-MZ' => 'MZ', 
            'pt-AO' => 'AO', 'pt-CV' => 'CV', 'pt' => 'PT',
        ];

        foreach ($languages as $lang) {
            if (isset($languageMap[$lang])) {
                return $languageMap[$lang];
            }
        }

        return null;
    }

    public function isLusophoneCountry(string $country): bool
    {
        return isset($this->lusophoneCountries[strtoupper($country)]);
    }

    protected function validateRegion(string $region): string
    {
        $region = strtoupper($region);
        
        if ($this->isLusophoneCountry($region)) {
            return $region;
        }

        return config('lusophone.default_region', 'PT');
    }

    public function getCountryInfo(string $region = null): array
    {
        $region = $region ?: $this->detect();
        return $this->lusophoneCountries[$region] ?? $this->lusophoneCountries['PT'];
    }

    public function getAllCountries(): array
    {
        return $this->lusophoneCountries;
    }

    public function getCurrencyInfo(string $region = null): array
    {
        $info = $this->getCountryInfo($region);
        
        return [
            'code' => $info['currency'],
            'symbol' => $info['currency_symbol'],
        ];
    }

    public function forceRegion(string $region): static
    {
        config(['lusophone.force_region' => strtoupper($region)]);
        session()->forget('lusophone_region');
        
        return $this;
    }
}
