<?php

namespace PHPePub\Core\Structure\NCX;

use PHPePub\Core\EPub;

/**
 * ePub NavPoint class
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2014- A. Grandt
 * @license   GNU LGPL 2.1
 */
class NavPoint extends AbstractNavEntry
{
    final public const _VERSION = 3.30;

    private ?string $label = null;
    private ?string $contentSrc = null;
    private ?string $id = null;
    private ?string $navClass = null;
    private bool $isNavHidden = false;
    private array $navPoints = [];
    /** @var $parent AbstractNavEntry */
    private ?\PHPePub\Core\Structure\NCX\AbstractNavEntry $parent = null;
    private ?string $writingDirection = EPub::DIRECTION_LEFT_TO_RIGHT;

    /**
     * Class constructor.
     *
     * All three attributes are mandatory, though if ID is set to null (default) the value will be generated.
     *
     * @param string $label
     * @param string $contentSrc
     * @param string $id
     * @param string $navClass
     * @param bool   $isNavHidden
     * @param string $writingDirection
     */
    public function __construct($label, $contentSrc = null, $id = null, $navClass = null, $isNavHidden = false, $writingDirection = null)
    {
        $this->setLabel($label);
        $this->setContentSrc($contentSrc);
        $this->setId($id);
        $this->setNavClass($navClass);
        $this->setNavHidden($isNavHidden);
        $this->setWritingDirection($writingDirection);
    }

    /**
     * Set the id for the NavPoint.
     *
     * The id must be unique, and is mandatory.
     *
     * @param string $id
     */
    public function setId($id): void
    {
        $this->id = is_string($id) ? trim($id) : null;
    }

    /**
     * Set the class to be used for this NavPoint.
     *
     * @param string $navClass
     */
    public function setNavClass($navClass): void
    {
        $this->navClass = isset($navClass) && is_string($navClass) ? trim($navClass) : null;
    }

    /**
     * Set the class to be used for this NavPoint.
     *
     * @param $isNavHidden
     */
    public function setNavHidden($isNavHidden): void
    {
        $this->isNavHidden = $isNavHidden === true;
    }

    /**
     * Class destructor
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->label, $this->contentSrc, $this->id, $this->navClass);
        unset($this->isNavHidden, $this->navPoints, $this->parent);
    }

    /**
     * Get the Text label for the NavPoint.
     *
     * @return string Label
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Set the Text label for the NavPoint.
     *
     * The label is mandatory.
     *
     * @param string $label
     */
    public function setLabel($label): void
    {
        $this->label = is_string($label) ? trim($label) : null;
    }

    /**
     * Get the src reference for the NavPoint.
     *
     * @return string content src url.
     */
    public function getContentSrc(): ?string
    {
        return $this->contentSrc;
    }

    /**
     * Set the src reference for the NavPoint.
     *
     * The src is mandatory for ePub 2.
     *
     * @param string $contentSrc
     */
    public function setContentSrc($contentSrc): void
    {
        $this->contentSrc = isset($contentSrc) && is_string($contentSrc) ? trim($contentSrc) : null;
    }

    /**
     * Get the parent to this NavPoint.
     *
     * @return AbstractNavEntry if the parent is the root.
     */
    public function getParent(): ?\PHPePub\Core\Structure\NCX\AbstractNavEntry
    {
        return $this->parent;
    }

    /**
     * Set the parent for this NavPoint.
     *
     * @param NavPoint|NavMap $parent
     */
    public function setParent($parent): void
    {
        if ($parent == null) {
            return;
        }
        if (!is_object($parent)) {
            return;
        }
        $this->parent = $parent;
    }

    /**
     * Get the current level. 1 = document root.
     *
     * @return int level
     */
    public function getLevel(): int|float
    {
        return $this->parent instanceof \PHPePub\Core\Structure\NCX\AbstractNavEntry ? $this->parent->getLevel() + 1 : 1;
    }

    /**
     * Add child NavPoints for multi level NavMaps.
     *
     * @param $navPoint
     *
     * @return $this
     */
    public function addNavPoint($navPoint): static
    {
        if ($navPoint != null && is_object($navPoint) && $navPoint instanceof NavPoint) {
            /** @var $navPoint NavPoint */
            $navPoint->setParent($this);
            if ($navPoint->getWritingDirection() == null) {
                $navPoint->setWritingDirection($this->writingDirection);
            }
            $this->navPoints[] = $navPoint;

            return $navPoint;
        }

        return $this;
    }

    public function getWritingDirection(): ?string
    {
        return $this->writingDirection;
    }

    /**
     * Set the writing direction to be used for this NavPoint.
     *
     * @param string $writingDirection
     */
    public function setWritingDirection($writingDirection): void
    {
        $this->writingDirection = isset($writingDirection) && is_string($writingDirection) ? trim($writingDirection) : null;
    }

    /**
     *
     * Enter description here ...
     *
     * @param int    $playOrder
     * @param int    $level
     * @return int
     */
    public function finalize(string &$nav = "", &$playOrder = 0, $level = 0)
    {
        $maxLevel = $level;
        $levelAdjust = 0;

        if ($this->isNavHidden) {
            return $maxLevel;
        }

        if ($this->contentSrc !== null) {
            $playOrder++;

            if ($this->id == null) {
                $this->id = "navpoint-" . $playOrder;
            }
            $nav .= str_repeat("\t", $level) . "\t\t<navPoint id=\"" . $this->id . "\" playOrder=\"" . $playOrder . "\">\n"
                . str_repeat("\t", $level) . "\t\t\t<navLabel>\n"
                . str_repeat("\t", $level) . "\t\t\t\t<text>" . $this->label . "</text>\n"
                . str_repeat("\t", $level) . "\t\t\t</navLabel>\n"
                . str_repeat("\t", $level) . "\t\t\t<content src=\"" . $this->contentSrc . "\" />\n";
        } else {
            $levelAdjust++;
        }

        if ($this->navPoints !== []) {
            $maxLevel++;
            foreach ($this->navPoints as $navPoint) {
                /** @var $navPoint NavPoint */
                $retLevel = $navPoint->finalize($nav, $playOrder, ($level + 1 + $levelAdjust));
                if ($retLevel > $maxLevel) {
                    $maxLevel = $retLevel;
                }
            }
        }

        if ($this->contentSrc !== null) {
            $nav .= str_repeat("\t", $level) . "\t\t</navPoint>\n";
        }

        return $maxLevel;
    }

    /**
     *
     * Enter description here ...
     *
     * @param int    $playOrder
     * @param int    $level
     * @param null   $subLevelClass
     * @param bool   $subLevelHidden
     * @return int
     */
    public function finalizeEPub3(string &$nav = "", &$playOrder = 0, $level = 0, $subLevelClass = null, $subLevelHidden = false)
    {
        $maxLevel = $level;

        if ($this->id == null) {
            $this->id = "navpoint-" . $playOrder;
        }

        $dir = "";
        if ($this->writingDirection !== null) {
            $dir .= " dir=\"" . $this->writingDirection . "\"";
        }
        $indent = str_repeat("\t", $level) . "\t\t\t\t";

        $nav .= $indent . "<li id=\"" . $this->id . "\"" . $dir . ">\n";

        if ($this->contentSrc !== null) {
            $nav .= $indent . "\t<a href=\"" . $this->contentSrc . "\"" . $dir . ">" . $this->label . "</a>\n";
        } else {
            $nav .= $indent . "\t<span" . $dir . ">" . $this->label . "</span>\n";
        }

        if ($this->navPoints !== []) {
            $maxLevel++;

            $nav .= $indent . "\t<ol epub:type=\"list\"" . $dir;
            if (isset($subLevelClass)) {
                $nav .= " class=\"" . $subLevelClass . "\"";
            }
            if ($subLevelHidden) {
                $nav .= " hidden=\"hidden\"";
            }
            $nav .= ">\n";

            foreach ($this->navPoints as $navPoint) {
                /** @var $navPoint NavPoint */
                $retLevel = $navPoint->finalizeEPub3($nav, $playOrder, ($level + 2), $subLevelClass, $subLevelHidden);
                if ($retLevel > $maxLevel) {
                    $maxLevel = $retLevel;
                }
            }
            $nav .= $indent . "\t</ol>\n";
        }

        $nav .= $indent . "</li>\n";

        return $maxLevel;
    }
}
