<?php
/**
 * PHPePub
 * <StringHelper.php description here>
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2015- A. Grandt
 * @license   GNU LGPL 2.1
 */

namespace PHPePub\Helpers;

use DOMDocument;
use DOMNode;
use PHPePub\Core\StaticData;

class StringHelper
{
    /**
     * Ensure the encoded string is a valid UTF-8 string.
     *
     * Note, that a mb_detect_encoding on the returned string will still return ASCII if the entire string is comprised of characters in the 1-127 range.
     *
     * @link: http://snippetdb.com/php/convert-string-to-utf-8-for-mysql
     *
     * @param string $in_str
     *
     * @return string converted string.
     */
    public static function fixEncoding($in_str)
    {
        if (mb_detect_encoding($in_str) == "UTF-8" && mb_check_encoding($in_str, "UTF-8")) {
            return $in_str;
        }

        return mb_convert_encoding($in_str, 'UTF-8', 'ISO-8859-1');
    }

    /**
     * Simply remove all HTML tags, brute force and no finesse.
     *
     * @param string $string html
     *
     * @return string
     */
    public static function html2text($string): ?string
    {
        return preg_replace('~<[^>]*>~', '', $string);
    }

    /**
     * Generates an UUID.
     *
     * Default version (4) will generate a random UUID, version 3 will URL based UUID.
     *
     * Added for convenience
     *
     * @param int    $bookVersion UUID version to retrieve, See lib.uuid.manual.html for details.
     * @param string $url
     *
     * @return string The formatted uuid
     */
    public static function createUUID($bookVersion = 4, $url = null)
    {
        return \UUID::mint($bookVersion, $url, \UUID::nsURL);
    }

    /**
     * Encode html code to use html entities, safeguarding it from potential character encoding problems
     * This function is a bit different from the vanilla htmlentities function in that it does not encode html tags.
     *
     * The regexp is taken from the PHP Manual discussion, it was written by user "busbyjon".
     * http://www.php.net/manual/en/function.htmlentities.php#90111
     *
     * @param string $string string to encode.
     */
    public static function encodeHtml($string): string
    {
        return strtr($string, StaticData::$htmlEntities);
    }

    /**
     * Remove all non essential html tags and entities.
     *
     * @param string $string
     *
     * @return string with the stripped entities.
     */
    public static function decodeHtmlEntities($string): string
    {
        $string = preg_replace('~\s*<br\s*/*\s*>\s*~i', "\n", (string) $string);
        $string = preg_replace('~\s*</(p|div)\s*>\s*~i', "\n\n", $string);
        $string = preg_replace('~<[^>]*>~', '', $string);

        $string = strtr($string, StaticData::$htmlEntities);

        $string = str_replace('&', '&amp;', $string);
        $string = str_replace('&amp;amp;', '&amp;', $string);
        $string = preg_replace('~&amp;(#x*[a-fA-F0-9]+;)~', '&\1', $string);
        $string = str_replace('<', '&lt;', $string);

        return str_replace('>', '&gt;', $string);
    }

    /**
     * Helper function to create a DOM fragment with given markup.
     *
     * @author Adam Schmalhofer
     *
     * @param DOMDocument $dom
     * @param string      $markup
     *
     * @return DOMNode fragment in a node.
     */
    public static function createDomFragment($dom, $markup)
    {
        $node = $dom->createDocumentFragment();
        $node->appendXML($markup);

        return $node;
    }

    /**
     * @param $doc
     *
     * @return string
     */
    public static function removeComments($doc): string|array|null
    {
        $doc = preg_replace('~--\s+>~', '-->', (string) $doc);
        $doc = preg_replace('~<\s*!\s*--~', '<!--', $doc);

        $cPos = mb_strpos($doc, "<!--");
        if ($cPos !== false) {
            $startCount = substr_count($doc, "<!--");
            $endCount = substr_count($doc, "-->");

            $lastCPos = -1;

            while ($cPos !== false && $lastCPos != $cPos) {
                $lastCPos = $cPos;
                $lastEPos = $cPos;
                $ePos = $cPos;
                do {
                    $ePos = mb_strpos($doc, "-->", $ePos + 1);
                    if ($ePos !== false) {
                        $lastEPos = $ePos;
                        $comment = mb_substr($doc, $cPos, ($lastEPos + 3) - $cPos);
                        $startCount = substr_count($comment, "<!--");
                        $endCount = substr_count($comment, "-->");
                    } elseif ($lastEPos == $cPos) {
                        $lastEPos = mb_strlen($doc) - 3;
                    }
                } while ($startCount !== $endCount && $ePos !== false);

                $doc = substr_replace($doc, "", $cPos, ($lastEPos + 3) - $cPos);
                $cPos = mb_strpos($doc, "<!--");
            }
        }

        // print "<pre>\n" . htmlentities($doc) . "\n</pre>\n";
        return $doc;
    }
}
