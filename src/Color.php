<?php namespace Lukaswhite\StaticMaps;

use InvalidArgumentException;

/**
 * Class Color
 *
 * Represents a color.
 *
 * @package Lukaswhite\StaticMaps
 */
class Color implements \Serializable, \JsonSerializable
{
    /**
     * The red value
     *
     * @var int
     */
    protected $red;

    /**
     * The green value
     *
     * @var int
     */
    protected $green;

    /**
     * The blue value
     *
     * @var int
     */
    protected $blue;

    /**
     * The alpha value (i.e. opacity)
     *
     * @var int
     */
    protected $alpha;

    /**
     * Color constructor.
     *
     * @param int $red
     * @param int $green
     * @param int $blue
     * @param int $alpha
     */
    public function __construct( $red, $green, $blue, $alpha = null )
    {
        $this->red      =   $red;
        $this->green    =   $green;
        $this->blue     =   $blue;
        $this->alpha    =   $alpha;
    }

    /**
     * Create a new instance from a hexadecimal string
     *
     * @param string $hex
     * @param int $alpha
     * @return self
     */
    public static function createFromHex( $hex, $alpha = null )
    {
        $rgb = Math::hex2RGB( $hex );
        return new self(
            $rgb[ 'red' ],
            $rgb[ 'green' ],
            $rgb[ 'blue' ],
            $alpha
        );
    }

    /**
     * Allocate this color
     *
     * @param resource $image
     * @return int
     */
    public function allocate( $image )
    {
        if ( ! $this->alpha )
        {
            return imagecolorallocate(
                $image,
                $this->red,
                $this->green,
                $this->blue
            );
        }

        return imagecolorallocatealpha(
            $image,
            $this->red,
            $this->green,
            $this->blue,
            $this->alpha
        );

    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return mixed|string
     */
    public function jsonSerialize( )
    {
        $data = [
            'red'       =>  $this->red,
            'green'     =>  $this->green,
            'blue'      =>  $this->blue,
        ];

        if ( $this->alpha ) {
            $data[ 'alpha' ] = $this->alpha;
        }

        return $data;
    }

    /**
     * String representation of object
     *
     * @return string
     */
    public function serialize()
    {
        $data = sprintf(
            '%d|%d|%d',
            $this->red,
            $this->green,
            $this->blue
        );

        if ( ! $this->alpha ) {
            return $data;
        }

        return sprintf( '%s|%d', $data, $this->alpha );
    }

    /**
     * Constructs the object
     *
     * @param string $data
     */
    public function unserialize( $data )
    {
        $parts = explode( '|', $data );
        $this->red = $parts[ 0 ];
        $this->green = $parts[ 1 ];
        $this->blue = $parts[ 2 ];
        if ( isset( $parts[ 3 ] ) ) {
            $this->alpha = $parts[ 3 ];
        }
    }

    /**
     * @return int
     */
    public function getRed()
    {
        return $this->red;
    }

    /**
     * @param int $red
     * @return Color
     */
    public function setRed($red)
    {
        $this->red = $red;
        return $this;
    }

    /**
     * @return int
     */
    public function getGreen()
    {
        return $this->green;
    }

    /**
     * @param int $green
     * @return Color
     */
    public function setGreen($green)
    {
        $this->green = $green;
        return $this;
    }

    /**
     * @return int
     */
    public function getBlue()
    {
        return $this->blue;
    }

    /**
     * @param int $blue
     * @return Color
     */
    public function setBlue($blue)
    {
        $this->blue = $blue;
        return $this;
    }

    /**
     * @return int
     */
    public function getAlpha()
    {
        return $this->alpha;
    }

    /**
     * @param int $alpha
     * @return Color
     */
    public function setAlpha($alpha)
    {
        $this->alpha = $alpha;
        return $this;
    }


}