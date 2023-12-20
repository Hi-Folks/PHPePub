<?php
namespace PHPePub\Core\Structure\OPF;

/**
 * Reference constants
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2014- A. Grandt
 * @license   GNU LGPL 2.1
 */
class Reference {
    /* REFERENCE types are derived from the "Chicago Manual of Style"
     */

    /** Acknowledgements page */
    final public const ACKNOWLEDGEMENTS = "acknowledgements";

    /** Bibliography page */
    final public const BIBLIOGRAPHY = "bibliography";

    /** Colophon page */
    final public const COLOPHON = "colophon";

    /** Copyright page */
    final public const COPYRIGHT_PAGE = "copyright-page";

    /** Dedication */
    final public const DEDICATION = "dedication";

    /** Epigraph */
    final public const EPIGRAPH = "epigraph";

    /** Foreword */
    final public const FOREWORD = "foreword";

    /** Glossary page */
    final public const GLOSSARY = "glossary";

    /** back-of-book style index */
    final public const INDEX = "index";

    /** List of illustrations */
    final public const LIST_OF_ILLUSTRATIONS = "loi";

    /** List of tables */
    final public const LIST_OF_TABLES = "lot";

    /** Notes page */
    final public const NOTES = "notes";

    /** Preface page */
    final public const PREFACE = "preface";

    /** Table of contents */
    final public const TABLE_OF_CONTENTS = "toc";

    /** Page with possibly title, author, publisher, and other metadata */
    final public const TITLE_PAGE = "titlepage";

    /** First page of the book, ie. first page of the first chapter */
    final public const TEXT = "text";

    // ******************
    // ePub3 constants
    // ******************

    // Document partitions
    /** The publications cover(s), jacket information, etc. This is officially in ePub3, but works for ePub 2 as well */
    final public const COVER = "cover";

    /** Preliminary material to the content body, such as tables of contents, dedications, etc. */
    final public const FRONTMATTER = "frontmatter";

    /** The main (body) content of a document. */
    final public const BODYMATTER = "bodymatter";

    /** Ancillary material occurring after the document body, such as indices, appendices, etc. */
    final public const BACKMATTER = "backmatter";

    private $type = null;
    private $title = null;
    private $href = null;

    /**
     * Class constructor.
     *
     * @param string $type
     * @param string $title
     * @param string $href
     */
    function __construct($type, $title, $href) {
        $this->setType($type);
        $this->setTitle($title);
        $this->setHref($href);
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $type
     */
    function setType($type) {
        $this->type = is_string($type) ? trim($type) : null;
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $title
     */
    function setTitle($title) {
        $this->title = is_string($title) ? trim($title) : null;
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $href
     */
    function setHref($href) {
        $this->href = is_string($href) ? trim($href) : null;
    }

    /**
     * Class destructor
     *
     * @return void
     */
    function __destruct() {
        unset($this->type, $this->title, $this->href);
    }

    /**
     *
     * Enter description here ...
     *
     * @return string
     */
    function finalize() {
        return "\t\t<reference type=\"" . $this->type . "\" title=\"" . $this->title . "\" href=\"" . $this->href . "\" />\n";
    }
}
