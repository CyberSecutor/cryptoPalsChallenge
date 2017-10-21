<?php

/**
 * Convert hex to base64
 * The string:
 *
 * 49276d206b696c6c696e6720796f757220627261696e206c696b65206120706f69736f6e6f7573206d757368726f6f6d
 * Should produce:
 *
 * SSdtIGtpbGxpbmcgeW91ciBicmFpbiBsaWtlIGEgcG9pc29ub3VzIG11c2hyb29te
 * So go ahead and make that happen. You'll need to use this code for the rest of the exercises.
 *
 */

use CyberSecutor\Set1\Hex;

require_once '../../vendor/autoload.php';

$hexString = '49276d206b696c6c696e6720796f757220627261696e206c696b65206120706f69736f6e6f7573206d757368726f6f6d';
$string = hex2bin($hexString);

echo $string . PHP_EOL;

$hexObj = new Hex();
$hex = $hexObj->fromString($string);
echo $hex . PHP_EOL;

$myString = $hexObj->toString($hex);
echo $myString . PHP_EOL;


//echo PHP_EOL;
//echo hex2bin($hex);
//echo PHP_EOL;
//echo var_dump($hexO->toString($hex));
