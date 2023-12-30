<?php

namespace PHPePub\Core\Structure\OPF;

use PHPePub\Core\EPub;

/**
 * ePub OPF Item structure
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2014- A. Grandt
 * @license   GNU LGPL 2.1
 */
class Item
{
    private ?string $id = null;

    private ?string $href = null;

    private ?string $mediaType = null;

    private ?string $properties = null;

    private ?string $requiredNamespace = null;

    private ?string $requiredModules = null;

    private ?string $fallback = null;

    private ?string $fallbackStyle = null;

    private array $indexPoints = [];

    /**
     * Class constructor.
     *
     * @param      $id
     * @param      $href
     * @param      $mediaType
     * @param null $properties
     */
    public function __construct($id, $href, $mediaType, $properties = null)
    {
        $this->setId($id);
        $this->setHref($href);
        $this->setMediaType($mediaType);
        $this->setProperties($properties);
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $id
     */
    public function setId($id): void
    {
        $this->id = is_string($id) ? trim($id) : null;
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $href
     */
    public function setHref($href): void
    {
        $this->href = is_string($href) ? trim($href) : null;
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $mediaType
     */
    public function setMediaType($mediaType): void
    {
        $this->mediaType = is_string($mediaType) ? trim($mediaType) : null;
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $properties
     */
    public function setProperties($properties): void
    {
        $this->properties = is_string($properties) ? trim($properties) : null;
    }

    /**
     * Class destructor
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->id, $this->href, $this->mediaType);
        unset($this->properties, $this->requiredNamespace, $this->requiredModules, $this->fallback, $this->fallbackStyle);
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $requiredNamespace
     */
    public function setRequiredNamespace($requiredNamespace): void
    {
        $this->requiredNamespace = is_string($requiredNamespace) ? trim($requiredNamespace) : null;
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $requiredModules
     */
    public function setRequiredModules($requiredModules): void
    {
        $this->requiredModules = is_string($requiredModules) ? trim($requiredModules) : null;
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $fallback
     */
    public function setfallback($fallback): void
    {
        $this->fallback = is_string($fallback) ? trim($fallback) : null;
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $fallbackStyle
     */
    public function setFallbackStyle($fallbackStyle): void
    {
        $this->fallbackStyle = is_string($fallbackStyle) ? trim($fallbackStyle) : null;
    }

    /**
     *
     * @param string $bookVersion
     */
    public function finalize($bookVersion = EPub::BOOK_VERSION_EPUB2): string
    {
        $item = "\t\t<item id=\"" . $this->id . '" href="' . $this->href . '" media-type="' . $this->mediaType . '" ';
        if ($bookVersion === EPub::BOOK_VERSION_EPUB3 && $this->properties !== null) {
            $item .= 'properties="' . $this->properties . '" ';
        }

        if ($this->requiredNamespace !== null) {
            $item .= "\n\t\t\trequired-namespace=\"" . $this->requiredNamespace . '" ';
            if ($this->requiredModules !== null) {
                $item .= 'required-modules="' . $this->requiredModules . '" ';
            }
        }

        if ($this->fallback !== null) {
            $item .= "\n\t\t\tfallback=\"" . $this->fallback . '" ';
        }

        if ($this->fallbackStyle !== null) {
            $item .= "\n\t\t\tfallback-style=\"" . $this->fallbackStyle . '" ';
        }

        return $item . "/>\n";
    }

    public function getIndexPoints(): array
    {
        return $this->indexPoints;
    }

    /**
     * @param string $indexPoint
     */
    public function addIndexPoint($indexPoint): void
    {
        $this->indexPoints[] = $indexPoint;
    }

    /**
     * @param string $indexPoint
     */
    public function hasIndexPoint($indexPoint): bool
    {
        return in_array($indexPoint, $this->indexPoints);
    }


    public function getId(): ?string
    {
        return $this->id;
    }


    public function getHref(): ?string
    {
        return $this->href;
    }
}
