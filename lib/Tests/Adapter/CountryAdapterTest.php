<?php

/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Tests\Adapter;

use Agit\CldrBundle\Adapter\CountryAdapter;

class CountryAdapterTest extends AbstractAdapterTest
{
    /**
     * @dataProvider providerCountries
     */
    public function testGetCountries($code, $nameEn, $nameDe)
    {
        $countryAdapter = $this->createInstance();
        $countries = $countryAdapter->getCountries();

        $this->assertTrue(is_array($countries));
        $this->assertArrayHasKey($code, $countries);
        $this->assertSame($code, $countries[$code]->getCode());
        $this->assertSame($nameEn, $countries[$code]->getName('en_US'));
        $this->assertSame($nameDe, $countries[$code]->getName('de_DE'));
    }

    /**
     * @dataProvider providerCountries
     */
    public function testGetCountry($code, $nameEn, $nameDe)
    {
        $countryAdapter = $this->createInstance();
        $country = $countryAdapter->getCountry($code);
        $this->assertTrue(is_object($country));
        $this->assertSame('Agit\CldrBundle\Adapter\Object\Country', get_class($country));
        $this->assertSame($nameEn, $country->getName('en_US'));
        $this->assertSame($nameDe, $country->getName('de_DE'));
    }

    public function createInstance()
    {
        $countryAdapter = new CountryAdapter($this->mockCurrencyAdapter(), $this->mockCountryCurrencyAdapter());
        $countryAdapter->setCldrDir($this->mockCldrDir());
        $countryAdapter->setLocaleService($this->mockLocaleService());

        return $countryAdapter;
    }

    public function providerCountries()
    {
        return [
            ['BR', 'Brazil', 'Brasilien'],
            ['DE', 'Germany', 'Deutschland'],
            ['GB', 'United Kingdom', 'Vereinigtes Königreich']
        ];
    }
}
