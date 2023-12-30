<?php

namespace PHPePub\Core\Structure\OPF;

/**
 * ePub OPF Guide structure
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2014- A. Grandt
 * @license   GNU LGPL 2.1
 */
class Guide
{
    private array $references = [];

    /**
     * Class destructor
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->references);
    }

    /**
     *
     * Enter description here ...
     *
     */
    public function length(): int
    {
        return count($this->references);
    }

    /**
     *
     * Enter description here ...
     *
     * @param Reference $reference
     */
    public function addReference($reference): void
    {
        if ($reference == null) {
            return;
        }

        if (!is_object($reference)) {
            return;
        }

        $this->references[] = $reference;
    }

    /**
     *
     * Enter description here ...
     */
    public function finalize(): string
    {
        $ref = "";
        if ($this->references !== []) {
            $ref = "\n\t<guide>\n";
            foreach ($this->references as $reference) {
                /** @var $reference Reference */
                $ref .= $reference->finalize();
            }

            $ref .= "\t</guide>\n";
        }

        return $ref;
    }
}
