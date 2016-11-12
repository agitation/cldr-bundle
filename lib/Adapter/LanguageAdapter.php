<?php

/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Adapter;

use Agit\CldrBundle\Adapter\Object\Language;

class LanguageAdapter extends AbstractAdapter
{
    protected $countryAdapter;

    protected $languageCountryMap = [];

    public function __construct(CountryAdapter $countryAdapter)
    {
        $this->countryAdapter = $countryAdapter;
    }

    public function getLanguages($defaultLocale, array $availableLocales)
    {
        $result = [];
        $territories = $this->getSupplementalData("territoryInfo.json");

        $countries = [];

        foreach ($this->countryAdapter->getCountries($defaultLocale, $availableLocales) as $country) {
            $countries[$country->getCode()] = $country;
        }

        foreach ($territories["supplemental"]["territoryInfo"] as $countryCode => $data) {
            if (strlen($countryCode) !== 2 || is_numeric($countryCode) || $countryCode === "ZZ" || ! isset($data["languagePopulation"])) {
                continue;
            }

            foreach ($data["languagePopulation"] as $langCode => $langData) {
                if (
                    strlen($langCode) !== 2 ||
                    ! isset($langData["_officialStatus"]) ||
                    ! isset($langData["_populationPercent"]) ||
                    $langData["_officialStatus"] !== "official" ||
                    $langData["_populationPercent"] < 1 ||
                    ! isset($countries[$countryCode])
                ) {
                    continue;
                }

                $localeDir = $this->findLocDirForLocale($langCode);
                if (! $localeDir) {
                    $localeDir = $this->findLocDirForLocale($defaultLocale);
                }

                $languages = $this->getMainData($localeDir, "languages.json");

                if (! isset($languages["main"][$localeDir]["localeDisplayNames"]["languages"][$langCode])) {
                    continue;
                }

                $localName = $languages["main"][$localeDir]["localeDisplayNames"]["languages"][$langCode];

                $result[$langCode] = new Language($langCode, $localName);

                if (! isset($this->languageCountryMap[$langCode])) {
                    $this->languageCountryMap[$langCode] = [];
                }

                $this->languageCountryMap[$langCode][$countryCode] = $countryCode;
            }
        }

        // ... and fill up with translations
        foreach ($availableLocales as $locale) {
            $localeDir = $this->findLocDirForLocale($locale);
            if (! $localeDir) {
                continue;
            }

            $languages = $this->getMainData($localeDir, "languages.json");

            foreach ($result as $langCode => &$language) {
                $language->addName($locale, $languages["main"][$localeDir]["localeDisplayNames"]["languages"][$langCode]);
            }
        }

        // add countries for languages
        foreach ($result as $langCode => &$language) {
            // dead language?
            if (! isset($this->languageCountryMap[$langCode])) {
                unset($result[$langCode]);
                continue;
            }

            foreach ($this->languageCountryMap[$langCode] as $countryCode) {
                $language->addCountry($countries[$countryCode]);
            }
        }

        return $result;
    }
}
