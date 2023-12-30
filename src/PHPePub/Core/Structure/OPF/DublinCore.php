<?php

namespace PHPePub\Core\Structure\OPF;

/**
 * ePub OPF Dublin Core (dc:) Metadata structures
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2014- A. Grandt
 * @license   GNU LGPL 2.1
 */
class DublinCore extends MetaValue
{
    final public const CONTRIBUTOR = "contributor";

    final public const COVERAGE = "coverage";

    final public const CREATOR = "creator";

    final public const DATE = "date";

    final public const DESCRIPTION = "description";

    final public const FORMAT = "format";

    final public const IDENTIFIER = "identifier";

    final public const LANGUAGE = "language";

    final public const PUBLISHER = "publisher";

    final public const RELATION = "relation";

    final public const RIGHTS = "rights";

    final public const SOURCE = "source";

    final public const SUBJECT = "subject";

    final public const TITLE = "title";

    final public const TYPE = "type";

    /**
     * Class constructor.
     *
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value)
    {
        $this->setDc($name, $value);
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $name
     * @param string $value
     */
    public function setDc($name, $value): void
    {
        if (is_string($name)) {
            $this->setValue("dc:" . trim($name), $value);
        }
    }
}
