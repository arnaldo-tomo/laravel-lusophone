<?php

namespace ArnaldoTomo\LaravelLusophone\Tests\Feature;

use ArnaldoTomo\LaravelLusophone\Tests\TestCase;
use ArnaldoTomo\LaravelLusophone\Facades\Lusophone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LusophoneIntegrationTest extends TestCase
{
    /** @test */
    public function it_registers_lusophone_validation_rules()
    {
        $validator = Validator::make(
            ['tax_id' => '123456789'],
            ['tax_id' => 'lusophone_tax_id']
        );
        
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function it_validates_phone_numbers_with_lusophone_rule()
    {
        Lusophone::forceRegion('PT');
        
        $validator = Validator::make(
            ['phone' => '912345678'],
            ['phone' => 'lusophone_phone']
        );
        
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function it_validates_postal_codes_with_lusophone_rule()
    {
        Lusophone::forceRegion('PT');
        
        $validator = Validator::make(
            ['postal_code' => '1000-001'],
            ['postal_code' => 'lusophone_postal']
        );
        
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function it_registers_string_macros()
    {
        Lusophone::forceRegion('PT');
        
        $formatted = Str::lusophoneCurrency(1500.50);
        $this->assertEquals('1 500,50 €', $formatted);
        
        $number = Str::lusophoneNumber(1500.50);
        $this->assertEquals('1 500,50', $number);
    }

    /** @test */
    public function it_registers_collection_macros()
    {
        $countries = collect()->lusophoneCountries();
        
        $this->assertArrayHasKey('PT', $countries->toArray());
        $this->assertArrayHasKey('BR', $countries->toArray());
        $this->assertArrayHasKey('MZ', $countries->toArray());
        $this->assertEquals('Portugal', $countries['PT']);
        $this->assertEquals('Moçambique', $countries['MZ']);
    }

    /** @test */
    public function it_works_with_facade()
    {
        $region = Lusophone::detectRegion();
        $this->assertEquals('PT', $region);
        
        $info = Lusophone::getCountryInfo('MZ');
        $this->assertEquals('Moçambique', $info['name']);
        
        $this->assertTrue(Lusophone::isLusophoneCountry('PT'));
        $this->assertFalse(Lusophone::isLusophoneCountry('US'));
    }

    /** @test */
    public function it_validates_specific_country_rules()
    {
        // Portugal NIF
        $validator = Validator::make(
            ['nif' => '123456789'],
            ['nif' => 'nif_portugal']
        );
        $this->assertFalse($validator->fails());
        
        // Mozambique NUIT
        $validator = Validator::make(
            ['nuit' => '123456789'],
            ['nuit' => 'nuit_mozambique']
        );
        $this->assertFalse($validator->fails());
        
        // Brazil CPF
        $validator = Validator::make(
            ['cpf' => '11144477735'],
            ['cpf' => 'cpf_brazil']
        );
        $this->assertFalse($validator->fails());
    }

    /** @test */
    public function it_provides_validation_error_messages()
    {
        $validator = Validator::make(
            ['tax_id' => 'invalid'],
            ['tax_id' => 'lusophone_tax_id']
        );
        
        $this->assertTrue($validator->fails());
        $this->assertStringContains('não é um documento fiscal válido', $validator->errors()->first('tax_id'));
    }

    /** @test */
    public function it_adapts_validation_by_region()
    {
        // Portugal - should validate as NIF
        Lusophone::forceRegion('PT');
        $this->assertTrue(Lusophone::validateTaxId('123456789'));
        
        // Mozambique - should validate as NUIT
        Lusophone::forceRegion('MZ');
        $this->assertTrue(Lusophone::validateTaxId('123456789'));
        
        // Brazil - should validate as CPF
        Lusophone::forceRegion('BR');
        $this->assertTrue(Lusophone::validateTaxId('11144477735'));
    }
}
