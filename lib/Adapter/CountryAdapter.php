<?php
declare(strict_types=1);
/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Adapter;

use Agit\CldrBundle\Adapter\Object\Country;

class CountryAdapter extends AbstractAdapter
{
    protected $currencyAdapter;

    protected $countryCurrencyAdapter;

    public function __construct(CurrencyAdapter $currencyAdapter, CountryCurrencyAdapter $countryCurrencyAdapter)
    {
        $this->currencyAdapter = $currencyAdapter;
        $this->countryCurrencyAdapter = $countryCurrencyAdapter;
    }

    public function getCountries($defaultLocale, array $availableLocales)
    {
        $result = [];

        $countries = $this->getMainData($this->baseLocDir, 'territories.json');
        $codeMappings = $this->getSupplementalData('codeMappings.json');
        $phoneCodes = $this->getSupplementalData('telephoneCodeData.json');
        $currencies = $this->currencyAdapter->getCurrencies($defaultLocale, $availableLocales);
        $currencyMappings = $this->countryCurrencyAdapter->getCountryCurrencyMap();

        // collect main data ...
        foreach ($countries['main'][$this->baseLocDir]['localeDisplayNames']['territories'] as $code => $name)
        {
            if (
                strlen((string)$code) === 2 &&
                ! is_numeric($code) &&
                $code !== 'ZZ' &&
                isset($currencyMappings[$code]) &&
                isset($currencies[$currencyMappings[$code]]) &&

                isset($codeMappings['supplemental']['codeMappings'][$code]) &&
                isset($codeMappings['supplemental']['codeMappings'][$code]['_alpha3']) &&

                isset($phoneCodes['supplemental']['telephoneCodeData'][$code]) &&
                isset($phoneCodes['supplemental']['telephoneCodeData'][$code][0]) &&
                isset($phoneCodes['supplemental']['telephoneCodeData'][$code][0]['telephoneCountryCode'])
            ) {
                $result[$code] = new Country(
                    $code,
                    $codeMappings['supplemental']['codeMappings'][$code]['_alpha3'],
                    $currencies[$currencyMappings[$code]],
                    $phoneCodes['supplemental']['telephoneCodeData'][$code][0]['telephoneCountryCode']
                );

                $result[$code]->addName($defaultLocale, $name);
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

            $locCountries = $this->getMainData($locDir, 'territories.json');

            foreach ($locCountries['main'][$locDir]['localeDisplayNames']['territories'] as $locCode => $locName)
            {
                if (isset($result[$locCode]))
                {
                    $result[$locCode]->addName($loc, $locName);
                }
            }
        }

        return $result;
    }
}
