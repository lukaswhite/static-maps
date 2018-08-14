<?php namespace Lukaswhite\StaticMaps;

use InvalidArgumentException;

/**
 * Class Math
 *
 * Asimple math library
 *
 * @package Lukaswhite\StaticMaps
 */
class Math
{
    /**
     * The radius of the Earth
     *
     * @var int
     */
    const R = 6378137;

    /**
     * Convert miles to meters.
     *
     * @param float $value
     * @return int
     */
    public static function milesToMeters( $value )
    {
        return intval( $value * 1609.34 );
    }

    /**
     * Convert a distance in kilometers into meters
     *
     * @param $value
     * @return int
     */
    public static function kilometersToMeters( $value )
    {
        return intval( $value * 1000 );
    }

    /**
     * Convert a hexidecimal value to RGB
     *
     * @param string $hexStr
     * @return array
     */
    public static function hex2RGB( $hexStr )
    {
        $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
        $rgbArray = array();
        if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
            $colorVal = hexdec($hexStr);
            $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
            $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
            $rgbArray['blue'] = 0xFF & $colorVal;
        } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
            $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        }
        /**else {
            return false; //Invalid hex color code
        }**/
        return $rgbArray;
    }

    /**
     * Calculate the distance between two sets of co-ordinates
     *
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     * @return int
     */
    public static function calculateDistance( $x1, $y1, $x2, $y2 )
    {
        return floor( sqrt( pow( ( $x2 - $x1 ), 2 ) + pow( ( $y2 - $y1 ), 2 ) ) );
    }
}