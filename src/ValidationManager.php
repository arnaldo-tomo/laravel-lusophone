<?php

namespace ArnaldoTomo\LaravelLusophone;

class ValidationManager
{
    protected RegionDetector $regionDetector;

    public function __construct(RegionDetector $regionDetector)
    {
        $this->regionDetector = $regionDetector;
    }
// fdghsfgd
    public function validateTaxId(string $value, string $region = null): bool
    {
        $region = $region ?: $this->regionDetector->detect();

        return match ($region) {
            'PT' => $this->validatePortugueseNIF($value),
            'BR' => $this->validateBrazilCPF($value),
            'MZ' => $this->validateMozambiqueNUIT($value),
            'AO' => $this->validateAngolaNIF($value),
            'CV' => $this->validateCapeVerdeNIF($value),
            default => $this->validatePortugueseNIF($value),
        };
    }

    public function validatePhone(string $value, string $region = null): bool
    {
        $region = $region ?: $this->regionDetector->detect();
        $phone = preg_replace('/[\s\-\(\)]/', '', $value);

        return match ($region) {
            'PT' => $this->validatePortuguesePhone($phone),
            'BR' => $this->validateBrazilPhone($phone),
            'MZ' => $this->validateMozambiquePhone($phone),
            'AO' => $this->validateAngolaPhone($phone),
            'CV' => $this->validateCapeVerdePhone($phone),
            default => $this->validatePortuguesePhone($phone),
        };
    }

    public function validatePostalCode(string $value, string $region = null): bool
    {
        $region = $region ?: $this->regionDetector->detect();

        return match ($region) {
            'PT' => preg_match('/^\d{4}-\d{3}$/', $value),
            'BR' => preg_match('/^\d{5}-?\d{3}$/', $value),
            'MZ' => preg_match('/^\d{4}$/', $value),
            'AO' => preg_match('/^[A-Z]{3}-\d{4}$/', $value),
            'CV' => preg_match('/^\d{4}$/', $value),
            default => preg_match('/^\d{4}-\d{3}$/', $value),
        };
    }

    // Portugal NIF validation
    public function validatePortugueseNIF(string $nif): bool
    {
        $nif = preg_replace('/\D/', '', $nif);
        
        if (strlen($nif) !== 9) {
            return false;
        }

        $validFirstDigits = [1, 2, 3, 5, 6, 8, 9];
        if (!in_array((int) $nif[0], $validFirstDigits)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 8; $i++) {
            $sum += (int) $nif[$i] * (9 - $i);
        }

        $checkDigit = 11 - ($sum % 11);
        if ($checkDigit >= 10) {
            $checkDigit = 0;
        }

        return (int) $nif[8] === $checkDigit;
    }

    // Mozambique NUIT validation  
    public function validateMozambiqueNUIT(string $nuit): bool
    {
        $nuit = preg_replace('/\D/', '', $nuit);
        return strlen($nuit) === 9 && (int) $nuit[0] !== 0;
    }

    // Brazil CPF validation
    public function validateBrazilCPF(string $cpf): bool
    {
        $cpf = preg_replace('/\D/', '', $cpf);
        
        if (strlen($cpf) !== 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Calculate first check digit
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $cpf[$i] * (10 - $i);
        }
        $remainder = $sum % 11;
        $checkDigit1 = $remainder < 2 ? 0 : 11 - $remainder;

        if ((int) $cpf[9] !== $checkDigit1) {
            return false;
        }

        // Calculate second check digit
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += (int) $cpf[$i] * (11 - $i);
        }
        $remainder = $sum % 11;
        $checkDigit2 = $remainder < 2 ? 0 : 11 - $remainder;

        return (int) $cpf[10] === $checkDigit2;
    }

    // Angola NIF validation
    public function validateAngolaNIF(string $nif): bool
    {
        $nif = preg_replace('/\D/', '', $nif);
        return strlen($nif) === 10 && preg_match('/^\d{10}$/', $nif);
    }

    // Cape Verde NIF validation
    public function validateCapeVerdeNIF(string $nif): bool
    {
        $nif = preg_replace('/\D/', '', $nif);
        return strlen($nif) === 9 && preg_match('/^\d{9}$/', $nif);
    }

    // Phone validations
    protected function validatePortuguesePhone(string $phone): bool
    {
        $phone = preg_replace('/^(\+351|351)/', '', $phone);
        return preg_match('/^[29]\d{8}$/', $phone);
    }

    protected function validateMozambiquePhone(string $phone): bool
    {
        $phone = preg_replace('/^(\+258|258)/', '', $phone);
        return preg_match('/^[82]\d{7}$/', $phone);
    }

    protected function validateBrazilPhone(string $phone): bool
    {
        $phone = preg_replace('/^(\+55|55)/', '', $phone);
        return preg_match('/^[1-9]{2}[2-9]\d{7,8}$/', $phone);
    }

    protected function validateAngolaPhone(string $phone): bool
    {
        $phone = preg_replace('/^(\+244|244)/', '', $phone);
        return preg_match('/^9\d{8}$/', $phone);
    }

    protected function validateCapeVerdePhone(string $phone): bool
    {
        $phone = preg_replace('/^(\+238|238)/', '', $phone);
        return preg_match('/^\d{7}$/', $phone);
    }

    public function getTaxIdFieldName(string $region = null): string
    {
        $region = $region ?: $this->regionDetector->detect();

        return match ($region) {
            'PT' => 'NIF',
            'BR' => 'CPF', 
            'MZ' => 'NUIT',
            'AO' => 'NIF',
            'CV' => 'NIF',
            default => 'NIF',
        };
    }

    public function getPhoneFieldName(string $region = null): string
    {
        $region = $region ?: $this->regionDetector->detect();

        return match ($region) {
            'PT', 'AO' => 'Telemóvel',
            'BR', 'MZ' => 'Celular', 
            'CV' => 'Telefone',
            default => 'Telemóvel',
        };
    }
}
