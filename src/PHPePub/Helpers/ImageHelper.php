<?php
/**
 * PHPePub
 * <ImageHelper.php description here>
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2015- A. Grandt
 * @license   GNU LGPL 2.1
 */

namespace PHPePub\Helpers;

use grandt\ResizeGif\ResizeGif;
use PHPePub\Core\EPub;
use SimpleXMLElement;

class ImageHelper
{
    protected static $isGdInstalled;

    protected static $isExifInstalled;

    protected static $isAnimatedGifResizeInstalled;

    /**
     * get mime type from image data
     *
     * By fireweasel found on http://stackoverflow.com/questions/2207095/get-image-mimetype-from-resource-in-php-gd
     *
     * @staticvar array $type
     *
     * @param object $binary
     *
     * @return string
     */
    public static function getImageFileTypeFromBinary($binary)
    {
        $hits = null;
        if (!preg_match(
            '/\A(?:(\xff\xd8\xff)|(GIF8[79]a)|(\x89PNG\x0d\x0a)|(BM)|(\x49\x49(?:\x2a\x00|\x00\x4a))|(FORM.{4}ILBM))/',
            $binary,
            $hits
        )
        ) {
            return 'application/octet-stream';
        }

        static $type = [1 => 'image/jpeg', 2 => 'image/gif', 3 => 'image/png', 4 => 'image/x-windows-bmp', 5 => 'image/tiff', 6 => 'image/x-ilbm'];

        return $type[count($hits) - 1];
    }

    /**
     * Scale image dimensions if they exceed the max image constraints.
     *
     * @param $width
     * @param $height
     *
     * @param $maxImageWidth
     * @param $maxImageHeight
     */
    public static function getImageScale($width, $height, $maxImageWidth, $maxImageHeight): int|float
    {
        if ($width > $maxImageWidth) {
            return $maxImageWidth / $width;
        }

        if ($height > $maxImageHeight) {
            return $maxImageHeight / $height;
        }

        return 1;
    }

    /**
     * @param        $attr
     *
     * @return array
     */
    public static function splitCSV($attr, string $sep = ','): array|false
    {

        if (strpos((string) $attr, $sep) > 0) {
            return preg_split('/\s*' . $sep . '\s*/', (string) $attr);
        }

        if ($sep !== ',' && strpos((string) $attr, ',') > 0) {
            return preg_split('/\s*,\s*/', (string) $attr);
        }

        if (strpos((string) $attr, ';') > 0) {
            return preg_split('/\s*;\s*/', (string) $attr);
        }

        return preg_split('/\s+/', (string) $attr);
    }

    /**
     * Copyright WikiMedia:
     * https://doc.wikimedia.org/mediawiki-core/master/php/SVGMetadataExtractor_8php_source.html
     *
     * @param     $length
     * @param int $portSize
     */
    public static function scaleSVGUnit($length, $portSize = 512): float
    {
        static $unitLength = [
            'px' => 1.0,
            'pt' => 1.25,
            'pc' => 15.0,
            'mm' => 3.543307,
            'cm' => 35.43307,
            'in' => 90.0,
            'em' => 16.0,
            // fake it?
            'ex' => 12.0,
            // fake it?
            '' => 1.0,
        ];
        $matches = [];
        if (preg_match('/^\s*(\d+(?:\.\d+)?)(em|ex|px|pt|pc|cm|mm|in|%|)\s*$/', (string) $length, $matches)) {
            $length = (float) $matches[1];
            $unit = $matches[2];
            if ($unit == '%') {
                return $length * 0.01 * $portSize;
            }

            return $length * $unitLength[$unit];
        }

        // Assume pixels
        return (float) $length;
    }

    /**
     * @param SimpleXMLElement $svg
     */
    public static function handleSVGAttribs($svg): array
    {
        $metadata = [];
        $attr = $svg->attributes();
        $viewWidth = 0;
        $viewHeight = 0;
        $aspect = 1.0;
        $x = null;
        $y = null;
        $width = null;
        $height = null;

        if ($attr->viewBox !== null) {
            // min-x min-y width height
            $viewBoxAttr = trim($attr->viewBox);

            $viewBox = self::splitCSV($viewBoxAttr);
            if (count($viewBox) == 4) {
                $viewWidth = self::scaleSVGUnit($viewBox[2]);
                $viewHeight = self::scaleSVGUnit($viewBox[3]);
                if ($viewWidth > 0 && $viewHeight > 0) {
                    $aspect = $viewWidth / $viewHeight;
                }
            }
        }

        if ($attr->x !== null) {
            $x = self::scaleSVGUnit($attr->x, 0);
            $metadata['originalX'] = "" . $attr->x;
        }

        if ($attr->y !== null) {
            $y = self::scaleSVGUnit($attr->y, 0);
            $metadata['originalY'] = "" . $attr->y;
        }

        if ($attr->width !== null) {
            $width = self::scaleSVGUnit($attr->width, $viewWidth);
            $metadata['originalWidth'] = "" . $attr->width;
        }

        if ($attr->height !== null) {
            $height = self::scaleSVGUnit($attr->height, $viewHeight);
            $metadata['originalHeight'] = "" . $attr->height;
        }

        if (!isset($width) && !isset($height)) {
            $width = 512;
            $height = $width / $aspect;
        } elseif (isset($width) && !isset($height)) {
            $height = $width / $aspect;
        } elseif (isset($height) && !isset($width)) {
            $width = $height * $aspect;
        }

        if ($x > 0 && $y > 0) {
            $metadata['x'] = (int) round($x);
            $metadata['y'] = (int) round($y);
        }

        if ($width > 0 && $height > 0) {
            $metadata['width'] = (int) round($width);
            $metadata['height'] = (int) round($height);
            $metadata['aspect'] = $aspect;
        }

        return $metadata;
    }

    /**
     * Get an image from a file or url, return it resized if the image exceeds the $maxImageWidth or $maxImageHeight directives.
     *
     * The return value is an array.
     * ['width'] is the width of the image.
     * ['height'] is the height of the image.
     * ['mime'] is the mime type of the image. Resized images are always in jpeg format.
     * ['image'] is the image data.
     * ['ext'] is the extension of the image file.
     *
     * @param EPub   $book
     * @param string $imageSource path or url to file.
     *
     * @throws \Exception
     */
    public static function getImage($book, $imageSource): bool|array
    {
        $width = -1;
        $height = -1;
        $mime = "application/octet-stream";
        $ext = "";

        $image = FileHelper::getFileContents($imageSource);
        $ratio = 1;

        if ($image !== false && strlen((string) $image) > 0) {
            if (str_starts_with(trim((string) $image), '<svg') || (str_starts_with(trim((string) $image), '<?xml') || strpos((string) $image, '<svg') > 0)) {
                // SVG image.
                $xml = simplexml_load_string((string) $image);
                $attr = $xml->attributes();

                $meta = ImageHelper::handleSVGAttribs($xml);

                $mime = "image/svg+xml";
                $ext = "svg";

                $width = $meta['width'];
                $height = $meta['height'];

                $ratio = ImageHelper::getImageScale($width, $height, $book->maxImageWidth, $book->maxImageHeight);

                if ($ratio < 1) {

                    $attr->width = $width * $ratio;
                    $attr->height = $height * $ratio;
                }

                $image = $xml->asXML();
            } else {

                $imageFile = imagecreatefromstring($image);
                if ($imageFile !== false) {
                    $width = ImageSX($imageFile);
                    $height = ImageSY($imageFile);
                }

                if (self::isExifInstalled()) {
                    @$type = exif_imagetype($imageSource);
                    $mime = image_type_to_mime_type($type);
                }

                if ($mime === "application/octet-stream") {
                    $mime = ImageHelper::getImageFileTypeFromBinary($image);
                }

                if ($mime === "application/octet-stream") {
                    $mime = MimeHelper::getMimeTypeFromUrl($imageSource);
                }
            }
        } else {
            return false;
        }

        if ($width <= 0 || $height <= 0) {
            return false;
        }

        if ($mime !== "image/svg+xml" && self::isGdInstalled()) {
            $ratio = ImageHelper::getImageScale($width, $height, $book->maxImageWidth, $book->maxImageHeight);

            if ($ratio < 1 || empty($mime)) {
                if ($mime == "image/png" || ($book->isGifImagesEnabled === false && $mime == "image/gif")) {
                    $image_o = imagecreatefromstring($image);
                    $image_p = imagecreatetruecolor((int) ($width * $ratio), (int) ($height * $ratio));
                    imagealphablending($image_p, false);
                    imagesavealpha($image_p, true);
                    imagealphablending($image_o, true);
                    imagecopyresampled($image_p, $image_o, 0, 0, 0, 0, (int) ($width * $ratio), (int) ($height * $ratio), $width, $height);
                    ob_start();
                    imagepng($image_p, null, 9);
                    $image = ob_get_contents();
                    ob_end_clean();
                    imagedestroy($image_o);
                    imagedestroy($image_p);
                    $ext = "png";
                } elseif ($book->isGifImagesEnabled !== false && $mime == "image/gif") {
                    $tFileD = tempnam("BewareOfGeeksBearingGifs", "grD");
                    ResizeGif::ResizeByRatio($imageSource, $tFileD, $ratio);
                    $image = file_get_contents($tFileD);
                    unlink($tFileD);
                } else {
                    $image_o = imagecreatefromstring($image);
                    $image_p = imagecreatetruecolor($width * $ratio, $height * $ratio);

                    imagecopyresampled($image_p, $image_o, 0, 0, 0, 0, ($width * $ratio), ($height * $ratio), $width, $height);
                    ob_start();
                    imagejpeg($image_p, null, 80);
                    $image = ob_get_contents();
                    ob_end_clean();

                    imagedestroy($image_o);
                    imagedestroy($image_p);

                    $mime = "image/jpeg";
                    $ext = "jpg";
                }
            }
        }

        if ($ext === "") {
            static $mimeToExt = ['image/jpeg'    => 'jpg', 'image/gif'     => 'gif', 'image/png'     => 'png', 'image/svg+xml' => "svg"];

            if (isset($mimeToExt[$mime])) {
                $ext = $mimeToExt[$mime];
            }
        }

        $rv = [];
        $rv['width'] = $width * $ratio;
        $rv['height'] = $height * $ratio;
        $rv['mime'] = $mime;
        $rv['image'] = $image;
        $rv['ext'] = $ext;

        return $rv;
    }

    /**
     * @return boolean
     */
    public static function isAnimatedGifResizeInstalled()
    {
        return self::$isAnimatedGifResizeInstalled;
    }

    /**
     * @return mixed
     */
    public static function isGdInstalled()
    {
        if (!isset(self::$isGdInstalled)) {
            self::$isGdInstalled = (extension_loaded('gd') || extension_loaded('gd2')) && function_exists('gd_info');
        }

        return self::$isGdInstalled;
    }

    /**
     * @return mixed
     */
    public static function isExifInstalled()
    {
        if (!isset(self::$isExifInstalled)) {
            self::$isExifInstalled = extension_loaded('exif') && function_exists('exif_imagetype');
        }

        return self::$isExifInstalled;
    }
}
