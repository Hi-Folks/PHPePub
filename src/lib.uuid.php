<?php
/*
   DrUUID RFC4122 library for PHP5
    by J. King (http://jkingweb.ca/)
   Licensed under MIT license

   See http://jkingweb.ca/code/php/lib.uuid/
    for documentation

   Last revised 2014-09-06
*/

/*
Copyright (c) 2009 J. King

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
*/


class UUID implements \Stringable
{
    final public const MD5  = 3;

    final public const SHA1 = 5;

    final public const clearVer = 15;

    // 00001111  Clears all bits of version byte with AND
    final public const clearVar = 63;

    // 00111111  Clears all relevant bits of variant byte with AND
    final public const varRes   = 224;

    // 11100000  Variant reserved for future use
    final public const varMS    = 192;

    // 11000000  Microsft GUID variant
    final public const varRFC   = 128;

    // 10000000  The RFC 4122 variant (this variant)
    final public const varNCS   = 0;

    // 00000000  The NCS compatibility variant
    final public const version1 = 16;

    // 00010000
    final public const version3 = 48;

    // 00110000
    final public const version4 = 64;

    // 01000000
    final public const version5 = 80;

    // 01010000
    final public const interval = "122192928000000000";

    //  Time (in 100ns steps) between the start of the Gregorian and Unix epochs
    final public const nsDNS  = '6ba7b810-9dad-11d1-80b4-00c04fd430c8';

    final public const nsURL  = '6ba7b811-9dad-11d1-80b4-00c04fd430c8';

    final public const nsOID  = '6ba7b812-9dad-11d1-80b4-00c04fd430c8';

    final public const nsX500 = '6ba7b814-9dad-11d1-80b4-00c04fd430c8';

    final public const bigChoose = -1;

    final public const bigNot    = 0;

    final public const bigNative = 1;

    final public const bigGMP    = 2;

    final public const bigBC     = 3;

    final public const bigSecLib = 4;

    final public const randChoose  = -1;

    final public const randPoor    = 0;

    final public const randDev     = 1;

    final public const randCAPICOM = 2;

    final public const randOpenSSL = 3;

    final public const randMcrypt  = 4;

    final public const randNative  = 5;

    //static properties
    protected static $randomFunc   = self::randChoose;

    protected static $randomSource;

    protected static $bignum       = self::bigChoose;

    protected static $storeClass   = "UUIDStorageStable";

    protected static $store;

    protected static $secLib;

    //instance properties
    protected $bytes;

    protected $hex;

    protected string $string;

    protected $urn;

    protected $version;

    protected $variant;

    protected $node;

    protected $time;

    public static function mint($ver = 1, $node = null, $ns = null, $time = null)
    {
        /* Create a new UUID based on provided data. */
        switch((int) $ver) {
            case 1:
                return new self(self::mintTime($node, $ns, $time));
            case 2:
                // Version 2 is not supported
                throw new UUIDException("Version 2 is unsupported.", 2);
            case 3:
                return new self(self::mintName(self::MD5, $node, $ns));
            case 4:
                return new self(self::mintRand());
            case 5:
                return new self(self::mintName(self::SHA1, $node, $ns));
            default:
                throw new UUIDException("Selected version is invalid or unsupported.", 1);
        }
    }

    public static function mintStr($ver = 1, $node = null, $ns = null, $time = null): string
    {
        /* If a randomness source hasn't been chosen, use the lowest common denominator. */
        if (self::$randomFunc == self::randChoose) {
            self::$randomFunc = self::randPoor;
        }

        /* Create a new UUID based on provided data and output a string rather than an object. */
        $uuid = match ((int) $ver) {
            1 => self::mintTime($node, $ns, $time),
            2 => throw new UUIDException("Version 2 is unsupported.", 2),
            3 => self::mintName(self::MD5, $node, $ns),
            4 => self::mintRand(),
            5 => self::mintName(self::SHA1, $node, $ns),
            default => throw new UUIDException("Selected version is invalid or unsupported.", 1),
        };
        return
            bin2hex(substr((string) $uuid, 0, 4)) . "-" .
            bin2hex(substr((string) $uuid, 4, 2)) . "-" .
            bin2hex(substr((string) $uuid, 6, 2)) . "-" .
            bin2hex(substr((string) $uuid, 8, 2)) . "-" .
            bin2hex(substr((string) $uuid, 10, 6));
    }

    public static function import($uuid): self
    {
        /* Import an existing UUID. */
        return ($uuid instanceof self) ? $uuid : new self(self::makeBin($uuid));
    }

    public static function compare($a, $b): bool
    {
        /* Compares the binary representations of two UUIDs.
           The comparison will return true if they are bit-exact,
            or if neither is valid. */
        return self::makeBin($a) == self::makeBin($b);
    }

    public static function seq()
    {
        /* Generate a random clock sequence; this is just two random bytes with the two most significant bits set to zero. */
        $seq = self::randomBytes(2);
        $seq[0] = chr(ord($seq[0]) & self::clearVar);
        return $seq;
    }

    public function __toString(): string
    {
        return $this->string;
    }

    public function __get($var)
    {
        switch($var) {
            case "bytes":
                return $this->bytes;
            case "hex":
                return bin2hex((string) $this->bytes);
            case "string":
                return $this->string;
            case "urn":
                return "urn:uuid:" . $this->string;
            case "version":
                return ord($this->bytes[6]) >> 4;
            case "variant":
                $byte = ord($this->bytes[8]);
                if ($byte >= self::varRes) {
                    return 3;
                }

                if ($byte >= self::varMS) {
                    return 2;
                }

                if ($byte >= self::varRFC) {
                    return 1;
                }

                return 0;
            case "node":
                if (ord($this->bytes[6]) >> 4 == 1) {
                    return bin2hex(strrev(substr((string) $this->bytes, 10)));
                }

                return null;
            case "time":
                if (ord($this->bytes[6]) >> 4 == 1) {
                    // Restore contiguous big-endian byte order
                    $time = bin2hex($this->bytes[6] . $this->bytes[7] . $this->bytes[4] . $this->bytes[5] . $this->bytes[0] . $this->bytes[1] . $this->bytes[2] . $this->bytes[3]);
                    // Clear version flag
                    $time[0] = "0";
                    // Decode the hex digits and return a fixed-precision string
                    $time = self::decodeTimestamp($time);
                    return $time;
                }

                return null;
            default:
                return null;
        }
    }

    protected function __construct($uuid)
    {
        if (strlen((string) $uuid) != 16) {
            throw new UUIDException("Input must be a valid UUID.", 3);
        }

        $this->bytes  = $uuid;
        // Optimize the most common use
        $this->string =
            bin2hex(substr((string) $uuid, 0, 4)) . "-" .
            bin2hex(substr((string) $uuid, 4, 2)) . "-" .
            bin2hex(substr((string) $uuid, 6, 2)) . "-" .
            bin2hex(substr((string) $uuid, 8, 2)) . "-" .
            bin2hex(substr((string) $uuid, 10, 6));
    }

    protected static function mintTime($node = null, $seq = null, $time = null): string
    {
        /* Generates a Version 1 UUID.
           These are derived from the time at which they were generated. */
        // Check for native 64-bit integer support
        if (self::$bignum == self::bigChoose) {
            self::$bignum = (PHP_INT_SIZE >= 8) ? self::bigNative : self::bigNot;
        }

        // ensure a store is available
        if (self::$store === null) {
            self::$store = new UUIDStorageVolatile();
        }

        // check any input for correctness and communicate with the store where appropriate
        [$node, $seq, $time] = self::checkTimeInput($node, $seq, $time);
        // construct a 60-bit timestamp, padded to 64 bits
        $time = self::buildTime($time);
        // Reorder bytes to their proper locations in the UUID
        $uuid  = $time[4] . $time[5] . $time[6] . $time[7] . $time[2] . $time[3] . $time[0] . $time[1];
        // Add the clock sequence
        $uuid .= $seq;
        // set variant
        $uuid[8] = chr(ord($uuid[8]) & self::clearVar | self::varRFC);
        // set version
        $uuid[6] = chr(ord($uuid[6]) & self::clearVer | self::version1);
        // Set the final 'node' parameter, a MAC address
        $uuid .= $node;
        return $uuid;
    }

    protected static function mintRand()
    {
        /* Generate a Version 4 UUID.
           These are derived solely from random numbers. */
        // generate random fields
        $uuid = self::randomBytes(16);
        // set variant
        $uuid[8] = chr(ord($uuid[8]) & self::clearVar | self::varRFC);
        // set version
        $uuid[6] = chr(ord($uuid[6]) & self::clearVer | self::version4);
        return $uuid;
    }

    protected static function mintName($ver, string $node, $ns): string
    {
        /* Generates a Version 3 or Version 5 UUID.
           These are derived from a hash of a name and its namespace, in binary form. */
        if ($ver == 3 && !$node) {
            throw new UUIDException("A name-string is required for Version 3 or 5 UUIDs.", 201);
        }

        // if the namespace UUID isn't binary, make it so
        $ns = self::makeBin($ns);
        if (!$ns) {
            throw new UUIDException("A valid UUID namespace is required for Version 3 or 5 UUIDs.", 202);
        }

        switch($ver) {
            case self::MD5:
                $version = self::version3;
                $uuid = md5($ns . $node, 1);
                break;
            case self::SHA1:
                $version = self::version5;
                $uuid = substr(sha1($ns . $node, 1), 0, 16);
                break;
        }

        // set variant
        $uuid[8] = chr(ord($uuid[8]) & self::clearVar | self::varRFC);
        // set version
        $uuid[6] = chr(ord($uuid[6]) & self::clearVer | $version);
        return ($uuid);
    }

    protected static function CheckTimeInput(array $node, $seq, $time): array
    {
        /* If no timestamp has been specified, generate one.
           Note that this will never be more accurate than to
           the microsecond, whereas UUID timestamps are measured in 100ns steps. */
        $time = ($time !== null) ? self::normalizeTime($time) : self::normalizeTime(microtime(), 1);
        /* If a node ID is supplied, use it and keep it in the store; if none is
           supplied, get it from the store or generate it if none is stored. */
        if ($node === null) {
            $node = self::$store->getNode();
            if (!$node) {
                $node = self::randomBytes(6);
                $node[0] = pack("C", ord($node[0]) | 1);
            }
        } else {
            $node = self::makeNode($node);
            if (!$node) {
                throw new UUIDException("Node must be a valid MAC address.", 101);
            }
        }

        // Do a sanity check on clock sequence if one is provided
        if ($seq !== null && strlen((string) $seq) != 2) {
            throw UUIDException("Clock sequence must be a two-byte binary string.", 102);
        }

        // If one is not provided, check stable/volatile storage for a valid clock sequence
        if ($seq === null) {
            $seq = self::$store->getSequence($time, $node);
        }

        // Generate a random clock sequence if one is not available
        if (!$seq) {
            $seq = self::seq();
            self::$store->setSequence($seq);
        }

        self::$store->setTimestamp($time);
        return [$node, $seq, $time];
    }

    protected static function normalizeTime(array $time, $expected = false)
    {
        /* Returns a string representation of the
           number of 100ns steps since the Unix epoch. */
        if(is_a($time, "DateTimeInterface") || is_a($time, "DateTime")) {
            return $time->format("U") . str_pad((string) $time->format("u"), 7, "0", STR_PAD_RIGHT);
        }

        switch(gettype($time)) {
            case "string":
                $time = explode(" ", $time);
                if(count($time) != 2) {
                    throw new UUIDException("Time input was of an unexpected format.", 103);
                }

                return $time[1] . substr(str_pad($time[0], 9, "0", STR_PAD_RIGHT), 2, 7);
            case "integer": // assume a second-precision timestamp
                return $time . "0000000";
            case "double":
                $time = sprintf("%F", $time);
                $time = explode(".", $time);
                return $time[0] . substr(str_pad($time[1], 7, "0", STR_PAD_RIGHT), 0, 7);
            default:
                throw new UUIDException("Time input was of an unexpected format.", 103);
        }
    }

    protected static function buildTime($time): string
    {
        switch (self::$bignum) {
            case self::bigNative:
                $out = base_convert($time + self::interval, 10, 16);
                break;
            case self::bigNot:
                // add the magic interval
                $out = $time + self::interval;
                // convert to a string, printing all digits rather than using scientific notation
                $out = sprintf("%F", $out);
                // strip decimal point if cast to float
                preg_match("/^\d+/", $out, $out);
                // convert to hexdecimal notation, big-endian
                $out = base_convert($out[0], 10, 16);
                break;
            case self::bigGMP:
                $out = gmp_strval(gmp_add($time, self::interval), 16);
                break;
            case self::bigBC:
                $out = bcadd((string) $time, self::interval, 0);
                $in = $out;
                $out = "";
                /* BC Math does not have a native equivalent of base_convert(),
                   so we have to fake it.  Chunking the number to as many
                   nybbles as PHP can handle in an integer speeds things up lots. */
                $base = (int) hexdec(str_repeat("f", (PHP_INT_SIZE * 2) - 1)) + 1;
                do {
                    $mod = (int) bcmod($in, $base);
                    $in = bcdiv($in, $base, 0);
                    $out = base_convert($mod, 10, 16) . $out;
                } while($in > 0);

                break;
            case self::bigSecLib:
                $out = new self::$secLib($time);
                $out = $out->add(new self::$secLib(self::interval));
                $out = $out->toHex();
                break;
            default:
                throw new UUIDException("Bignum method not implemented.", 901);
        }

        // convert to binary, padding to 8 bytes
        return pack("H*", str_pad((string) $out, 16, "0", STR_PAD_LEFT));
    }

    protected static function decodeTimestamp($hex): string
    {
        /* Convrt a UUID timestamp (in hex notation) to
           a Unix timestamp with microseconds. */
        // Check for native 64-bit integer support
        if (self::$bignum == self::bigChoose) {
            self::$bignum = (PHP_INT_SIZE >= 8) ? self::bigNative : self::bigNot;
        }

        switch(self::$bignum) {
            case self::bigNative:
                $time = hexdec((string) $hex) - self::interval;
                break;
            case self::bigGMP:
                $time = gmp_strval(gmp_sub("0x" . $hex, self::interval));
                break;
            case self::bigBC:
                /* BC Math does not natively handle hexadecimal input,
                   so we must convert to decimal in safe-sized chunks. */
                $time = 0;
                $mul = 1;
                $size = PHP_INT_SIZE * 2 - 1;
                $max = hexdec(str_repeat("f", $size)) + 1;
                $hex = str_split(str_pad((string) $hex, ceil(strlen((string) $hex) / $size) * $size, 0, STR_PAD_LEFT), $size);
                do {
                    $chunk = hexdec(array_pop($hex));
                    $time = bcadd($time, bcmul($chunk, $mul));
                    $mul = bcmul($max, $mul);
                } while (count($hex));

                // And finally subtract the magic number to get the correct timestamp
                $time = bcsub($time, self::interval);
                break;
            case self::bigSecLib:
                $time = new self::$secLib($hex, 16);
                $time = $time->subtract(new self::$secLib(self::interval));
                $time = $time->toString();
                break;
            case self::bigNot:
                $time = sprintf("%F", hexdec((string) $hex) - self::interval);
                preg_match("/^\d+/", $time, $time);
                $time = $time[0];
                break;
            default:
                throw new UUIDException("Bignum method not implemented.", 901);
        }

        return substr((string) $time, 0, strlen((string) $time) - 7) . "." . substr((string) $time, strlen((string) $time) - 7);
    }

    protected static function makeBin($str)
    {
        /* Ensure that an input string is a UUID.
           Returns binary representation, or false on failure. */
        $len = 16;
        if ($str instanceof self) {
            return $str->bytes;
        }

        if (strlen((string) $str) == $len) {
            return $str;
        }

        $str = preg_replace("/^urn:uuid:/is", "", (string) $str); // strip URN scheme and namespace
        $str = preg_replace("/[^a-f0-9]/is", "", $str);  // strip non-hex characters
        if (strlen($str) != ($len * 2)) {
            return false;
        }

        return pack("H*", $str);
    }

    protected static function makeNode($str)
    {
        /* Parse a string to see if it's a MAC address.
           If it's six bytes, don't touch it; if it's hex, reverse bytes */
        $len = 6;
        if (strlen((string) $str) == $len) {
            return $str;
        }

        $str = preg_replace("/[^a-f0-9]/is", "", (string) $str);  // strip non-hex characters
        if (strlen($str) != ($len * 2)) {
            return false;
        }

        // MAC addresses are little-endian and UUIDs are big-endian, so we reverse bytes
        return strrev(pack("H*", $str));
    }

    public static function randomBytes($bytes)
    {
        switch (self::$randomFunc) {
            case self::randChoose:
            case self::randPoor:
                /* Get the specified number of random bytes, using mt_rand(). */
                $rand = "";
                for ($a = 0; $a < $bytes; ++$a) {
                    $rand .= chr(mt_rand(0, 255));
                }

                return $rand;
            case self::randNative:
                /* Get the specified number of bytes from the PHP core.
                   This is available since PHP 7. */
                return random_bytes($bytes);
            case self::randDev:
                /* Get the specified number of random bytes using a file handle
                   previously opened with UUID::initRandom(). */
                return fread(self::$randomSource, $bytes);
            case self::randOpenSSL:
                /* Get the specified number of bytes from OpenSSL.
                   This is available since PHP 5.3. */
                return openssl_random_pseudo_bytes($bytes);
            case self::randMcrypt:
                /* Get the specified number of random bytes via Mcrypt. */
                return mcrypt_create_iv($bytes);
            case self::randCOM:
                /* Get the specified number of random bytes using Windows'
                   randomness source via a COM object previously created by UUID::initRandom().
                   Straight binary mysteriously doesn't work, hence the base64. */
                return base64_decode((string) self::$randomSource->GetRandom($bytes, 0));
            default:
                throw new UUIDException("Randomness source not implemented.", 902);
        }
    }

    public static function initAccurate(): void
    {
        $big = self::initBignum();
        if ($big == self::bigNot) {
            throw new UUIDException("64-bit integer arithmetic is not available.", 2001);
        }

        $rand = self::initRandom();
        if ($rand == self::randPoor) {
            throw new UUIDException("Secure random number generator is not available.", 2002);
        }

        if (!is_object(self::$store)) {
            try {
                call_user_func_array(["self", "initStorage"], func_gets_args());
            } catch(Exception $e) {
                throw new UUIDStorageException("Stable storage not available.", 2003, $e);
            }
        } elseif (!(self::$store instanceof UUIDStorage)) {
            throw new UUIDStorageException("Storage is invalid.", 2004);
        }
    }

    public static function initRandom($how = null)
    {
        /* Look for a system-provided source of randomness, which is usually crytographically secure.
           /dev/urandom is tried first because tests suggest it is faster than other options. */
        if ($how === null) {
            if (self::$randomFunc != self::randChoose) {
                return self::$randomFunc;
            }

            if (function_exists('random_bytes')) {
                $how = self::randNative;
            } elseif (function_exists('openssl_random_pseudo_bytes')) {
                $how = self::randOpenSSL;
            } elseif (function_exists('mcrypt_create_iv')) {
                $how = self::randMcrypt;
            } elseif (is_readable('/dev/urandom')) {
                $how = self::randDev;
            } else {
                $how = self::randCAPICOM;
            }

            try {
                self::initRandom($how);
            } catch(Exception) {
                self::$randomFunc = self::randPoor;
            }
        } else {
            $source = null;
            switch($how) {
                case self::randChoose:
                    self::$randomFunc = $how;
                    return self::initRandom();
                case self::randPoor:
                    self::$randomFunc = $how;
                    break;
                case self::randNative:
                    if (!function_exists('random_bytes')) {
                        throw new UUIDException("Randomness source is not available.", 802);
                    }

                    break;
                case self::randDev:
                    $source = @fopen('/dev/urandom', 'rb');
                    if (!$source) {
                        throw new UUIDException("Randomness source is not available.", 802);
                    }

                    break;
                case self::randOpenSSL:
                    if (!function_exists('openssl_random_pseudo_bytes')) {
                        throw new UUIDException("Randomness source is not available.", 802);
                    }

                    break;
                case self::randMcrypt:
                    if (!function_exists('mcrypt_create_iv')) {
                        throw new UUIDException("Randomness source is not available.", 802);
                    }

                    break;
                case self::randCAPICOM: // See http://msdn.microsoft.com/en-us/library/aa388182(VS.85).aspx
                    if (!class_exists('COM', 0)) {
                        throw new UUIDException("Randomness source is not available.", 802);
                    }

                    try {
                        $source = new COM('CAPICOM.Utilities.1');
                    } catch(Exception $e) {
                        throw new UUIDException("Randomness source is not available.", 802, $e);
                    }

                    break;
                default:
                    throw new UUIDException("Randomness source not implemented.", 902);
            }

            self::$randomSource = $source;
            self::$randomFunc = $how;
        }

        return self::$randomFunc;
    }

    public static function initBignum($how = null)
    {
        /* Check to see if PHP is running in a 32-bit environment and if so,
           use GMP or BC Math if available. */
        if ($how === null) {
            if (self::$bignum != self::bigChoose) {
                // determination has already been made
                return self::$bignum;
            }

            if (PHP_INT_SIZE >= 8) {
                self::$bignum = self::bigNative;
            } elseif (function_exists("gmp_add")) {
                self::$bignum = self::bigGMP;
            } elseif (function_exists("bcadd")) {
                self::$bignum = self::bigBC;
            } elseif (class_exists("\phpseclib\Math\BigInteger", 0)) { // phpseclib v2.x
                self::$bignum = self::bigSecLib;
                self::$secLib = "\phpseclib\Math\BigInteger";
            } elseif (class_exists("Math_BigInteger", 0)) { // phpseclib v1.x
                self::$bignum = self::bigSecLib;
                self::$secLib = "Math_BigInteger";
            } else {
                self::$bignum = self::bigNot;
            }
        } else {
            switch($how) {
                case self::bigChoose:
                    self::$bignum = $how;
                    return self::initBignum();
                case self::bigNot:
                    break;
                case self::bigNative:
                    if (PHP_INT_SIZE < 8) {
                        throw new UUIDException("Bignum method is not available.", 801);
                    }

                    break;
                case self::bigGMP:
                    if (!function_exists("gmp_add")) {
                        throw new UUIDException("Bignum method is not available.", 801);
                    }

                    break;
                case self::bigBC:
                    if (!function_exists("bcadd")) {
                        throw new UUIDException("Bignum method is not available.", 801);
                    }

                    break;
                case self::bigSecLib:
                    if (class_exists("\phpseclib\Math\BigInteger", 0)) { //v2.x
                        self::$secLib = "\phpseclib\Math\BigInteger";
                    } elseif (class_exists("Math_BigInteger", 0)) { //v1.x
                        self::$secLib = "Math_BigInteger";
                    } else {
                        throw new UUIDException("Bignum method is not available.", 801);
                    }

                    break;
                default:
                    throw new UUIDException("Bignum method not implemented.", 901);
            }

            self::$bignum = $how;
        }

        return self::$bignum;
    }

    public static function initStorage($file = null): void
    {
        if (self::$storeClass == "UUIDStorageStable") {
            try {
                self::$store = new UUIDStorageStable($file);
            } catch(Exception $e) {
                throw new UUIDStorageException("Storage class could not be instantiated with supplied arguments.", 1003, $e);
            }

            return;
        }

        $store = new ReflectionClass(self::$storeClass);
        $args = func_get_args();
        try {
            self::$store = $store->newInstanceArgs($args);
        } catch(Exception $exception) {
            throw new UUIDStorageException("Storage class could not be instantiated with supplied arguments.", 1003, $exception);
        }
    }

    public static function registerStorage($name): void
    {
        try {
            $store = new ReflectionClass($name);
        } catch(Exception $exception) {
            throw new UUIDStorageException("Storage class does not exist.", 1001, $exception);
        }

        if (!in_array("UUIDStorage", $store->getInterfaceNames())) {
            throw new UUIDStorageException("Storage class does not implement the UUIDStorage interface.", 1002);
        }

        self::$storeClass = $name;
        if (func_num_args() > 1) {
            $args = func_get_args();
            array_shift($args);
            try {
                self::$store = $store->newInstanceArgs($args);
            } catch(Exception $e) {
                throw new UUIDStorageException("Storage class could not be instantiated with supplied arguments.", 1003, $e);
            }
        }
    }
}

class UUIDException extends Exception {}

class UUIDStorageException extends UUIDException {}

interface UUIDStorage
{
    public function getNode();

    // return bytes or NULL if node cannot be retrieved
    public function getSequence($timestamp, $node);

    // return bytes or NULL if sequence is not available; this method should also update the stored timestamp
    public function setSequence($sequence);

    public function setTimestamp($timestamp);

    public const maxSequence = 16383; // 00111111 11111111
}

class UUIDStorageVolatile implements UUIDStorage
{
    protected $node;

    protected $timestamp;

    protected $sequence;

    public function getNode()
    {
        if ($this->node === null) {
            return;
        }

        return $this->node;
    }

    public function getSequence($timestamp, $node)
    {
        if ($node != $this->node) {
            $this->node = $node;
            return;
        }

        if ($this->sequence === null) {
            return;
        }

        if ($timestamp <= $this->timestamp) {
            $this->sequence = pack("n", (unpack("nseq", (string) $this->sequence)['seq'] + 1) & self::maxSequence);
        }

        $this->setTimestamp($timestamp);
        return $this->sequence;
    }

    public function setSequence($sequence): void
    {
        $this->sequence = pack("n", unpack("nseq", (string) $sequence)['seq'] & self::maxSequence);
    }

    public function setTimestamp($timestamp): void
    {
        $this->timestamp = $timestamp;
    }
}

class UUIDStorageStable extends UUIDStorageVolatile
{
    protected $file;

    protected $read = false;

    protected $wrote = true;

    public function __construct($path)
    {
        if (!file_exists($path)) {
            $dir = dirname((string) $path);
            if (!is_writable($dir)) {
                throw new UUIDStorageException("Stable storage is not writable.", 1102);
            }

            if (!is_readable($dir)) {
                throw new UUIDStorageException("Stable storage is not readable.", 1101);
            }
        } elseif (!is_writable($path)) {
            throw new UUIDStorageException("Stable storage is not writable.", 1102);
        } elseif (!is_readable($path)) {
            throw new UUIDStorageException("Stable storage is not readable.", 1101);
        }

        $this->file = $path;
    }

    protected function readState()
    {
        if (!file_exists($this->file)) { // a missing file is not an error
            return;
        }

        $data = @file_get_contents($this->file);
        if ($data === false) {
            throw new UUIDStorageException("Stable storage could not be read.", 1201);
        }

        $this->read = true;
        $this->wrote = false;
        if ($data === '' || $data === '0') { // an empty file is not an error
            return;
        }

        $data = @unserialize($data);
        if (!is_array($data) || count($data) < 3) {
            throw new UUIDStorageException("Stable storage data is invalid or corrupted.", 1203);
        }

        [$this->node, $this->sequence, $this->timestamp] = $data;
    }

    public function getNode()
    {
        $this->readState();
        return parent::getNode();
    }

    public function setSequence($sequence): void
    {
        if (!$this->read) {
            $this->readState();
        }

        parent::setSequence($sequence);
        $this->write();
    }

    public function setTimestamp($timestamp): void
    {
        parent::setTimestamp($timestamp);
        if ($this->wrote) {
            return;
        }

        $this->write();
    }

    protected function write($check = 1)
    {
        $data = serialize([$this->node, $this->sequence, $this->timestamp]);
        $write = @file_put_contents($this->file, $data);
        if ($check && $write === false) {
            throw new UUIDStorageException("Stable storage could not be written.", 1202);
        }

        $this->wrote = true;
        $this->read = false;
    }

    public function __destruct()
    {
        $this->write(0);
    }
}
