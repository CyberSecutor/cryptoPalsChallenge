<?php

namespace CyberSecutor\Set1;
use InvalidArgumentException;

/**
 * Encode and decode strings to hexadecimal encoding.
 */

class Hex {

    /**
     * List of possible chars in the encoding.
     *
     * @var string $charlist
     */
    private static $charlist = '0123456789ABCDEF';

    /**
     * Convert a decimal number to a hexadecimal character.
     *
     * @param int $num the number to convert to a character.
     * @return string
     * @throws InvalidArgumentException if the decimal number is 0 > $num > 15.
     */
    private static function toChar(int $num) :string
    {
        if ($num >= 15) {
            throw new InvalidArgumentException("We need an integer between 0 and 15. We got " . var_export($num, true));
        }

        return static::$charlist[$num];
    }

    /**
     * Convert a decimal number to a hexadecimal character.
     *
     * @param string $char A hex chracter to be converted to value.
     * @return int
     * @throws InvalidArgumentException If the character is not valid.
     */
    private static function hexCharToValue(string $char) :int
    {
        $char = strtoupper($char);

        if (strlen($char) !== 1) {
            throw new InvalidArgumentException("We must only get one character. We got #" . strlen($char));
        }

        if (!in_array($char, str_split(static::$charlist), true)) {
            throw new InvalidArgumentException("We need a valid hex character. We got " . var_export($char, true));
        }

        $pos = strpos(static::$charlist, $char);

        if (!is_integer($pos)) {
            throw new InvalidArgumentException("Character not found in possible hex characters. We got " . var_export($char, true));
        }

        return $pos;
    }

    /**
     * Convert a string to hexadecimal value.
     *
     * @param string $string The string to convert.
     * @return string the hexadecimal string.
     */
    public function fromString(string $string) :string
    {
        $bin = $this->stringToBin($string);
        $hex = $this->binToHex($bin);

        return $hex;
    }

    /**
     * Convert a hexadecimal sequence to string.
     *
     * This might have nasty side effects if its not a string that's encoded in the hex.
     *
     * @param string $hex The hexadecimal sequence.
     * @return string
     */
    public function toString(string $hex) :string
    {
        $string = '';

        $bin = $this->hexToBin($hex);

        return $string;
    }

    /**
     * Convert the string to a binary representation.
     *
     * @param string $string The string to convert
     * @return int the binary string.
     */
    private function stringToBin(string $string) :int
    {
        $bin = 0;
        $strLength = strlen($string);
        $string = strrev($string);

        for ($pos = 0; $pos < $strLength; $pos++) {
            $bin |= ord($string[$pos]) << (8 * $pos);
        }

        return $bin;
    }

    /**
     * Convert a binary value to a hexadecimal string.
     *
     * @param int $bin the binary value as int
     * @return string The hexadecimal string
     */
    private function binToHex(int $bin) :string
    {
        $hex = '';

        // Shift a nibble (4 bits) off the right side.
        // 15 is a 4 bit mask (1111) to get the rightmost 4 bits from the bit sequence.
        $mask = 15;
        while (!empty($bin)) {
            $nibble = $bin & $mask;
            $hex = static::toChar($nibble) . $hex;

            // Shift off the 4 processed bits;
            $bin = $bin >> 4;
        }

        return $hex;
    }

    /**
     * Convert a hexadecimal sequence to binary.
     *
     * @param string $hex The hexadecimal sequence.
     * @return int[]
     */
    private function hexToBin($hex)
    {
        $hexLen = strlen($hex);
        $register = array(0);
        $rIndex = 0;
        $maxBitShift = (PHP_INT_SIZE === 4 ? 32 : 64) - 8;

        for ($index = 0; $index < $hexLen; $index++) {
            // To prevent using binary numbers larger then 32 bit we must divide them.
            // On 64bit system we could use 64 bit numbers but to keep it safe let's stick to 32.
            // Since we are pushing 4 bits on the stack, the border is at 28 bits (index 0-27).
            // This means we can have a max of 6 iterations before we go out of bounds.
            // 6*4 + 4 = 28
            // 7*4 + 4 = 32 which would mean 33 bits (zero is inclusive)
            $bitShift = 4 * ($index - (($maxBitShift / 4) * $rIndex));

            if ($bitShift >= $maxBitShift) {
                $rIndex++;
                $register[$rIndex] = 0;
                $bitShift = 0;
            }

            $register[$rIndex] |= static::hexCharToValue($hex[$index]) << $bitShift;
        }

        return $register;
    }

    /**
     * Convert a single ordinal value to a hexadecimal value.
     *
     * @param int $ord The ordinal value.
     * @return string The corresponding hexadecimal characters.
     */
    private static function ordinalToHex($ord) {
        $mod = $ord % 16;

        if (($ord - $mod) === 0) {
            return self::toChar($ord);
        }

        return self::ordinalToHex(($ord - $mod) / 16 ) . toChar($mod);
    }

    private function hexToDec($string) {

    }


}