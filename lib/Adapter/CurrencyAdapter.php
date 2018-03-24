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
        $currencyData = $this->getSupplementalData('currencyData.json');
        $currencies = $this->getMainData($this->baseLocDir, 'currencies.json');
        $currencyMappings = array_flip($this->countryCurrencyAdapter->getCountryCurrencyMap());

        // collect main data ...
        foreach ($currencies['main'][$this->baseLocDir]['numbers']['currencies'] as $code => $list)
        {
            $digits = 2;

            if (isset($currencyData['supplemental']['currencyData']['fractions'][$code]['_digits']))
            {
                $digits = (int)$currencyData['supplemental']['currencyData']['fractions'][$code]['_digits'];
            }

            if (isset($currencyMappings[$code]))
            {
                $symbol = $list['symbol-alt-narrow'] ?? $list['symbol'];
                $result[$code] = new Currency($code, $symbol, $digits);
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
