<?php

namespace PHPePub\Core\Structure\NCX;

/**
 * ePub NavMap class
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2014- A. Grandt
 * @license   GNU LGPL 2.1
 */
class NavMap extends AbstractNavEntry
{
    final public const _VERSION = 3.30;

    private array $navPoints = [];

    private int $navLevels = 0;

    private ?string $writingDirection = null;

    /**
     * Class constructor.
     *
     * @param string $writingDirection
     */
    public function __construct($writingDirection = null)
    {
        $this->setWritingDirection($writingDirection);
    }

    /**
     * Class destructor
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->navPoints, $this->navLevels, $this->writingDirection);
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
     * Add a navPoint to the root of the NavMap.
     *
     * @param NavPoint $navPoint
     *
     * @return NavMap
     */
    public function addNavPoint($navPoint): \PHPePub\Core\Structure\NCX\NavPoint|static
    {
        if ($navPoint != null && is_object($navPoint) && $navPoint instanceof NavPoint) {
            $navPoint->setParent($this);
            if ($navPoint->getWritingDirection() == null) {
                $navPoint->setWritingDirection($this->writingDirection);
            }

            $this->navPoints[] = $navPoint;

            return $navPoint;
        }

        return $this;
    }

    /**
     * The final max depth for the "dtb:depth" meta attribute
     * Only available after finalize have been called.
     *
     * @return number
     */
    public function getNavLevels(): int|float
    {
        return $this->navLevels + 1;
    }

    public function getLevel(): int
    {
        return 1;
    }

    /**
     * @return AbstractNavEntry this
     */
    public function getParent(): static
    {
        return $this;
    }

    /**
     * Finalize the navMap, the final max depth for the "dtb:depth" meta attribute can be retrieved with getNavLevels after finalization
     *
     */
    public function finalize(): string
    {
        $playOrder = 0;
        $this->navLevels = 0;

        $nav = "\t<navMap>\n";
        if ($this->navPoints !== []) {
            ++$this->navLevels;
            foreach ($this->navPoints as $navPoint) {
                /** @var $navPoint NavPoint */
                $retLevel = $navPoint->finalize($nav, $playOrder, 0);
                if ($retLevel > $this->navLevels) {
                    $this->navLevels = $retLevel;
                }
            }
        }

        return $nav . "\t</navMap>\n";
    }

    /**
     * Finalize the navMap, the final max depth for the "dtb:depth" meta attribute can be retrieved with getNavLevels after finalization
     *
     */
    public function finalizeEPub3(): string
    {
        $playOrder = 0;
        $level = 0;
        $this->navLevels = 0;

        $nav = "\t\t<nav epub:type=\"toc\" id=\"toc\">\n";

        if ($this->navPoints !== []) {
            ++$this->navLevels;

            $nav .= str_repeat("\t", $level) . "\t\t\t<ol epub:type=\"list\">\n";
            foreach ($this->navPoints as $navPoint) {
                /** @var $navPoint NavPoint */
                $retLevel = $navPoint->finalizeEPub3($nav, $playOrder, 0);
                if ($retLevel > $this->navLevels) {
                    $this->navLevels = $retLevel;
                }
            }

            $nav .= str_repeat("\t", $level) . "\t\t\t</ol>\n";
        }

        return $nav . "\t\t</nav>\n";
    }
}
