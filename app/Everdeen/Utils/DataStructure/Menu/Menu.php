<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-06
 * Time: 20:23
 */

namespace Katniss\Everdeen\Utils\DataStructure\Menu;


class Menu
{
    protected $data;

    protected $currentIndex;

    protected $matchingUrl;
    protected $strict;

    public function __construct($matchingUrl = null, $strict = false)
    {
        $this->matchingUrl = $matchingUrl;
        $this->strict = $strict;
        $this->reset();
    }

    public function reset()
    {
        $this->data = [];
        $this->currentIndex = -1;
    }

    public function has()
    {
        return count($this->data) > 0;
    }

    public function &get()
    {
        return $this->data;
    }

    public function append(Menu $menu)
    {
        foreach ($menu->get() as $item) {
            $this->data[] = $item;
        }
    }

    public function prepend(Menu $menu)
    {
        $data = $menu->get();
        for ($i = count($data) - 1; $i >= 0; --$i) {
            array_unshift($this->data, $data[$i]);
        }
    }

    protected function matchUrl($url)
    {
        if (!beginsWith($url, 'http') || empty($this->matchingUrl)) return false;

        if ($this->strict) {
            return $url == $this->matchingUrl;
        }

        if ($url == $this->matchingUrl
            || (notRootUrl($url) && beginsWith($this->matchingUrl, $url))
        ) {
            return true;
        }

        $url = parse_url($url);
        $matchingUrl = parse_url($this->matchingUrl);
        if (!empty($url['query']) && !empty($matchingUrl['query'])) {
            parse_str($url['query'], $urlQuery);
            parse_str($matchingUrl['query'], $matchingUrlQuery);
            $matching = false;
            foreach ($urlQuery as $key => $value) {
                if (isset($matchingUrlQuery[$key])
                    && (beginsWith($matchingUrlQuery[$key], $value) || beginsWith($value, $matchingUrlQuery[$key]))
                ) {
                    $matching = true;
                    break;
                }
            }
            if (!$matching) return false;
        }

        return $url['host'] == $matchingUrl['host']
            && $url['path'] == $matchingUrl['path'];
    }

    public function add($url, $name, $before = '', $after = '', $itemClass = '', $linkClass = '', $itemId = '', $title = '', $matchingCallback = null)
    {
        $this->data[] = [
            'item' => [
                'title' => empty($title) ? $name : $title,
                'url' => $url,
                'name' => $name,
                'before' => $before,
                'after' => $after,
                'item_class' => $itemClass,
                'link_class' => $linkClass,
                'item_id' => $itemId,
                'active' => is_bool($matchingCallback) ?
                    $matchingCallback : (empty($matchingCallback) ?
                        $this->matchUrl($url) : call_user_func($matchingCallback, $url)),
            ],
        ];
        $this->currentIndex = count($this->data) - 1;
    }

    public function setTargetBlank()
    {
        $this->data[$this->currentIndex]['item']['blank'] = true;
    }

    public function addSubMenu(Menu $menu)
    {
        $this->data[$this->currentIndex]['children'] = $menu->get();
    }
}