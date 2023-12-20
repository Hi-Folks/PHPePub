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
    private $references = [];

    /**
     * Class constructor.
     */
    public function __construct() {}

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
    public function length()
    {
        return count($this->references);
    }

    /**
     *
     * Enter description here ...
     *
     * @param Reference $reference
     */
    public function addReference($reference)
    {
        if ($reference != null && is_object($reference) && $reference instanceof Reference) {
            $this->references[] = $reference;
        }
    }

    /**
     *
     * Enter description here ...
     *
     * @return string
     */
    public function finalize()
    {
        $ref = "";
        if (count($this->references) > 0) {
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
