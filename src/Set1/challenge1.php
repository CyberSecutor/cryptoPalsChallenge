<?php

/**
 * Convert hex to base64
 * The string:
 *
 * 49276d206b696c6c696e6720796f757220627261696e206c696b65206120706f69736f6e6f7573206d757368726f6f6d
 * Should produce:
 *
 * SSdtIGtpbGxpbmcgeW91ciBicmFpbiBsaWtlIGEgcG9pc29ub3VzIG11c2hyb29t
 * So go ahead and make that happen. You'll need to use this code for the rest of the exercises.
 *
 */

use CyberSecutor\Set1\Hex;

require_once '../../vendor/autoload.php';

$hexString = '49276d206b696c6c696e6720796f757220627261696e206c696b65206120706f69736f6e6f7573206d757368726f6f6d';
$string = '22abzsaa';

$hexO = new Hex();
$hex = $hexO->fromString($string);
echo decbin(ord($string[0]));
echo PHP_EOL;
echo hex2bin($hex);
echo PHP_EOL;
echo var_dump($hexO->toString($hexString));
