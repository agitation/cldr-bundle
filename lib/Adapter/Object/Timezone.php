<?php
declare(strict_types=1);
/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Adapter\Object;

class Timezone extends AbstractObject
{
    private $country;

    public function __construct($code, Country $country)
    {
        $this->code = (string) $code;
        $this->country = $country;
    }

    public function getCountry()
    {
        return $this->country;
    }
}
