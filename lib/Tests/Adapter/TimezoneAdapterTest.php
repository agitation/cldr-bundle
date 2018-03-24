<?php
declare(strict_types=1);

/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Tests\Adapter;

use Agit\CldrBundle\Adapter\TimezoneAdapter;

class TimezoneAdapterTest extends AbstractAdapterTest
{
    /**
     * @dataProvider providerTimezones
     * @param mixed $code
     * @param mixed $nameEn
     * @param mixed $nameDe
     */
    public function testGetTimezones($code, $nameEn, $nameDe)
    {
        $timezoneAdapter = $this->createInstance();
        $timezones = $timezoneAdapter->getTimezones();

        $this->assertTrue(is_array($timezones));
        $this->assertArrayHasKey($code, $timezones);
        $this->assertSame($code, $timezones[$code]->getCode());
        $this->assertSame($nameEn, $timezones[$code]->getName('en_US'));
        $this->assertSame($nameDe, $timezones[$code]->getName('de_DE'));
    }

    /**
     * @dataProvider providerTimezones
     * @param mixed $code
     * @param mixed $nameEn
     * @param mixed $nameDe
     */
    public function testGetTimezone($code, $nameEn, $nameDe)
    {
        $timezoneAdapter = $this->createInstance();
        $timezone = $timezoneAdapter->getTimezone($code);
        $this->assertTrue(is_object($timezone));
        $this->assertSame('Agit\CldrBundle\Adapter\Object\Timezone', get_class($timezone));
        $this->assertSame($nameEn, $timezone->getName('en_US'));
        $this->assertSame($nameDe, $timezone->getName('de_DE'));
    }

    public function createInstance()
    {
        $timezoneAdapter = new TimezoneAdapter($this->mockCountryAdapter());
        $timezoneAdapter->setCldrDir($this->mockCldrDir());
        $timezoneAdapter->setLocaleService($this->mockLocaleService());

        return $timezoneAdapter;
    }

    public function providerTimezones()
    {
        return [
            ['Europe/Berlin', 'Germany, Berlin', 'Deutschland, Berlin'],
            ['Europe/London', 'United Kingdom, London', 'Vereinigtes Königreich, London']
        ];
    }
}
