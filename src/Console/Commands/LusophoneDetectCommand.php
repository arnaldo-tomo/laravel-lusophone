<?php

namespace ArnaldoTomo\LaravelLusophone\Console\Commands;

use Illuminate\Console\Command;
use ArnaldoTomo\LaravelLusophone\Facades\Lusophone;
use Illuminate\Support\Str;

class LusophoneDetectCommand extends Command
{
    protected $signature = 'lusophone:detect 
                           {--region= : Test specific region}
                           {--clear-cache : Clear detection cache}
                           {--test-validation : Test validation rules}
                           {--test-currency : Test currency formatting}';

    protected $description = 'Test Lusophone region detection and functionality';

    public function handle()
    {
        $this->info('🔍 Laravel Lusophone Detection Test');
        $this->newLine();

        if ($this->option('clear-cache')) {
            $this->clearCache();
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

    protected function testCurrentDetection()
    {
        $this->info('🎯 Current Detection Results:');
        
        try {
            $region = Lusophone::detectRegion();
            $info = Lusophone::getCountryInfo($region);
            
            $this->line("   Region: {$region}");
            $this->line("   Country: {$info['name']}");
            $this->line("   Currency: {$info['currency']} ({$info['currency_symbol']})");
            $this->line("   Phone Prefix: {$info['phone_prefix']}");
            $this->line("   Formality: {$info['formality']}");
            
            // Show field names for this region
            $taxField = Lusophone::getTaxIdFieldName($region);
            $phoneField = Lusophone::getPhoneFieldName($region);
            
            $this->line("   Tax ID Field: {$taxField}");
            $this->line("   Phone Field: {$phoneField}");
            
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
            $this->line('   Valid regions: PT, BR, MZ, AO, CV, GW, ST, TL');
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
        ]);

        $this->newLine();
    }

    protected function testValidation()
    {
        $this->info('✅ Testing Validation Rules:');
        
        $region = Lusophone::detectRegion();
        
        $testCases = [
            'PT' => [
                'tax_id' => '123456789',
                'phone' => '912345678',
                'postal_code' => '1000-001'
            ],
            'MZ' => [
                'tax_id' => '123456789',
                'phone' => '821234567', 
                'postal_code' => '1100'
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
                $method = 'validate' . ucfirst(str_replace('_', '', $type));
                
                try {
                    $result = match($type) {
                        'tax_id' => Lusophone::validateTaxId($value, $testRegion),
                        'phone' => Lusophone::validatePhone($value, $testRegion),
                        'postal_code' => Lusophone::validatePostalCode($value, $testRegion),
                        default => false
                    };
                    
                    $status = $result ? '✅' : '❌';
                    $this->line("     {$type}: {$value} {$status}");
                    
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
        
        $rows = [];
        foreach ($countries as $code => $info) {
            try {
                $formatted = Lusophone::formatCurrency($amount, $code);
                $rows[] = [$code, $info['name'], $formatted];
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
    }
}
