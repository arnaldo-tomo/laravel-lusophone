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

        $detectedRegion = $this->detectEnvironmentBasedRegion();
        session(['lusophone_region' => $detectedRegion]);

        return $detectedRegion;
    }

    /**
     * Smart environment-based detection
     * LOCAL: defaults to MZ, ONLINE: auto-detects
     */
    protected function detectEnvironmentBasedRegion(): string
    {
        // Check if we're in local environment
        if ($this->isLocalEnvironment()) {
            Log::info('Laravel Lusophone: Local environment detected, defaulting to MZ');
            return 'MZ';
        }

        // Online environment - perform full detection
        return $this->detectFromMultipleSources();
    }

    /**
     * Detect if we're running locally
     */
    protected function isLocalEnvironment(): bool
    {
        // Check if we're in local/testing environment
        if (app()->environment(['local', 'testing'])) {
            return true;
        }

        // Check common local IPs
        $ip = $this->request->ip();
        $localIps = [
            '127.0.0.1', '::1', 'localhost',
            '192.168.', '10.', '172.16.', '172.17.', '172.18.', '172.19.',
            '172.20.', '172.21.', '172.22.', '172.23.', '172.24.', '172.25.',
            '172.26.', '172.27.', '172.28.', '172.29.', '172.30.', '172.31.'
        ];

        foreach ($localIps as $localIp) {
            if (str_starts_with($ip, $localIp)) {
                return true;
            }
        }

        // Check if running on common local domains
        $host = $this->request->getHost();
        $localDomains = ['.local', '.test', '.localhost', 'localhost'];
        
        foreach ($localDomains as $domain) {
            if (str_contains($host, $domain)) {
                return true;
            }
        }

        // Check for common development ports
        $port = $this->request->getPort();
        $devPorts = [8000, 8080, 3000, 5173, 5174, 9000];
        
        if (in_array($port, $devPorts)) {
            return true;
        }

        return false;
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
            // If online but can't detect, check if user might be Mozambican
            return $this->intelligentFallback();
        }

        $voteCounts = array_count_values($votes);
        arsort($voteCounts);
        return array_key_first($voteCounts);
    }

    /**
     * Intelligent fallback for online environments
     */
    protected function intelligentFallback(): string
    {
        // Check Accept-Language for Portuguese variants
        $acceptLanguage = $this->request->header('Accept-Language', '');
        
        if (str_contains($acceptLanguage, 'pt')) {
            // If Portuguese detected but country unclear, default to MZ for African context
            if (str_contains($acceptLanguage, 'pt-MZ') || str_contains($acceptLanguage, 'pt-AO')) {
                return 'MZ';
            }
            if (str_contains($acceptLanguage, 'pt-BR')) {
                return 'BR';
            }
            if (str_contains($acceptLanguage, 'pt-PT')) {
                return 'PT';
            }
            
            // Default Portuguese to MZ (African context preference)
            return 'MZ';
        }

        // Final fallback - check timezone hints
        $timezone = config('app.timezone');
        if (str_contains($timezone, 'Africa')) {
            return 'MZ';
        }

        return config('lusophone.default_region', 'MZ');
    }

    protected function detectFromHeaders(): ?string
    {
        if ($country = $this->request->header('CF-IPCountry')) {
            return strtoupper($country);
        }
        if ($country = $this->request->header('CloudFront-Viewer-Country')) {
            return strtoupper($country);
        }
        if ($country = $this->request->header('X-Country-Code')) {
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
            // Try ipapi.co first (more reliable)
            $response = Http::timeout(3)->get("https://ipapi.co/{$ip}/country/");
            
            if ($response->successful()) {
                $country = strtoupper(trim($response->body()));
                
                if ($this->isLusophoneCountry($country)) {
                    Cache::put($cacheKey, $country, now()->addDays(7));
                    return $country;
                }
            }

            // Fallback to ip-api.com
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}?fields=countryCode");
            
            if ($response->successful()) {
                $data = $response->json();
                $country = strtoupper($data['countryCode'] ?? '');
                
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
            'pt-AO' => 'AO', 'pt-CV' => 'CV', 'pt-GW' => 'GW',
            'pt-ST' => 'ST', 'pt-TL' => 'TL', 'pt' => 'MZ', // Default pt to MZ
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

        return config('lusophone.default_region', 'MZ');
    }

    public function getCountryInfo(string $region = null): array
    {
        $region = $region ?: $this->detect();
        return $this->lusophoneCountries[$region] ?? $this->lusophoneCountries['MZ'];
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

    /**
     * Get environment type for debugging
     */
    public function getEnvironmentType(): string
    {
        return $this->isLocalEnvironment() ? 'local' : 'online';
    }
}