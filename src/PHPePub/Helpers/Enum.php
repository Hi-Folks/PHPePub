<?php

namespace PHPePub\Helpers;

use ReflectionClass;
use UnexpectedValueException;

/**
 * Author: Igor Vorobiov
 * Author URL: http://blog.igorvorobiov.com/2015/01/11/tip3-enums-in-php-or-an-alternative-solution-to-splenum/
 * More:
 *   http://stackoverflow.com/questions/254514/php-and-enumerations
 */
abstract class Enum
{
    private static array $constantsCache = [];

    private $value;

    public function __construct($value)
    {
        if (!self::has($value)) {
            throw new UnexpectedValueException(sprintf("Value '%s' is not part of the enum ", $value) . static::class);
        }

        $this->value = $value;
    }

    public function is($value): bool
    {
        return $this->value === $value;
    }

    public function value()
    {
        return $this->value;
    }

    public static function has($value): bool
    {
        return in_array($value, self::toArray(), true);
    }

    public static function toArray()
    {
        $calledClass = static::class;

        if(!array_key_exists($calledClass, self::$constantsCache)) {
            $reflection = new ReflectionClass($calledClass);
            self::$constantsCache[$calledClass] = $reflection->getConstants();
        }

        return self::$constantsCache[$calledClass];
    }
}
