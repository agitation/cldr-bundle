<?php
declare(strict_types=1);

/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Tests\Adapter;

use Agit\CldrBundle\Adapter\CurrencyAdapter;

class CurrencyAdapterTest extends AbstractAdapterTest
{
    /**
     * @dataProvider providerCurrencies
     * @param mixed $code
     * @param mixed $nameEn
     * @param mixed $nameDe
     */
    public function testGetCurrencies($code, $nameEn, $nameDe)
    {
        $currencyAdapter = $this->createInstance();
        $currencies = $currencyAdapter->getCurrencies();

        $this->assertTrue(is_array($currencies));
        $this->assertArrayHasKey($code, $currencies);
        $this->assertSame($code, $currencies[$code]->getCode());
        $this->assertSame($nameEn, $currencies[$code]->getName('en_US'));
        $this->assertSame($nameDe, $currencies[$code]->getName('de_DE'));
    }

    /**
     * @dataProvider providerCurrencies
     * @param mixed $code
     * @param mixed $nameEn
     * @param mixed $nameDe
     */
    public function testGetCurrency($code, $nameEn, $nameDe)
    {
        $currencyAdapter = $this->createInstance();
        $currency = $currencyAdapter->getCurrency($code);
        $this->assertTrue(is_object($currency));
        $this->assertSame('Agit\CldrBundle\Adapter\Object\Currency', get_class($currency));
        $this->assertSame($nameEn, $currency->getName('en_US'));
        $this->assertSame($nameDe, $currency->getName('de_DE'));
    }

    public function createInstance()
    {
        $currencyAdapter = new CurrencyAdapter($this->mockCountryCurrencyAdapter());
        $currencyAdapter->setCldrDir($this->mockCldrDir());
        $currencyAdapter->setLocaleService($this->mockLocaleService());

        return $currencyAdapter;
    }

    public function providerCurrencies()
    {
        return [
            ['BRL', 'Brazilian Real', 'Brasilianischer Real'],
            ['EUR', 'Euro', 'Euro'],
            ['GBP', 'British Pound Sterling', 'Britisches Pfund Sterling'],
            ['USD', 'US Dollar', 'US-Dollar']
        ];
    }
}
