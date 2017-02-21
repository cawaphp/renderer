<?php

/*
 * This file is part of the Сáша framework.
 *
 * (c) tchiotludo <http://github.com/tchiotludo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types = 1);

namespace Cawa\Renderer;

use Cawa\Core\DI;
use Cawa\Net\Uri;

trait AssetTrait
{
    /**
     * @param string $path
     *
     * @return array<Uri, bool>
     */
    protected function getAssetData(string $path) : array
    {
        $return = [null, DI::config()->exists('assets/hashes')];

        // file hash
        $hashes = DI::config()->getIfExists('assets/hashes');
        if ($hashes) {
            if (isset($hashes[$path])) {
                $path = $hashes[$path];
            }
        }

        // relative path like "vendor.js", add assets/url
        if (substr($path, 0, 4) != 'http' && // remove "http//host/vendor.js"
            substr($path, 0, 1) != '/' && // remove "/vendor.js" & "//host/vendor.js"
            $assetsPath = DI::config()->get('assets/url')) {
            $path = rtrim($assetsPath, '/') . '/' . $path;
        }

        $host = DI::config()->getIfExists('assets/host') ?: '';
        $return[0] = (new Uri(null, [Uri::OPTIONS_RELATIVE => empty($host)]))
            ->setPath($path)
            ->setQueries([])
            ->setFragment();

        if ($host) {
            $return[0]->setHost($host);
        }

        return $return;
    }

    /**
     * @param string $path
     *
     * @return Uri
     */
    public function asset(string $path) : Uri
    {
        list($path) = $this->getAssetData($path);

        return $path;
    }
}
