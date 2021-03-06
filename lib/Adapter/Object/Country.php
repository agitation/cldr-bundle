<?php
declare(strict_types=1);

/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Adapter\Object;

class Country extends AbstractObject
{
    private $longCode;

    private $currency;

    private $phone;

    public function __construct($code, $longCode, Currency $currency, $phone)
    {
        $this->code = (string) $code;
        $this->longCode = (string) $longCode;
        $this->currency = $currency;
        $this->phone = $phone;
    }

    public function getLongCode()
    {
        return $this->longCode;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getPhone()
    {
        return $this->phone;
    }
}
