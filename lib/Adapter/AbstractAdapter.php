<?php

/*
 * @package    agitation/cldr-bundle
 * @link       http://github.com/agitation/cldr-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CldrBundle\Adapter;

use Agit\BaseBundle\Exception\InternalErrorException;

abstract class AbstractAdapter
{
    protected $cldrDir;

    protected $baseLocDir = "en-GB";

    // cache for raw retrieved data
    protected $dataCache = [];

    public function setCldrDir($cldrDir)
    {
        $this->cldrDir = realpath(__DIR__ . "/../$cldrDir");
    }

    protected function getMainData($locDir, $filename)
    {
        $path = sprintf("%s/main/%s/%s", $this->cldrDir, $locDir ?: $this->baseLocDir, $filename);

        return $this->getData($path);
    }

    protected function findLocDirForLocale($locale)
    {
        $locDir = null;
        $variants = [str_replace("_", "-", $locale), substr($locale, 0, 2)];

        foreach ($variants as $variant) {
            $path = sprintf("%s/main/%s", $this->cldrDir, $variant);

            if (is_dir($path)) {
                $locDir = $variant;
                break;
            }
        }

        return $locDir;
    }

    protected function getSupplementalData($filename)
    {
        $path = sprintf("%s/supplemental/%s", $this->cldrDir, $filename);

        return $this->getData($path);
    }

    private function getData($path)
    {
        if (! isset($this->dataCache[$path])) {
            if (! is_readable($path)) {
                throw new InternalErrorException("Cannot read `$path`.");
            }

            $this->dataCache[$path] = json_decode(file_get_contents($path), true);
        }

        return $this->dataCache[$path];
    }
}
