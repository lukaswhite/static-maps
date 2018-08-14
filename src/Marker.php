<?php namespace Lukaswhite\StaticMaps;

/**
 * Class Marker
 *
 * @package Lukaswhite\StaticMaps
 */
class Marker extends MarkerPrototype
{
    /**
     * The latitude of the marker
     *
     * @var float
     */
    protected $lat;

    /**
     * The longitude of the marker
     *
     * @var float
     */
    protected $lng;

    /**
     * The X co-ordinate of the marker, in relation to the map image
     *
     * @var integer
     */
    protected $x;

    /**
     * The Y co-ordinate of the marker, in relation to the map image
     *
     * @var integer
     */
    protected $y;

    /**
     * Marker constructor.
     *
     * @param LatLng $latLng
     */
    public function __construct( LatLng $latLng )
    {
        $this->lat = $latLng->getLatitude( );
        $this->lng = $latLng->getLongitude( );
    }

    /**
     * Set the latitude
     *
     * @param float $value
     * @return $this
     */
    public function setLatitude($value)
    {
        $this->lat = $value;
        return $this;
    }

    /**
     * Set the longitude
     *
     * @param float $value
     * @return $this
     */
    public function setLongitude($value)
    {
        $this->lng = $value;
        return $this;
    }

    /**
     * Set the co-ordinates
     *
     * Takes an array of lat, lng
     *
     * @param array $values
     * @return $this
     */
    public function setCoordinates(array $values)
    {
        $this->setLatitude($values[0]);
        $this->setLongitude($values[1]);
        return $this;
    }

    /**
     * Get the co-ordinates of this marker
     *
     * @return LatLng
     */
    public function getCoordinates( )
    {
        return new LatLng(
            $this->lat,
            $this->lng
        );
    }
}