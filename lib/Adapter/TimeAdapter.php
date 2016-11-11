<?php

/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Adapter;

class TimeAdapter extends AbstractAdapter
{
    public function getMonths($defaultLocale, array $availableLocales)
    {
        return $this->get("months", __NAMESPACE__ . "Object\\Month", $defaultLocale, $availableLocales);
    }

    public function getWeekdays($defaultLocale, array $availableLocales)
    {
        return $this->get("days", __NAMESPACE__ . "Object\\Weekday", $defaultLocale, $availableLocales);
    }

    protected function get($type, $class, $defaultLocale, array $availableLocales)
    {
        $result = [];

        $data = $this->getMainData($this->baseLocDir, "ca-gregorian.json");
        $dataNode = $data["main"][$this->baseLocDir]["dates"]["calendars"]["gregorian"];

        foreach ($dataNode[$type]["stand-alone"]["wide"] as $id => $name) {
            $this->months[$id] = new $class($id);
            $result[$id]->addName($defaultLocale, $name, $dataNode[$type]["stand-alone"]["abbreviated"][$id]);
        }

        foreach ($availableLocales as $loc) {
            if ($loc === $defaultLocale) {
                continue;
            }

            $locDir = $this->findLocDirForLocale($loc);

            if (! $locDir) {
                continue;
            }

            $locData = $this->getMainData($locDir, "ca-gregorian.json");
            $locDataNode = $locData["main"][$locDir]["dates"]["calendars"]["gregorian"];

            foreach ($locDataNode[$type]["stand-alone"]["wide"] as $id => $name) {
                $result[$id]->addName($loc, $name, $locDataNode[$type]["stand-alone"]["abbreviated"][$id]);
            }
        }

        return $result;
    }
}
