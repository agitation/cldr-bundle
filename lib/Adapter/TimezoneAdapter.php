<?php
declare(strict_types=1);
/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Adapter;

use Agit\CldrBundle\Adapter\Object\Timezone;

class TimezoneAdapter extends AbstractAdapter
{
    protected $countryAdapter;

    public function __construct(CountryAdapter $countryAdapter)
    {
        $this->countryAdapter = $countryAdapter;
    }

    public function getTimezones($defaultLocale, array $availableLocales)
    {
        $result = [];
        $data = $this->getMainData($this->baseLocDir, 'timeZoneNames.json');

        $allTimezones = [];
        $supportedTimezones = array_flip(\DateTimeZone::listIdentifiers());

        foreach ($data['main'][$this->baseLocDir]['dates']['timeZoneNames']['zone'] as $continent => $list)
        {
            $allTimezones = array_merge($allTimezones, $this->getCodesFromSublist($continent, $list));
        }

        $countries = $this->countryAdapter->getCountries($defaultLocale, $availableLocales);

        foreach ($allTimezones as $tzName => $tzCity)
        {
            if (isset($supportedTimezones[$tzName]))
            {
                $dateTimezone = new \DateTimeZone($tzName);
                $locData = $dateTimezone->getLocation();

                if (is_array($locData) && isset($locData['country_code']) && isset($countries[$locData['country_code']]))
                {
                    $result[$tzName] = new Timezone($tzName, $countries[$locData['country_code']]);
                    $result[$tzName]->addName($defaultLocale, $this->makeName($tzCity, $result[$tzName]->getCountry(), $defaultLocale));
                }
            }
        }

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

            $locData = $this->getMainData($locDir, 'timeZoneNames.json');
            $locTimezones = [];

            foreach ($locData['main'][$locDir]['dates']['timeZoneNames']['zone'] as $locContinent => $locs)
            {
                $locTimezones = array_merge($locTimezones, $this->getCodesFromSublist($locContinent, $locs));
            }

            foreach ($locTimezones as $locTzName => $locTzCity)
            {
                if (isset($result[$locTzName]))
                {
                    $result[$locTzName]->addName($loc, $this->makeName($locTzCity, $result[$locTzName]->getCountry(), $loc));
                }
            }
        }

        return $result;
    }

    private function getCodesFromSublist($continent, $list)
    {
        $timezones = [];

        if (! in_array($continent, ['Etc', 'Arctic', 'Antarctica']))
        {
            foreach ($list as $city => $sublist)
            {
                if (is_array($sublist))
                {
                    if (isset($sublist['exemplarCity']))
                    {
                        $timezones["$continent/$city"] = $sublist['exemplarCity'];
                    }
                    else
                    {
                        // some timezones have three sections, for some reason

                        foreach ($sublist as $realCity => $realSublist)
                        {
                            if (isset($realSublist['exemplarCity']))
                            {
                                $timezones["$continent/$city/$realCity"] = $realSublist['exemplarCity'];
                            }
                        }
                    }
                }
            }
        }

        return $timezones;
    }

    private function makeName($name, $country, $locale)
    {
        return sprintf('%s, %s', $country->getName($locale), $name);
    }
}
