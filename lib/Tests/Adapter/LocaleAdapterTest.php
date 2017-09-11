<?php
declare(strict_types=1);
/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Tests\Adapter;

use Agit\CldrBundle\Adapter\LocaleAdapter;

class LocaleAdapterTest extends AbstractAdapterTest
{
    /**
     * @dataProvider providerLocales
     * @param mixed $code
     * @param mixed $nameEn
     * @param mixed $nameDe
     */
    public function testGetLocales($code, $nameEn, $nameDe)
    {
        $localeAdapter = $this->createInstance();
        $locales = $localeAdapter->getLocales();

        $this->assertTrue(is_array($locales));
        $this->assertArrayHasKey($code, $locales);
        $this->assertSame($code, $locales[$code]->getCode());
        $this->assertSame($nameEn, $locales[$code]->getName('en_US'));
        $this->assertSame($nameDe, $locales[$code]->getName('de_DE'));
    }

    /**
     * @dataProvider providerLocales
     * @param mixed $code
     * @param mixed $nameEn
     * @param mixed $nameDe
     */
    public function testGetLocale($code, $nameEn, $nameDe)
    {
        $localeAdapter = $this->createInstance();
        $locale = $localeAdapter->getLocale($code);
        $this->assertTrue(is_object($locale));
        $this->assertSame('Agit\CldrBundle\Adapter\Object\Locale', get_class($locale));
        $this->assertSame($nameEn, $locale->getName('en_US'));
        $this->assertSame($nameDe, $locale->getName('de_DE'));
    }

    public function createInstance()
    {
        $localeAdapter = new LocaleAdapter($this->mockCountryAdapter());
        $localeAdapter->setCldrDir($this->mockCldrDir());
        $localeAdapter->setLocaleService($this->mockLocaleService());

        return $localeAdapter;
    }

    public function providerLocales()
    {
        return [
            ['de_DE', 'German', 'Deutsch'],
            ['en_US', 'English', 'Englisch']
        ];
    }
}
