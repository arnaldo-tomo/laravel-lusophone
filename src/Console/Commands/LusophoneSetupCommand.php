<?php

namespace ArnaldoTomo\LaravelLusophone\Console\Commands;

use Illuminate\Console\Command;
use ArnaldoTomo\LaravelLusophone\Facades\Lusophone;

class LusophoneSetupCommand extends Command
{
    protected $signature = 'lusophone:setup 
                           {--region= : Force a specific region (PT, BR, MZ, AO, CV, etc.)}
                           {--publish : Publish config and language files}';

    protected $description = 'Setup Laravel Lusophone package for your application';

    public function handle()
    {
        $this->info('🌍 Setting up Laravel Lusophone...');
        $this->newLine();

        // Publish files if requested
        if ($this->option('publish')) {
            $this->publishFiles();
        }

        // Setup region
        $this->setupRegion();

        // Test detection
        $this->testDetection();

        // Show summary
        $this->showSummary();

        $this->newLine();
        $this->info('✅ Laravel Lusophone setup completed successfully!');
    }

    protected function publishFiles()
    {
        $this->info('📦 Publishing configuration and language files...');

        // Publish config
        $this->call('vendor:publish', [
            '--tag' => 'lusophone-config',
            '--force' => true
        ]);

        // Publish language files
        $this->call('vendor:publish', [
            '--tag' => 'lusophone-lang',
            '--force' => true
        ]);

        $this->info('   ✓ Files published successfully');
        $this->newLine();
    }

    protected function setupRegion()
    {
        $region = $this->option('region');

        if ($region) {
            $region = strtoupper($region);
            
            if (!Lusophone::isLusophoneCountry($region)) {
                $this->error("❌ Invalid region: {$region}");
                $this->line('   Valid regions: PT, BR, MZ, AO, CV, GW, ST, TL');
                return;
            }

            // Update .env file
            $this->updateEnvFile('LUSOPHONE_FORCE_REGION', $region);
            $this->info("🎯 Region forced to: {$region}");
        } else {
            $this->info('🔍 Auto-detection enabled (default behavior)');
        }
    }

    protected function testDetection()
    {
        $this->info('🧪 Testing region detection...');

        try {
            $detected = Lusophone::detectRegion();
            $info = Lusophone::getCountryInfo($detected);
            
            $this->line("   ✓ Detected region: {$detected} ({$info['name']})");
            $this->line("   ✓ Currency: {$info['currency']} ({$info['currency_symbol']})");
            $this->line("   ✓ Phone prefix: {$info['phone_prefix']}");
            
            // Test currency formatting
            $formatted = Lusophone::formatCurrency(1500.50, $detected);
            $this->line("   ✓ Currency format: {$formatted}");

        } catch (\Exception $e) {
            $this->error("❌ Detection failed: {$e->getMessage()}");
        }
    }

    protected function showSummary()
    {
        $this->newLine();
        $this->info('📋 Configuration Summary:');
        
        $countries = Lusophone::getAllCountries();
        
        $headers = ['Code', 'Country', 'Currency', 'Symbol', 'Phone'];
        $rows = [];
        
        foreach ($countries as $code => $info) {
            $rows[] = [
                $code,
                $info['name'],
                $info['currency'],
                $info['currency_symbol'],
                $info['phone_prefix']
            ];
        }
        
        $this->table($headers, $rows);
    }

    protected function updateEnvFile($key, $value)
    {
        $path = base_path('.env');
        
        if (!file_exists($path)) {
            $this->warn('   ⚠️  .env file not found - please set manually');
            return;
        }

        $content = file_get_contents($path);
        
        if (str_contains($content, "{$key}=")) {
            // Update existing
            $content = preg_replace("/^{$key}=.*$/m", "{$key}={$value}", $content);
        } else {
            // Add new
            $content .= "\n{$key}={$value}\n";
        }
        
        file_put_contents($path, $content);
        $this->line("   ✓ Updated .env: {$key}={$value}");
    }
}
