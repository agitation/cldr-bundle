<?php
declare(strict_types=1);

/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Tests\Adapter;

use Agit\CldrBundle\Adapter\CountryCurrencyAdapter;

class CountryCurrencyAdapterTest extends AbstractAdapterTest
{
    /**
     * @dataProvider providerGetCountryCurrencyMap
     * @param mixed $countryCode
     * @param mixed $currencyCode
     */
    public function testGetCountryCurrencyMap($countryCode, $currencyCode)
    {
        $countryCurrencyAdapter = new CountryCurrencyAdapter();
        $countryCurrencyAdapter->setCldrDir($this->mockCldrDir());
        $countryCurrencyAdapter->setLocaleService($this->mockLocaleService());

        $map = $countryCurrencyAdapter->getCountryCurrencyMap();

        $this->assertTrue(is_array($map));
        $this->assertArrayHasKey($countryCode, $map);
        $this->assertSame($map[$countryCode], $currencyCode);
    }

    public function providerGetCountryCurrencyMap()
    {
        return [
            ['AU', 'AUD'],
            ['BR', 'BRL'],
            ['CH', 'CHF'],
            ['DE', 'EUR'],
            ['GB', 'GBP'],
            ['US', 'USD'],
            ['ZA', 'ZAR']
        ];
    }
}
