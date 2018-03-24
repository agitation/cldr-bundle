<?php
declare(strict_types=1);

/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Adapter;

class CountryCurrencyAdapter extends AbstractAdapter
{
    // manually blacklisted
    private $currencyBlacklist = ['USN'];
    private $countryBlacklist = ['ZZ'];

    public function getCountryCurrencyMap()
    {
        $currencyMappings = $this->getSupplementalData('currencyData.json');
        $results = [];

        foreach ($currencyMappings['supplemental']['currencyData']['region'] as $country => $list)
        {
            if (strlen((string)$country) !== 2 || is_numeric($country) || in_array($country, $this->countryBlacklist))
            {
                continue;
            }

            foreach ($list as $sublist)
            {
                foreach ($sublist as $currencyCode => $details)
                {
                    if (! isset($details['_to']) && (! isset($details['_tender']) || $details['_tender'] === 'true') && ! in_array($currencyCode, $this->currencyBlacklist))
                    {
                        $results[$country] = $currencyCode;

                        continue 3;
                    }
                }
            }
        }

        return $results;
    }
}
