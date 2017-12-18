<?php

/*
 * This file is part of the Сáша framework.
 *
 * (c) tchiotludo <http://github.com/tchiotludo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Cawa\Renderer;

use Cawa\Cache\CacheFactory;

trait CacheTrait
{
    use CacheFactory;

    /**
     * @var array
     */
    private $renderCacheData;

    /**
     * @param string $key
     * @param int $tll
     * @param array $tags
     *
     * @return bool
     */
    private function renderCacheGet(string $key, int $tll = null, array $tags = []) : bool
    {
        $key = self::class . ':' . $key;

        $this->renderCacheData = [
            'key' => $key,
            'cache' => self::cache(self::class)->get($key),
            'ttl' => $tll,
            'tags' => $tags,

        ];

        return $this->renderCacheData['cache'] === false ? false : true;
    }

    /**
     * @param string $data
     *
     * @return string
     */
    private function renderCacheSet(string $data) {
        self::cache(self::class)->set(
            $this->renderCacheData['key'],
            $data,
            $this->renderCacheData['ttl'],
            $this->renderCacheData['tags']
        );

        return $data;
    }

    /**
     * @return string
     */
    public function render()
    {
        if ($this->renderCacheData['cache']) {
            return $this->renderCacheData['cache'];
        }

        return $this->renderCacheSet(parent::render());
    }
}
