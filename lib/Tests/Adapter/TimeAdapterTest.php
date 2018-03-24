<?php
declare(strict_types=1);

/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Tests\Adapter;

use Agit\CldrBundle\Adapter\TimeAdapter;

class TimeAdapterTest extends AbstractAdapterTest
{
    /**
     * @dataProvider providerMonths
     * @param mixed $code
     * @param mixed $nameEn
     * @param mixed $nameDe
     */
    public function testGetMonths($code, $nameEn, $nameDe)
    {
        $timeAdapter = $this->createInstance();
        $months = $timeAdapter->getMonths();

        $this->assertTrue(is_array($months));
        $this->assertArrayHasKey($code, $months);
        $this->assertSame($code, $months[$code]->getCode());
        $this->assertSame($nameEn, $months[$code]->getName('en_US'));
        $this->assertSame($nameDe, $months[$code]->getName('de_DE'));
    }

    /**
     * @dataProvider providerMonths
     * @param mixed $code
     * @param mixed $nameEn
     * @param mixed $nameDe
     */
    public function testGetMonth($code, $nameEn, $nameDe)
    {
        $timeAdapter = $this->createInstance();
        $month = $timeAdapter->getMonth($code);
        $this->assertTrue(is_object($month));
        $this->assertSame('Agit\CldrBundle\Adapter\Object\Month', get_class($month));
        $this->assertSame($nameEn, $month->getName('en_US'));
        $this->assertSame($nameDe, $month->getName('de_DE'));
    }

    /**
     * @dataProvider providerWeekdays
     * @param mixed $code
     * @param mixed $nameEn
     * @param mixed $nameDe
     */
    public function testGetWeekdays($code, $nameEn, $nameDe)
    {
        $timeAdapter = $this->createInstance();
        $weekdays = $timeAdapter->getWeekdays();

        $this->assertTrue(is_array($weekdays));
        $this->assertArrayHasKey($code, $weekdays);
        $this->assertSame($code, $weekdays[$code]->getCode());
        $this->assertSame($nameEn, $weekdays[$code]->getName('en_US'));
        $this->assertSame($nameDe, $weekdays[$code]->getName('de_DE'));
    }

    /**
     * @dataProvider providerWeekdays
     * @param mixed $code
     * @param mixed $nameEn
     * @param mixed $nameDe
     */
    public function testGetWeekday($code, $nameEn, $nameDe)
    {
        $timeAdapter = $this->createInstance();
        $weekday = $timeAdapter->getWeekday($code);
        $this->assertTrue(is_object($weekday));
        $this->assertSame('Agit\CldrBundle\Adapter\Object\Weekday', get_class($weekday));
        $this->assertSame($nameEn, $weekday->getName('en_US'));
        $this->assertSame($nameDe, $weekday->getName('de_DE'));
    }

    public function createInstance()
    {
        $timeAdapter = new TimeAdapter();
        $timeAdapter->setCldrDir($this->mockCldrDir());
        $timeAdapter->setLocaleService($this->mockLocaleService());

        return $timeAdapter;
    }

    public function providerMonths()
    {
        return [
            ['1', 'January', 'Januar'],
            ['3', 'March', 'März'],
            ['12', 'December', 'Dezember']
        ];
    }

    public function providerWeekdays()
    {
        return [
            ['mon', 'Monday', 'Montag'],
            ['sat', 'Saturday', 'Samstag'],
            ['sun', 'Sunday', 'Sonntag']
        ];
    }
}
