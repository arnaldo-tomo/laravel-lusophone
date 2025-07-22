<?php

namespace ArnaldoTomo\LaravelLusophone\Tests\Unit;

use ArnaldoTomo\LaravelLusophone\RegionDetector;
use ArnaldoTomo\LaravelLusophone\Tests\TestCase;
use Illuminate\Http\Request;

class RegionDetectorTest extends TestCase
{
    protected RegionDetector $detector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->detector = new RegionDetector();
    }

    /** @test */
    public function it_detects_portugal_as_default_region()
    {
        $region = $this->detector->detect();
        $this->assertEquals('PT', $region);
    }

    /** @test */
    public function it_can_force_specific_region()
    {
        $this->detector->forceRegion('MZ');
        $region = $this->detector->detect();
        $this->assertEquals('MZ', $region);
    }

    /** @test */
    public function it_validates_lusophone_countries()
    {
        $this->assertTrue($this->detector->isLusophoneCountry('PT'));
        $this->assertTrue($this->detector->isLusophoneCountry('BR'));
        $this->assertTrue($this->detector->isLusophoneCountry('MZ'));
        $this->assertTrue($this->detector->isLusophoneCountry('AO'));
        $this->assertFalse($this->detector->isLusophoneCountry('US'));
        $this->assertFalse($this->detector->isLusophoneCountry('FR'));
    }

    /** @test */
    public function it_returns_country_information()
    {
        $info = $this->detector->getCountryInfo('MZ');
        
        $this->assertEquals('Moçambique', $info['name']);
        $this->assertEquals('MZN', $info['currency']);
        $this->assertEquals('MT', $info['currency_symbol']);
        $this->assertEquals('+258', $info['phone_prefix']);
    }

    /** @test */
    public function it_returns_currency_information()
    {
        $currency = $this->detector->getCurrencyInfo('PT');
        
        $this->assertEquals('EUR', $currency['code']);
        $this->assertEquals('€', $currency['symbol']);
    }

    /** @test */
    public function it_returns_all_lusophone_countries()
    {
        $countries = $this->detector->getAllCountries();
        
        $this->assertArrayHasKey('PT', $countries);
        $this->assertArrayHasKey('BR', $countries);
        $this->assertArrayHasKey('MZ', $countries);
        $this->assertArrayHasKey('AO', $countries);
        $this->assertArrayHasKey('CV', $countries);
    }

    /** @test */
    public function it_detects_from_cloudflare_header()
    {
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_CF_IPCOUNTRY' => 'MZ']);
        $detector = new RegionDetector($request);
        
        config(['lusophone.force_region' => null]);
        
        $region = $detector->detect();
        $this->assertEquals('MZ', $region);
    }
}
