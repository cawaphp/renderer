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

class WidgetOption extends HtmlElement
{
    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct('<script>');
        $this->addAttribute('type', 'application/json');

        $this->data = $data;
    }

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @return array
     */
    public function getData() : array
    {
        return $this->data;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param bool $push
     *
     * @return $this|self
     */
    public function addData(string $key, $value, bool $push = false) : self
    {
        if ($push == false && isset($this->data[$key]) && is_array($value)) {
            $this->data[$key] = array_replace_recursive(
                is_array($this->data[$key]) ? $this->data[$key] : [$this->data[$key]],
                $value
            );
        } else if ($push) {
            if (!isset($this->data[$key])) {
                $this->data[$key] = [];
            }

            $this->data[$key][] = $value;
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this|self
     */
    public function setData(array $data) : self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return sizeof($this->data);
    }

    /**
     * @return string
     */
    public function render()
    {
        $this->setContent(json_encode($this->data));

        return parent::render();
    }
}
