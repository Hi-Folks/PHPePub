<?php

namespace PHPePub\Core\Structure\OPF;

use PHPePub\Core\EPub;

/**
 * ePub OPF Manifest structure
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2014- A. Grandt
 * @license   GNU LGPL 2.1
 */
class Manifest
{
    private array $items = [];

    /**
     * Class destructor
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->items);
    }

    /**
     *
     * Enter description here ...
     *
     * @param Item $item
     */
    public function addItem($item): void
    {
        if ($item == null) {
            return;
        }

        if (!is_object($item)) {
            return;
        }

        $this->items[] = $item;
    }

    /**
     *
     * @param string $bookVersion
     */
    public function finalize($bookVersion = EPub::BOOK_VERSION_EPUB2): string
    {
        $manifest = "\n\t<manifest>\n";
        foreach ($this->items as $item) {
            /** @var $item Item */
            $manifest .= $item->finalize($bookVersion);
        }

        return $manifest . "\t</manifest>\n";
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
