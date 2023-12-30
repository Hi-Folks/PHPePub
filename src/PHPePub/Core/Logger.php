<?php

namespace PHPePub\Core;

/**
 * Simple log line aggregator.
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2012- A. Grandt
 * @license   GNU LGPL 2.1
 */
class Logger
{
    private string $log = "";

    private array|float|null $tStart = null;

    private $tLast;

    private ?string $name = null;

    private bool $isDebugging = false;

    /**
     * Class constructor.
     *
     * @param string $name
     * @param bool $isLogging
     */
    public function __construct($name = null, private $isLogging = false)
    {
        $this->name = $name === null ? "" : $name . " : ";
        $this->start();
    }

    public function start(): void
    {
        /* Prepare Logging. Just in case it's used. later */
        if ($this->isLogging) {
            $this->tStart = gettimeofday();
            $this->tLast = $this->tStart;
            $this->log = "<h1>Log: " . $this->name . "</h1>\n<pre>Started: " . gmdate("D, d M Y H:i:s T", $this->tStart['sec']) . "\n &#916; Start ;  &#916; Last  ;";
            $this->logLine("Start");
        }
    }

    public function logLine(string $line): void
    {
        if ($this->isLogging) {
            $tTemp = gettimeofday();
            $tS = $this->tStart['sec'] + (((int)($this->tStart['usec'] / 100)) / 10000);
            $tL = $this->tLast['sec'] + (((int)($this->tLast['usec'] / 100)) / 10000);
            $tT = $tTemp['sec'] + (((int)($tTemp['usec'] / 100)) / 10000);

            $logline = sprintf("\n+%08.04f; +%08.04f; ", ($tT - $tS), ($tT - $tL)) . $this->name . $line;
            $this->log .= $logline;
            $this->tLast = $tTemp;

            if ($this->isDebugging) {
                echo "<pre>" . $logline . "\n</pre>\n";
            }
        }
    }

    /**
     * Class destructor
     *
     * @return void
     * @TODO make sure elements in the destructor match the current class elements
     */
    public function __destruct()
    {
        unset($this->log);
    }

    public function dumpInstalledModules(): void
    {
        if ($this->isLogging) {
            $isCurlInstalled = extension_loaded('curl') && function_exists('curl_version');
            $isGdInstalled = extension_loaded('gd') && function_exists('gd_info');
            $isExifInstalled = extension_loaded('exif') && function_exists('exif_imagetype');
            $isFileGetContentsInstalled = function_exists('file_get_contents');
            $isFileGetContentsExtInstalled = $isFileGetContentsInstalled && ini_get('allow_url_fopen');

            $this->logLine("isCurlInstalled...............: " . $this->boolYN($isCurlInstalled));
            $this->logLine("isGdInstalled.................: " . $this->boolYN($isGdInstalled));
            $this->logLine("isExifInstalled...............: " . $this->boolYN($isExifInstalled));
            $this->logLine("isFileGetContentsInstalled....: " . $this->boolYN($isFileGetContentsInstalled));
            $this->logLine("isFileGetContentsExtInstalled.: " . $this->boolYN($isFileGetContentsExtInstalled));
        }
    }

    public function getLog(): string
    {
        return $this->log;
    }

    /**
     * @param $isCurlInstalled
     */
    public function boolYN($isCurlInstalled): string
    {
        return ($isCurlInstalled ? "Yes" : "No");
    }
}
