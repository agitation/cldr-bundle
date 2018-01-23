<?php
declare(strict_types=1);
/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Adapter\Object;

class Currency extends AbstractObject
{
    protected $symbol;

    protected $digits;

    public function __construct(string $code, string $symbol, int $digits)
    {
        parent::__construct($code);
        $this->symbol = $symbol;
        $this->digits = $digits;
    }

    public function getSymbol() : string
    {
        return $this->symbol;
    }

    public function getDigits() : int
    {
        return $this->digits;
    }
}
