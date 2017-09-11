<?php
declare(strict_types=1);
/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Adapter;

use Agit\CldrBundle\Adapter\Object\Currency;

class CurrencyAdapter extends AbstractAdapter
{
    protected $countryCurrencyAdapter;

    public function __construct(CountryCurrencyAdapter $countryCurrencyAdapter)
    {
        $this->countryCurrencyAdapter = $countryCurrencyAdapter;
    }

    public function getCurrencies($defaultLocale, array $availableLocales)
    {
        $result = [];
        $currencyData = $this->getMainData($this->baseLocDir, 'currencies.json');
        $currencyMappings = array_flip($this->countryCurrencyAdapter->getCountryCurrencyMap());

        // collect main data ...
        foreach ($currencyData['main'][$this->baseLocDir]['numbers']['currencies'] as $code => $list)
        {
            if (isset($currencyMappings[$code]))
            {
                $result[$code] = new Currency($code);
                $result[$code]->addName($defaultLocale, $list['displayName']);
            }
        }

        // ... and fill up with translations
        foreach ($availableLocales as $loc)
        {
            if ($loc === $defaultLocale)
            {
                continue;
            }

            $locDir = $this->findLocDirForLocale($loc);
            if (! $locDir)
            {
                continue;
            }

            $locCurrencies = $this->getMainData($locDir, 'currencies.json');

            foreach ($locCurrencies['main'][$locDir]['numbers']['currencies'] as $locCode => $locs)
            {
                if (isset($result[$locCode]))
                {
                    $result[$locCode]->addName($loc, $locs['displayName']);
                }
            }
        }

        return $result;
    }
}
