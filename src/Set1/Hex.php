<?php

namespace CyberSecutor\Set1;

use InvalidArgumentException;

/**
 * Encode and decode strings to hexadecimal encoding.
 */
class Hex
{

    /**
     * List of possible chars in the encoding.
     *
     * @var string $charlist
     */
    private static $charlist = '0123456789abcdef';

    /**
     * Convert base 10 encoded binary to a decimal number.
     *
     * @param int $bin base 10 encoded binary number.
     * @return int Decimal number
     */
    private function binToDec(int $bin): int
    {
        $dec = 0;
        $pos = 0;

        while ($bin) {
            if ($pos === 0) {
                $dec = ($bin & 1);
            } else {
                $dec += pow(2, $pos) * ($bin & 1);
            }

            $bin = $bin >> 1;
            $pos++;
        }

        return $dec;
    }

    /**
     * Convert a binary value to a hexadecimal string.
     *
     * @param \GMP $bin the binary value as int
     * @return string The hexadecimal string
     */
    private function binToHex(\GMP $bin): string
    {
        $hex = '';

        // Shift a nibble (4 bits) off the right side.
        // 15 is a 4 bit mask (1111) to get the rightmost 4 bits from the bit sequence.
        $mask = 15;
        while ($bin > 0) {
            $nibble = $bin & $mask;
            $hex = static::toChar(gmp_intval($nibble)) . $hex;

            // Shift off the 4 processed bits.
            $bin = $bin >> 4;
        }

        return $hex;
    }

    /**
     * Convert an array of binary values back to string.
     *
     * @param \GMP $bin
     * @return string
     */
    private function binToString(\GMP $bin): string
    {
        $string = '';

        $mask = 255; // Will give eight 1's as an eight bit mask.
        while ($bin > 0) {
            $byte = $bin & $mask;
            $string .= chr($this->binToDec(gmp_intval($byte)));
            $bin = $bin >> 8;
        }

        return strrev($string);
    }

    /**
     * Convert a string to hexadecimal value.
     *
     * @param string $string The string to convert.
     * @return string the hexadecimal string.
     */
    public function fromString(string $string): string
    {
        $bin = $this->stringToBin($string);
        $hex = $this->binToHex($bin);

        return $hex;
    }

    /**
     * Convert a decimal number to a hexadecimal character.
     *
     * @param string $char A hex chracter to be converted to value.
     * @return int
     * @throws InvalidArgumentException If the character is not valid.
     */
    private static function hexCharToValue(string $char): int
    {
        $char = strtolower($char);

        if (strlen($char) !== 1) {
            throw new InvalidArgumentException("We must only get one character. We got #" . strlen($char));
        }

        if (!in_array($char, str_split(static::$charlist), true)) {
            throw new InvalidArgumentException("We need a valid hex character. We got " . var_export($char, true));
        }

        $pos = strpos(static::$charlist, $char);

        return $pos;
    }

    /**
     * Convert a hexadecimal sequence to binary.
     *
     * @param string $hex The hexadecimal sequence.
     * @return \GMP
     */
    private function hexToBinArray(string $hex): \GMP
    {
        $hexLen = strlen($hex);
        $hex = strrev($hex);
        $bin = gmp_init(0, 10);
        $bitShift = 4;

        for ($index = 0; $index < $hexLen; $index++) {
            $val = gmp_init(static::hexCharToValue($hex[$index]), 10);
            $bin |= $val << ($bitShift * $index);
        }

        return $bin;
    }

    /**
     * Convert the string to a binary representation.
     *
     * @param string $string The string to convert
     * @return \GMP the binary string.
     */
    private function stringToBin(string $string): \GMP
    {
        $bin = gmp_init(0, 10);
        $strLength = strlen($string);

        for ($pos = 0; $pos < $strLength; $pos++) {
            $ord = gmp_init(ord($string[$pos]), 10);
            // Reserve the max number of bits in memory and augment that.
            $bin |= ($ord << (8 * ($strLength - 1 - $pos)));
        }

        return $bin;
    }

    /**
     * Convert a decimal number to a hexadecimal character.
     *
     * @param int $num the number to convert to a character.
     * @return string
     * @throws InvalidArgumentException if the decimal number is 0 > $num > 15.
     */
    private static function toChar(int $num): string
    {
        if ($num > 15) {
            throw new InvalidArgumentException("We need an integer between 0 and 15. We got " . var_export($num, true));
        }

        return static::$charlist[$num];
    }

    /**
     * Convert a hexadecimal sequence to string.
     *
     * This might have nasty side effects if its not a string that's encoded in the hex.
     *
     * @param string $hex The hexadecimal sequence.
     * @return string
     */
    public function toString(string $hex): string
    {
        $bin = $this->hexToBinArray($hex);
        $string = $this->binToString($bin);

        return $string;
    }
}