<?php namespace Lukaswhite\StaticMaps;

use InvalidArgumentException;

/**
 * Class Point
 *
 * This class simply represents a point on a map image; i.e. it encapulates the X and Y
 * co-ordinates.
 *
 * @package Lukaswhite\StaticMaps
 */
class Point
{
    /**
     * The X position
     *
     * @var int
     */
    protected $x;

    /**
     * The Y position
     *
     * @var int
     */
    protected $y;

    /**
     * Marker constructor.
     *
     * @param int $x
     * @param int $y
     */
    public function __construct( $x = null, $y = null )
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * Get the X co-ordinate
     *
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set the X co-ordinate
     *
     * @param int $x
     * @return Point
     */
    public function setX(int $x): Point
    {
        $this->x = $x;
        return $this;
    }

    /**
     * Get the Y co-ordinate
     *
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Set the Y co-ordinate
     *
     * @param int $y
     * @return Point
     */
    public function setY(int $y): Point
    {
        $this->y = $y;
        return $this;
    }


}