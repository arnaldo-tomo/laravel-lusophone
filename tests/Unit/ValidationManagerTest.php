<?php

namespace ArnaldoTomo\LaravelLusophone\Tests\Unit;

use ArnaldoTomo\LaravelLusophone\ValidationManager;
use ArnaldoTomo\LaravelLusophone\RegionDetector;
use ArnaldoTomo\LaravelLusophone\Tests\TestCase;

class ValidationManagerTest extends TestCase
{
    protected ValidationManager $validator;
    protected RegionDetector $detector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->detector = new RegionDetector();
        $this->validator = new ValidationManager($this->detector);
    }

    /** @test */
    public function it_validates_portuguese_nif()
    {
        // Valid Portuguese NIF
        $this->assertTrue($this->validator->validatePortugueseNIF('123456789'));
        
        // Invalid Portuguese NIF
        $this->assertFalse($this->validator->validatePortugueseNIF('123456788'));
        $this->assertFalse($this->validator->validatePortugueseNIF('12345678'));
        $this->assertFalse($this->validator->validatePortugueseNIF('0123456789'));
    }

    /** @test */
    public function it_validates_mozambique_nuit()
    {
        // Valid Mozambique NUIT
        $this->assertTrue($this->validator->validateMozambiqueNUIT('123456789'));
        
        // Invalid Mozambique NUIT
        $this->assertFalse($this->validator->validateMozambiqueNUIT('012345678'));
        $this->assertFalse($this->validator->validateMozambiqueNUIT('12345678'));
        $this->assertFalse($this->validator->validateMozambiqueNUIT('1234567890'));
    }

    /** @test */
    public function it_validates_brazil_cpf()
    {
        // Valid Brazilian CPF
        $this->assertTrue($this->validator->validateBrazilCPF('11144477735'));
        
        // Invalid Brazilian CPF
        $this->assertFalse($this->validator->validateBrazilCPF('11111111111'));
        $this->assertFalse($this->validator->validateBrazilCPF('123456789'));
        $this->assertFalse($this->validator->validateBrazilCPF('11144477736'));
    }

    /** @test */
    public function it_validates_phones_by_region()
    {
        // Portugal
        $this->assertTrue($this->validator->validatePhone('+351912345678', 'PT'));
        $this->assertTrue($this->validator->validatePhone('912345678', 'PT'));
        
        // Mozambique
        $this->assertTrue($this->validator->validatePhone('+258821234567', 'MZ'));
        $this->assertTrue($this->validator->validatePhone('821234567', 'MZ'));
        
        // Brazil
        $this->assertTrue($this->validator->validatePhone('+5511987654321', 'BR'));
        $this->assertTrue($this->validator->validatePhone('11987654321', 'BR'));
    }

    /** @test */
    public function it_validates_postal_codes_by_region()
    {
        // Portugal
        $this->assertTrue($this->validator->validatePostalCode('1000-001', 'PT'));
        $this->assertFalse($this->validator->validatePostalCode('1000001', 'PT'));
        
        // Mozambique
        $this->assertTrue($this->validator->validatePostalCode('1100', 'MZ'));
        $this->assertFalse($this->validator->validatePostalCode('1100-001', 'MZ'));
        
        // Brazil
        $this->assertTrue($this->validator->validatePostalCode('01310-100', 'BR'));
        $this->assertTrue($this->validator->validatePostalCode('01310100', 'BR'));
    }

    /** @test */
    public function it_returns_correct_field_names_by_region()
    {
        $this->assertEquals('NIF', $this->validator->getTaxIdFieldName('PT'));
        $this->assertEquals('NUIT', $this->validator->getTaxIdFieldName('MZ'));
        $this->assertEquals('CPF', $this->validator->getTaxIdFieldName('BR'));
        
        $this->assertEquals('Telemóvel', $this->validator->getPhoneFieldName('PT'));
        $this->assertEquals('Celular', $this->validator->getPhoneFieldName('MZ'));
        $this->assertEquals('Celular', $this->validator->getPhoneFieldName('BR'));
    }

    /** @test */
    public function it_validates_tax_id_by_detected_region()
    {
        // Force Portugal region
        $this->detector->forceRegion('PT');
        $this->assertTrue($this->validator->validateTaxId('123456789'));
        
        // Force Mozambique region
        $this->detector->forceRegion('MZ');
        $this->assertTrue($this->validator->validateTaxId('123456789'));
        
        // Force Brazil region
        $this->detector->forceRegion('BR');
        $this->assertTrue($this->validator->validateTaxId('11144477735'));
    }
}
