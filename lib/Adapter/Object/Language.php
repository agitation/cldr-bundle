<?php
declare(strict_types=1);

/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Adapter\Object;

class Language extends AbstractObject
{
    private $countries = [];

    private $localName;

    public function __construct($code, $localName)
    {
        $this->code = (string) $code;
        $this->localName = (string) $localName;
    }

    public function addCountry(Country $country)
    {
        $this->countries[] = $country;
    }

    public function getLocalName()
    {
        return $this->localName;
    }

    public function getCountries()
    {
        return $this->countries;
    }
}
