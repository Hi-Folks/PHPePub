<?php
namespace PHPePub\Helpers\Rendition;

use PHPePub\Core\EPub;

/**
 * Helper for Rendition ePub3 extensions.
 *
 *   http://www.idpf.org/epub/fxl/#property-defs
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2015- A. Grandt
 * @license   GNU LGPL 2.1
 */
class RenditionHelper {

    final public const RENDITION_PREFIX_NAME = "rendition";
    final public const RENDITION_PREFIX_URI = "http://www.idpf.org/vocab/rendition/#";

    final public const RENDITION_LAYOUT = "rendition:layout";
    final public const RENDITION_ORIENTATION = "rendition:orientation";
    final public const RENDITION_SPREAD = "rendition:spread";

    final public const LAYOUT_REFLOWABLE = "reflowable";
    final public const LAYOUT_PRE_PAGINATED = "pre-paginated";

    final public const ORIENTATION_LANDSCAPE = "landscape";
    final public const ORIENTATION_PORTRAIT = "portrait";
    final public const ORIENTATION_AUTO = "auto";

    final public const SPREAD_NONE = "none";
    final public const SPREAD_LANDSCAPE = "landscape";
    final public const SPREAD_PORTRAIT = "portrait";
    final public const SPREAD_BOTH = "both";
    final public const SPREAD_AUTO = "auto";

    /**
     * Add iBooks prefix to the ePub book
     *
     * @param EPub $book
     */
    public static function addPrefix($book) {
        if (!$book->isEPubVersion2()) {
            $book->addCustomPrefix(self::RENDITION_PREFIX_NAME, self::RENDITION_PREFIX_URI);
        }
    }

    /**
     * @param EPub   $book
     * @param string $value "reflowable", "pre-paginated"
     */
    public static function setLayout($book, $value) {
        if (!$book->isEPubVersion2() && $value === self::LAYOUT_REFLOWABLE || $value === self::LAYOUT_PRE_PAGINATED) {
            $book->addCustomMetaProperty(self::RENDITION_LAYOUT, $value);
        }
    }

    /**
     * @param EPub   $book
     * @param string $value "landscape", "portrait" or "auto"
     */
    public static function setOrientation($book, $value) {
        if (!$book->isEPubVersion2() && $value === self::ORIENTATION_LANDSCAPE || $value === self::ORIENTATION_PORTRAIT || $value === self::ORIENTATION_AUTO) {
            $book->addCustomMetaProperty(self::RENDITION_ORIENTATION, $value);
        }
    }

    /**
     * @param EPub   $book
     * @param string $value "landscape", "portrait" or "auto"
     */
    public static function setSpread($book, $value) {
        if (!$book->isEPubVersion2() && $value === self::SPREAD_NONE || $value === self::SPREAD_LANDSCAPE || $value === self::SPREAD_PORTRAIT || $value === self::SPREAD_BOTH || $value === self::SPREAD_AUTO) {
            $book->addCustomMetaProperty(self::RENDITION_SPREAD, $value);
        }
    }

    // TODO Implement Rendition settings
}
