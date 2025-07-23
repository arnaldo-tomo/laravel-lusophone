<?php

namespace ArnaldoTomo\LaravelLusophone\Console\Commands;

use Illuminate\Console\Command;
use ArnaldoTomo\LaravelLusophone\Facades\Lusophone;
use ArnaldoTomo\LaravelLusophone\RegionDetector;
use Illuminate\Support\Str;

class LusophoneDetectCommand extends Command
{
    protected $signature = 'lusophone:detect 
                           {--region= : Test specific region}
                           {--clear-cache : Clear detection cache}
                           {--test-validation : Test validation rules}
                           {--test-currency : Test currency formatting}
                           {--show-environment : Show environment detection details}';

    protected $description = 'Test Lusophone region detection and functionality';

    public function handle()
    {
        $this->info('🔍 Laravel Lusophone Detection Test v1.0.1');
        $this->newLine();

        if ($this->option('clear-cache')) {
            $this->clearCache();
        }

        if ($this->option('show-environment')) {
            $this->showEnvironmentDetails();
        }

        if ($region = $this->option('region')) {
            $this->testSpecificRegion($region);
        } else {
            $this->testCurrentDetection();
        }

        if ($this->option('test-validation')) {
            $this->testValidation();
        }

        if ($this->option('test-currency')) {
            $this->testCurrency();
        }

        $this->newLine();
        $this->info('✅ Detection test completed!');
    }

    protected function clearCache()
    {
        $this->info('🧹 Clearing detection cache...');
        
        Lusophone::clearDetectionCache();
        
        $this->line('   ✓ Cache cleared');
        $this->newLine();
    }

    protected function showEnvironmentDetails()
    {
        $this->info('🌍 Environment Detection Details:');
        
        $detector = app(RegionDetector::class);
        $environmentType = $detector->getEnvironmentType();
        
        $this->table(['Property', 'Value'], [
            ['Environment Type', $environmentType],
            ['App Environment', app()->environment()],
            ['Request IP', request()->ip()],
            ['Request Host', request()->getHost()],
            ['Request Port', request()->getPort()],
            ['User Agent', substr(request()->userAgent() ?? 'N/A', 0, 50) . '...'],
            ['Accept Language', request()->header('Accept-Language', 'N/A')],
            ['CF-IPCountry Header', request()->header('CF-IPCountry', 'N/A')],
            ['App Timezone', config('app.timezone')],
        ]);
        
        $this->newLine();
        
        if ($environmentType === 'local') {
            $this->line('   🏠 <fg=green>LOCAL ENVIRONMENT DETECTED</fg=green>');
            $this->line('   → Defaulting to Mozambique (MZ) as configured');
        } else {
            $this->line('   🌐 <fg=blue>ONLINE ENVIRONMENT DETECTED</fg=blue>');
            $this->line('   → Performing intelligent region detection');
        }
        
        $this->newLine();
    }

    protected function testCurrentDetection()
    {
        $this->info('🎯 Current Detection Results:');
        
        try {
            $detector = app(RegionDetector::class);
            $region = Lusophone::detectRegion();
            $info = Lusophone::getCountryInfo($region);
            $environmentType = $detector->getEnvironmentType();
            
            $this->line("   Environment: <fg=yellow>{$environmentType}</fg=yellow>");
            $this->line("   Region: <fg=green>{$region}</fg=green>");
            $this->line("   Country: {$info['name']}");
            $this->line("   Currency: {$info['currency']} ({$info['currency_symbol']})");
            $this->line("   Phone Prefix: {$info['phone_prefix']}");
            $this->line("   Formality: {$info['formality']}");
            
            // Show field names for this region
            $taxField = Lusophone::getTaxIdFieldName($region);
            $phoneField = Lusophone::getPhoneFieldName($region);
            
            $this->line("   Tax ID Field: <fg=cyan>{$taxField}</fg=cyan>");
            $this->line("   Phone Field: <fg=cyan>{$phoneField}</fg=cyan>");
            
            // Show detection logic used
            if ($environmentType === 'local') {
                $this->line("   <fg=yellow>Detection Logic: Local environment → MZ default</fg=yellow>");
            } else {
                $this->line("   <fg=yellow>Detection Logic: Online environment → IP/Headers/Language</fg=yellow>");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Detection failed: {$e->getMessage()}");
        }
        
        $this->newLine();
    }

    protected function testSpecificRegion($region)
    {
        $region = strtoupper($region);
        
        if (!Lusophone::isLusophoneCountry($region)) {
            $this->error("❌ Invalid region: {$region}");
            $this->line('   Valid regions: MZ, PT, BR, AO, CV, GW, ST, TL');
            return;
        }

        $this->info("🎯 Testing Region: {$region}");
        
        // Force region
        Lusophone::forceRegion($region);
        
        $info = Lusophone::getCountryInfo($region);
        
        $this->table(['Property', 'Value'], [
            ['Country', $info['name']],
            ['Currency Code', $info['currency']],
            ['Currency Symbol', $info['currency_symbol']],
            ['Phone Prefix', $info['phone_prefix']],
            ['Formality Level', $info['formality']],
            ['Tax ID Field', Lusophone::getTaxIdFieldName($region)],
            ['Phone Field', Lusophone::getPhoneFieldName($region)],
            ['Primary Development', $region === 'MZ' ? 'Yes' : 'No'],
        ]);

        $this->newLine();
    }

    protected function testValidation()
    {
        $this->info('✅ Testing Validation Rules:');
        
        $region = Lusophone::detectRegion();
        
        $testCases = [
            'MZ' => [  // MZ first as primary development region
                'tax_id' => '123456789',
                'phone' => '821234567', 
                'postal_code' => '1100'
            ],
            'PT' => [
                'tax_id' => '123456789',
                'phone' => '912345678',
                'postal_code' => '1000-001'
            ],
            'BR' => [
                'tax_id' => '11144477735',
                'phone' => '11987654321',
                'postal_code' => '01310-100'
            ],
            'AO' => [
                'tax_id' => '1234567890',
                'phone' => '912345678',
                'postal_code' => 'LDA-1234'
            ]
        ];

        foreach ($testCases as $testRegion => $tests) {
            $this->line("   Testing {$testRegion}:");
            
            foreach ($tests as $type => $value) {
                try {
                    $result = match($type) {
                        'tax_id' => Lusophone::validateTaxId($value, $testRegion),
                        'phone' => Lusophone::validatePhone($value, $testRegion),
                        'postal_code' => Lusophone::validatePostalCode($value, $testRegion),
                        default => false
                    };
                    
                    $status = $result ? '✅' : '❌';
                    $fieldName = match($type) {
                        'tax_id' => Lusophone::getTaxIdFieldName($testRegion),
                        'phone' => Lusophone::getPhoneFieldName($testRegion),
                        'postal_code' => 'Código Postal',
                        default => $type
                    };
                    
                    $this->line("     {$fieldName}: {$value} {$status}");
                    
                } catch (\Exception $e) {
                    $this->line("     {$type}: {$value} ❌ (Error: {$e->getMessage()})");
                }
            }
            $this->newLine();
        }
    }

    protected function testCurrency()
    {
        $this->info('💰 Testing Currency Formatting:');
        
        $amount = 1234.56;
        $countries = Lusophone::getAllCountries();
        
        // Reorder to show MZ first
        $orderedCountries = ['MZ' => $countries['MZ']] + $countries;
        
        $rows = [];
        foreach ($orderedCountries as $code => $info) {
            try {
                $formatted = Lusophone::formatCurrency($amount, $code);
                $isPrimary = $code === 'MZ' ? ' (Primary Dev)' : '';
                $rows[] = [$code, $info['name'] . $isPrimary, $formatted];
            } catch (\Exception $e) {
                $rows[] = [$code, $info['name'], "Error: {$e->getMessage()}"]; 
            }
        }
        
        $this->table(['Region', 'Country', 'Formatted Amount'], $rows);
        
        // Test string macro
        $this->newLine();
        $this->info('🔧 Testing String Macro:');
        
        $macroResult = Str::lusophoneCurrency($amount);
        $this->line("   Str::lusophoneCurrency({$amount}): {$macroResult}");
        
        $this->newLine();
        
        // Show environment-specific behavior
        $detector = app(RegionDetector::class);
        $envType = $detector->getEnvironmentType();
        
        $this->info('🌍 Environment-Specific Behavior:');
        $this->line("   Current Environment: {$envType}");
        
        if ($envType === 'local') {
            $this->line("   → Currency defaults to MT (Mozambican Metical)");
        } else {
            $this->line("   → Currency auto-detects based on user location");
        }
        
        $this->newLine();
    }
}