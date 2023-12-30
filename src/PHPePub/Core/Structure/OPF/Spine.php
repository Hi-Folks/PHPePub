<?php

namespace PHPePub\Core\Structure\OPF;

/**
 * ePub OPF Spine structure
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2014- A. Grandt
 * @license   GNU LGPL 2.1
 */
class Spine
{
    private array $itemrefs = [];
    private ?string $toc = null;

    /**
     * Class constructor.
     *
     * @param string $toc
     */
    public function __construct($toc = "ncx")
    {
        $this->setToc($toc);
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $toc
     */
    public function setToc($toc): void
    {
        $this->toc = is_string($toc) ? trim($toc) : null;
    }

    /**
     * Class destructor
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->itemrefs, $this->toc);
    }

    /**
     *
     * Enter description here ...
     *
     * @param Itemref $itemref
     */
    public function addItemref($itemref): void
    {
        if ($itemref == null) {
            return;
        }
        if (!is_object($itemref)) {
            return;
        }
        if (isset($this->itemrefs[$itemref->getIdref()])) {
            return;
        }
        $this->itemrefs[$itemref->getIdref()] = $itemref;
    }

    /**
     *
     * Enter description here ...
     */
    public function finalize(): string
    {
        $spine = "\n\t<spine toc=\"" . $this->toc . "\">\n";
        foreach ($this->itemrefs as $itemref) {
            /** @var $itemref ItemRef */
            $spine .= $itemref->finalize();
        }

        return $spine . "\t</spine>\n";
    }
}
