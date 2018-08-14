<?php namespace Lukaswhite\StaticMaps;

use Lukaswhite\StaticMaps\Exception\InvalidRequestException;

/**
 * Class RequestParser
 *
 * This simple class is used to take an array of (typically) GET parameters, and return
 * the appropriate map. This allows you to build dynamic maps over HTTP service.
 *
 * e.g.
 *
 * ?center=53.48095000,-2.23743000&zoom=12&300x250
 *
 * This equates to:
 *  lat: 53.48095000
 *  lng: -2.23743000
 *  zoom: 12
 *  width: 300
 *  height: 250
 *
 * Note that you need to pass the parameters as an array; ideally using something like Symfony or
 * Laravel's Request class, or otherwise you could simply pass the $_GET super variable.
 *
 * @package Lukaswhite\StaticMaps
 */
class RequestParser
{

    /**
     * An associative array of parameters; usually GET params.
     *
     * @var array
     */
    protected $params;

    /**
     * RequestParser constructor.
     *
     * @param array $params
     */
    public function __construct( array $params )
    {
        $this->params = $params;
    }

    /**
     * Get the map
     *
     * @return Map
     * @throws InvalidRequestException
     */
    public function getMap( )
    {
        $map = new Map( );

        // Get the center (lat,lng)
        if ( isset( $this->params[ 'center' ] ) ) {
            $valid = preg_match(
                '^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$^',
                $this->params[ 'center' ], $matches
            );
            if ( ! $valid || count( $matches ) !== 5 ) {
                throw new InvalidRequestException( 'The center parameter should be in the format lat,lng' );
            }
            $map->setCenter( new LatLng( floatval( $matches[ 1 ] ), floatval( $matches[ 3 ] ) ) );
        } else {
            throw new InvalidRequestException( 'The center parameter is required' );
        }

        // Get the zoom from the parameters, if present
        if ( isset( $this->params[ 'zoom' ] ) ) {
            $map->setZoom( intval( $this->params[ 'zoom' ] ) );
        }

        // If the map type has been specified, add it now
        if ( isset( $this->params[ 'maptype' ] ) ) {
            $map->setMapType( $this->params[ 'maptype' ] );
        }

        // Get the size (widthxheight)
        if ( isset( $this->params[ 'size' ] ) ) {
            $valid = preg_match( '^(\d+)x(\d+)^', $this->params[ 'size' ], $matches );
            if ( ! $valid || count( $matches ) !== 3 ) {
                throw new InvalidRequestException( 'The size parameter should be in the format widthxheight; e.g. 200x200' );
            }
            $map->setWidth( intval( $matches[ 1 ] ) )
                ->setHeight( intval( $matches[ 2 ] ) );
        }

        return $map;

        /**
        // get size from GET paramter
        if ($_GET['size']) {
            list($this->width, $this->height) = explode('x', $_GET['size']);
            $this->width = intval($this->width);
            if ($this->width > $this->maxWidth) $this->width = $this->maxWidth;
            $this->height = intval($this->height);
            if ($this->height > $this->maxHeight) $this->height = $this->maxHeight;
        }
        if (!empty($_GET['markers'])) {
            $markers = explode('|', $_GET['markers']);
            foreach ($markers as $marker) {
                list($markerLat, $markerLon, $markerType) = explode(',', $marker);
                $markerLat = floatval($markerLat);
                $markerLon = floatval($markerLon);
                $markerType = basename($markerType);
                $this->markers[] = array('lat' => $markerLat, 'lon' => $markerLon, 'type' => $markerType);
            }

        }
         **/
    }

}