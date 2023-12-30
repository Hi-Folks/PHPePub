<?php

namespace PHPePub\Core\Structure\OPF;

use PHPePub\Core\EPub;
use PHPePub\Core\StaticData;

/**
 * ePub OPF Metadata structures
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2014- A. Grandt
 * @license   GNU LGPL 2.1
 */
class Metadata
{
    private array $dc = [];
    private array $meta = [];
    private array $metaProperties = [];
    public $namespaces = [];

    /**
     * Class destructor
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->dc, $this->meta);
    }

    /**
     *
     * Enter description here ...
     *
     * @param MetaValue $dc
     */
    public function addDublinCore($dc): void
    {
        if ($dc == null) {
            return;
        }
        if (!is_object($dc)) {
            return;
        }
        $this->dc[] = $dc;
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $name
     * @param string $content
     */
    public function addMeta($name, $content): void
    {
        $name = is_string($name) ? trim($name) : null;
        if (isset($name)) {
            $content = is_string($content) ? trim($content) : null;
        }
        if (isset($content)) {
            $this->meta[] = [$name => $content];
        }
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $name
     * @param string $content
     */
    public function addMetaProperty($name, $content): void
    {
        $name = is_string($name) ? trim($name) : null;
        if (isset($name)) {
            $content = is_string($content) ? trim($content) : null;
        }
        if (isset($content)) {
            $this->metaProperties[] = [$name => $content];
        }
    }

    /**
     * @param string $nsName
     * @param string $nsURI
     */
    public function addNamespace($nsName, $nsURI): void
    {
        if (!array_key_exists($nsName, $this->namespaces)) {
            $this->namespaces[$nsName] = $nsURI;
        }
    }

    /**
     *
     * @param string $bookVersion
     * @param int    $date
     */
    public function finalize($bookVersion = EPub::BOOK_VERSION_EPUB2, $date = null): string
    {
        if ($bookVersion === EPub::BOOK_VERSION_EPUB2) {
            $this->addNamespace("opf", StaticData::$namespaces["opf"]);
        } else {
            if (!isset($date)) {
                $date = time();
            }
            $this->addNamespace("dcterms", StaticData::$namespaces["dcterms"]);
            $this->addMetaProperty("dcterms:modified", gmdate('Y-m-d\TH:i:s\Z', $date));
        }

        if ($this->dc !== []) {
            $this->addNamespace("dc", StaticData::$namespaces["dc"]);
        }

        $metadata = "\t<metadata>\n";

        foreach ($this->dc as $dc) {
            /** @var $dc MetaValue */
            $metadata .= $dc->finalize($bookVersion);
        }

        foreach ($this->metaProperties as $data) {
            $content = current($data);
            $name = key($data);
            $metadata .= "\t\t<meta property=\"" . $name . "\">" . $content . "</meta>\n";
        }

        foreach ($this->meta as $data) {
            $content = current($data);
            $name = key($data);
            $metadata .= "\t\t<meta name=\"" . $name . "\" content=\"" . $content . "\" />\n";
        }

        return $metadata . "\t</metadata>\n";
    }
}
