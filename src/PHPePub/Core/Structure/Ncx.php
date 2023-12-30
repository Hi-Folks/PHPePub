<?php

namespace PHPePub\Core\Structure;

use PHPePub\Core\EPub;
use PHPePub\Core\Structure\NCX\NavMap;
use PHPePub\Core\Structure\NCX\NavPoint;

/**
 * ePub NCX file structure
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2009- A. Grandt
 * @license   GNU LGPL, Attribution required for commercial implementations, requested for everything else.
 */
class Ncx
{
    final public const MIMETYPE = "application/x-dtbncx+xml";

    private string $bookVersion = EPub::BOOK_VERSION_EPUB2;

    /** @var EPub $parentBook */
    private $parentBook;

    private ?\PHPePub\Core\Structure\NCX\NavMap $navMap = null;
    private ?string $uid = null;
    private array $meta = [];
    private ?string $docTitle = null;
    private ?string $docAuthor = null;

    private $currentLevel;
    private $lastLevel;

    private string $languageCode = "en";
    private string $writingDirection = EPub::DIRECTION_LEFT_TO_RIGHT;

    public $chapterList = [];
    public $referencesTitle = "Guide";
    public $referencesClass = "references";
    public $referencesId = "references";
    public $referencesList = [];
    public $referencesName = [];
    public $referencesOrder;

    /**
     * Class constructor.
     *
     * @param string $uid
     * @param string $docTitle
     * @param string $docAuthor
     * @param string $languageCode
     * @param string $writingDirection
     */
    public function __construct($uid = null, $docTitle = null, $docAuthor = null, $languageCode = "en", $writingDirection = EPub::DIRECTION_LEFT_TO_RIGHT)
    {
        $this->navMap = new NavMap($writingDirection);
        $this->currentLevel = $this->navMap;
        $this->setUid($uid);
        $this->setDocTitle($docTitle);
        $this->setDocAuthor($docAuthor);
        $this->setLanguageCode($languageCode);
        $this->setWritingDirection($writingDirection);
    }

    /**
     * Class destructor
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->parentBook, $this->bookVersion, $this->navMap, $this->uid, $this->meta);
        unset($this->docTitle, $this->docAuthor, $this->currentLevel, $this->lastLevel);
        unset($this->languageCode, $this->writingDirection, $this->chapterList, $this->referencesTitle);
        unset($this->referencesClass, $this->referencesId, $this->referencesList, $this->referencesName);
        unset($this->referencesOrder);
    }

    /**
     * @param EPub $parentBook
     */
    public function setBook($parentBook): void
    {
        $this->parentBook = $parentBook;
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $bookVersion
     */
    public function setVersion($bookVersion): void
    {
        $this->bookVersion = is_string($bookVersion) ? trim($bookVersion) : EPub::BOOK_VERSION_EPUB2;
    }

    /**
     *
     * @return bool TRUE if the book is set to type ePub 2
     */
    public function isEPubVersion2(): bool
    {
        return $this->bookVersion === EPub::BOOK_VERSION_EPUB2;
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $uid
     */
    public function setUid($uid): void
    {
        $this->uid = is_string($uid) ? trim($uid) : null;
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $docTitle
     */
    public function setDocTitle($docTitle): void
    {
        $this->docTitle = is_string($docTitle) ? trim($docTitle) : null;
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $docAuthor
     */
    public function setDocAuthor($docAuthor): void
    {
        $this->docAuthor = is_string($docAuthor) ? trim($docAuthor) : null;
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $languageCode
     */
    public function setLanguageCode($languageCode): void
    {
        $this->languageCode = is_string($languageCode) ? trim($languageCode) : "en";
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $writingDirection
     */
    public function setWritingDirection($writingDirection): void
    {
        $this->writingDirection = is_string($writingDirection) ? trim($writingDirection) : EPub::DIRECTION_LEFT_TO_RIGHT;
    }

    /**
     *
     * Enter description here ...
     *
     * @param NavMap $navMap
     */
    public function setNavMap($navMap): void
    {
        if ($navMap == null) {
            return;
        }
        if (!is_object($navMap)) {
            return;
        }
        $this->navMap = $navMap;
    }

    /**
     * Add one chapter level.
     *
     * Subsequent chapters will be added to this level.
     *
     * @param string $navTitle
     * @param string $navId
     * @param string $navClass
     * @param bool   $isNavHidden
     * @param null   $writingDirection
     */
    public function subLevel($navTitle = null, $navId = null, $navClass = null, $isNavHidden = false, $writingDirection = null): \PHPePub\Core\Structure\NCX\NavPoint|bool
    {
        $navPoint = false;
        if (isset($navTitle) && isset($navClass)) {
            $navPoint = new NavPoint($navTitle, null, $navId, $navClass, $isNavHidden, $writingDirection);
            $this->addNavPoint($navPoint);
        }
        if ($this->lastLevel !== null) {
            $this->currentLevel = $this->lastLevel;
        }

        return $navPoint;
    }

    /**
     * Step back one chapter level.
     *
     * Subsequent chapters will be added to this chapters parent level.
     */
    public function backLevel(): void
    {
        $this->lastLevel = $this->currentLevel;
        $this->currentLevel = $this->currentLevel->getParent();
    }

    /**
     * Step back to the root level.
     *
     * Subsequent chapters will be added to the rooot NavMap.
     */
    public function rootLevel(): void
    {
        $this->lastLevel = $this->currentLevel;
        $this->currentLevel = $this->navMap;
    }

    /**
     * Step back to the given level.
     * Useful for returning to a previous level from deep within the structure.
     * Values below 2 will have the same effect as rootLevel()
     *
     * @param int $newLevel
     */
    public function setCurrentLevel($newLevel): void
    {
        if ($newLevel <= 1) {
            $this->rootLevel();
        } else {
            while ($this->currentLevel->getLevel() > $newLevel) {
                $this->backLevel();
            }
        }
    }

    /**
     * Get current level count.
     * The indentation of the current structure point.
     *
     * @return int current level count;
     */
    public function getCurrentLevel()
    {
        return $this->currentLevel->getLevel();
    }

    /**
     * Add child NavPoints to current level.
     *
     * @param NavPoint $navPoint
     */
    public function addNavPoint($navPoint): void
    {
        $this->lastLevel = $this->currentLevel->addNavPoint($navPoint);
    }

    /**
     *
     * Enter description here ...
     *
     * @return NavMap
     */
    public function getNavMap(): ?\PHPePub\Core\Structure\NCX\NavMap
    {
        return $this->navMap;
    }

    /**
     *
     * Enter description here ...
     *
     * @param string $name
     * @param string $content
     */
    public function addMetaEntry($name, $content): void
    {
        $name = is_string($name) ? trim($name) : null;
        $content = is_string($content) ? trim($content) : null;
        if ($name == null) {
            return;
        }
        if ($content == null) {
            return;
        }
        $this->meta[] = [$name => $content];
    }

    /**
     *
     * Enter description here ...
     */
    public function finalize(): string
    {
        $nav = $this->navMap->finalize();

        $ncx = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        if ($this->isEPubVersion2()) {
            $ncx .= "<!DOCTYPE ncx PUBLIC \"-//NISO//DTD ncx 2005-1//EN\"\n"
                . "  \"http://www.daisy.org/z3986/2005/ncx-2005-1.dtd\">\n";
        }
        $ncx .= "<ncx xmlns=\"http://www.daisy.org/z3986/2005/ncx/\" version=\"2005-1\" xml:lang=\"" . $this->languageCode . "\" dir=\"" . $this->writingDirection . "\">\n"
            . "\t<head>\n"
            . "\t\t<meta name=\"dtb:uid\" content=\"" . $this->uid . "\" />\n"
            . "\t\t<meta name=\"dtb:depth\" content=\"" . $this->navMap->getNavLevels() . "\" />\n"
            . "\t\t<meta name=\"dtb:totalPageCount\" content=\"0\" />\n"
            . "\t\t<meta name=\"dtb:maxPageNumber\" content=\"0\" />\n";

        foreach ($this->meta as $metaEntry) {
            $content = reset($metaEntry);
            $name = key($metaEntry);
            $ncx .= "\t\t<meta name=\"" . $name . "\" content=\"" . $content . "\" />\n";
        }

        $ncx .= "\t</head>\n\n\t<docTitle>\n\t\t<text>"
            . $this->docTitle
            . "</text>\n\t</docTitle>\n\n\t<docAuthor>\n\t\t<text>"
            . $this->docAuthor
            . "</text>\n\t</docAuthor>\n\n"
            . $nav;

        return $ncx . "</ncx>\n";
    }

    /**
     *
     * @param string $cssFileName
     *
     */
    public function finalizeEPub3(string $title = "Table of Contents", $cssFileName = null): string
    {
        $end = '<?xml version="1.0" encoding="UTF-8"?>
<html xmlns="http://www.w3.org/1999/xhtml"
'
            . "      xmlns:epub=\"http://www.idpf.org/2007/ops\"\n"
            . "      xml:lang=\"" . $this->languageCode . "\" lang=\"" . $this->languageCode . "\" dir=\"" . $this->writingDirection . "\">\n"
            . "\t<head>\n"
            . "\t\t<title>" . $this->docTitle . "</title>\n"
            . "\t\t<meta http-equiv=\"default-style\" content=\"text/html; charset=utf-8\"/>\n";

        if ($this->parentBook !== null) {
            $end .= $this->parentBook->getViewportMetaLine();
        }

        if ($cssFileName !== null) {
            $end .= "\t\t<link rel=\"stylesheet\" href=\"" . $cssFileName . "\" type=\"text/css\"/>\n";
        }

        return $end . ('	</head>
	<body epub:type="frontmatter toc">
		<header>
			<h1>' . $title . "</h1>\n"
            . "\t\t</header>\n"
            . $this->navMap->finalizeEPub3()
            . $this->finalizeEPub3Landmarks()
            . "\t</body>\n"
            . "</html>\n");
    }

    /**
     * Build the references for the ePub 2 toc.
     * These are merely reference pages added to the end of the navMap though.
     */
    public function finalizeReferences(): void
    {
        if ($this->referencesList !== null && count($this->referencesList) > 0) {
            $this->rootLevel();
            $this->subLevel($this->referencesTitle, $this->referencesId, $this->referencesClass);
            $refId = 1;
            foreach ($this->referencesOrder as $item => $descriptive) {
                if (array_key_exists($item, $this->referencesList)) {
                    $name = (empty($this->referencesName[$item]) ? $descriptive : $this->referencesName[$item]);
                    $navPoint = new NavPoint($name, $this->referencesList[$item], "ref-" . $refId++);
                    $this->addNavPoint($navPoint);
                }
            }
        }
    }

    /**
     * Build the landmarks for the ePub 3 toc.
     */
    public function finalizeEPub3Landmarks(): string
    {
        $lm = "";
        if ($this->referencesList !== null && count($this->referencesList) > 0) {
            $lm = '			<nav epub:type="landmarks">
				<h2'
                . ($this->writingDirection === EPub::DIRECTION_RIGHT_TO_LEFT ? " dir=\"rtl\"" : "") . ">"
                . $this->referencesTitle . "</h2>\n"
                . "\t\t\t\t<ol>\n";

            $li = "";
            foreach ($this->referencesOrder as $item => $descriptive) {
                if (array_key_exists($item, $this->referencesList)) {
                    $li .= "\t\t\t\t\t<li><a epub:type=\""
                        . $item
                        . "\" href=\"" . $this->referencesList[$item] . "\">"
                        . (empty($this->referencesName[$item]) ? $descriptive : $this->referencesName[$item])
                        . "</a></li>\n";
                }
            }
            if ($li === '' || $li === '0') {
                return "";
            }

            $lm .= $li
                . "\t\t\t\t</ol>\n"
                . "\t\t\t</nav>\n";
        }

        return $lm;
    }
}
