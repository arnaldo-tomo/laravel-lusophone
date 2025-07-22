<?php

namespace ArnaldoTomo\LaravelLusophone\Console\Commands;

use Illuminate\Console\Command;
use ArnaldoTomo\LaravelLusophone\Facades\Lusophone;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;

class LusophoneAnalyzeCommand extends Command
{
    protected $signature = 'lusophone:analyze 
                           {--missing : Show missing translations}
                           {--coverage : Show translation coverage}
                           {--export= : Export analysis to file (json, csv)}';

    protected $description = 'Analyze Lusophone translations usage and coverage';

    public function handle()
    {
        $this->info('🔍 Analyzing Laravel Lusophone translations...');
        $this->newLine();

        if ($this->option('missing')) {
            $this->analyzeMissingTranslations();
        }

        if ($this->option('coverage')) {
            $this->analyzeCoverage();
        }

        if (!$this->option('missing') && !$this->option('coverage')) {
            $this->showOverview();
            $this->analyzeCoverage();
            $this->analyzeMissingTranslations();
        }

        if ($exportFormat = $this->option('export')) {
            $this->exportAnalysis($exportFormat);
        }

        $this->newLine();
        $this->info('✅ Analysis completed!');
    }

    protected function showOverview()
    {
        $this->info('📊 Overview:');
        
        $regions = Lusophone::getAllCountries();
        $detected = Lusophone::detectRegion();
        $detectedInfo = Lusophone::getCountryInfo($detected);

        $this->line("   Current region: {$detected} ({$detectedInfo['name']})");
        $this->line("   Supported regions: " . count($regions));
        $this->line("   Available validators: lusophone_tax_id, lusophone_phone, lusophone_postal");
        
        $this->newLine();
    }

    protected function analyzeCoverage()
    {
        $this->info('📈 Translation Coverage:');
        
        $langPath = resource_path('lang/pt');
        $packageLangPath = __DIR__ . '/../../../resources/lang/pt';
        
        $files = [
            'validation.php' => 'Validation Rules',
            'auth.php' => 'Authentication',
            'pagination.php' => 'Pagination',
            'passwords.php' => 'Password Reset'
        ];

        $rows = [];
        foreach ($files as $file => $description) {
            $exists = File::exists($langPath . '/' . $file) || File::exists($packageLangPath . '/' . $file);
            $status = $exists ? '✅ Available' : '❌ Missing';
            
            if ($exists) {
                $translations = $this->loadTranslations($file);
                $count = $this->countTranslations($translations);
                $status .= " ({$count} translations)";
            }
            
            $rows[] = [$file, $description, $status];
        }

        $this->table(['File', 'Description', 'Status'], $rows);
        $this->newLine();
    }

    protected function analyzeMissingTranslations()
    {
        $this->info('🔍 Missing Translations Analysis:');
        
        // Check for common Laravel keys that might need translation
        $commonKeys = [
            'validation.required',
            'validation.email', 
            'validation.min.string',
            'validation.max.string',
            'auth.failed',
            'passwords.reset'
        ];

        $missing = [];
        $available = [];

        foreach ($commonKeys as $key) {
            if (Lang::has($key, 'pt')) {
                $available[] = $key;
            } else {
                $missing[] = $key;
            }
        }

        if (empty($missing)) {
            $this->line('   ✅ All common translations are available!');
        } else {
            $this->warn('   ⚠️  Missing common translations:');
            foreach ($missing as $key) {
                $this->line("      - {$key}");
            }
        }

        $this->line("   📊 Coverage: " . count($available) . "/" . count($commonKeys) . " (" . 
                   round((count($available) / count($commonKeys)) * 100, 1) . "%)");
        
        $this->newLine();
    }

    protected function exportAnalysis($format)
    {
        $this->info("📤 Exporting analysis to {$format} format...");
        
        $data = [
            'timestamp' => now()->toISOString(),
            'detected_region' => Lusophone::detectRegion(),
            'supported_regions' => Lusophone::getAllCountries(),
            'validation_rules' => [
                'lusophone_tax_id' => 'Universal tax ID validation',
                'lusophone_phone' => 'Universal phone validation', 
                'lusophone_postal' => 'Universal postal code validation',
                'nif_portugal' => 'Portugal NIF validation',
                'nuit_mozambique' => 'Mozambique NUIT validation',
                'cpf_brazil' => 'Brazil CPF validation'
            ]
        ];

        $filename = "lusophone-analysis-" . now()->format('Y-m-d-H-i-s');
        
        switch ($format) {
            case 'json':
                $content = json_encode($data, JSON_PRETTY_PRINT);
                $filename .= '.json';
                break;
                
            case 'csv':
                $content = $this->arrayToCsv($data);
                $filename .= '.csv';
                break;
                
            default:
                $this->error("❌ Unsupported format: {$format}");
                return;
        }

        File::put(storage_path("app/{$filename}"), $content);
        $this->line("   ✓ Exported to: storage/app/{$filename}");
    }

    protected function loadTranslations($file)
    {
        $langPath = resource_path('lang/pt/' . $file);
        $packageLangPath = __DIR__ . '/../../../resources/lang/pt/' . $file;
        
        if (File::exists($langPath)) {
            return include $langPath;
        }
        
        if (File::exists($packageLangPath)) {
            return include $packageLangPath;
        }
        
        return [];
    }

    protected function countTranslations($translations, $prefix = '')
    {
        $count = 0;
        
        foreach ($translations as $key => $value) {
            if (is_array($value)) {
                $count += $this->countTranslations($value, $prefix . $key . '.');
            } else {
                $count++;
            }
        }
        
        return $count;
    }

    protected function arrayToCsv($data)
    {
        $csv = "Key,Value\n";
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    $csv .= "\"{$key}.{$subKey}\",\"" . (is_array($subValue) ? json_encode($subValue) : $subValue) . "\"\n";
                }
            } else {
                $csv .= "\"{$key}\",\"{$value}\"\n";
            }
        }
        
        return $csv;
    }
}
