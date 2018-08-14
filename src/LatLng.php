<?php namespace Lukaswhite\StaticMaps;

/**
 * Class LatLng
 *
 * A lat (latitude) / lng (longitude) pair
 *
 * @package Lukaswhite\StaticMaps
 */
class LatLng implements \Serializable, \JsonSerializable
{
    /**
     * The latitude
     *
     * @var float
     */
    protected $lat;

    /**
     * The longitude
     *
     * @var float
     */
    protected $lng;

    /**
     * Marker constructor.
     *
     * @param float $lat
     * @param float $lng
     */
    public function __construct( $lat = null, $lng = null )
    {
        $this->lat = $lat;
        $this->lng = $lng;
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
     * Get the latitude
     *
     * @return float
     */
    public function getLatitude( )
    {
        return $this->lat;
    }

    /**
     * Get the longitude
     *
     * @return float
     */
    public function getLongitude( )
    {
        return $this->lng;
    }

    /**
     * Determine whether this lat/lng pair is valid.
     *
     * @return bool
     */
    public function isValid( )
    {
        return (
            $this->lat >= -90 &&
            $this->lat <= 90 &&
            $this->lng >= -180 &&
            $this->lng <= 180
        );
    }

    /**
     * Serialize this lat/lng pair to JSON
     *
     * @return mixed|string
     */
    public function jsonSerialize( )
    {
        return [
            'lat'   =>  $this->lat,
            'lng'   =>  $this->lng
        ];
    }

    /**
     * Serialize this lat/lng pair
     *
     * @return mixed|string
     */
    public function serialize()
    {
        return sprintf(
            '%f,%f',
            $this->lat,
            $this->lng
        );
    }

    /**
     * Unserialize this lat/lng pair
     *
     * @param string $data
     */
    public function unserialize( $data )
    {
        $parts = explode( ',', $data );
        $this->lat = $parts[ 0 ];
        $this->lng = $parts[ 1 ];
    }

    /**
     * Create a string representation of this lat/lng pair
     *
     * @return string
     */
    public function toString()
    {
        return $this->serialize( );
    }

    /**
     * Magic to string method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString( );
    }
}