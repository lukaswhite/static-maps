<?php namespace Lukaswhite\StaticMaps;

use InvalidArgumentException;
use Lukaswhite\StaticMaps\Exception\CacheDirectoryNotFoundException;
use Lukaswhite\StaticMaps\Exception\CacheDirectoryNotWriteableException;
use Lukaswhite\StaticMaps\LatLng;

/**
 * Class Map
 *
 * This class is used to generate a static map.
 *
 * @package Lukaswhite\StaticMaps
 */
class Map
{
    /**
     * The maximum width that a map can be rendered at.
     *
     * @var int
     */
    protected $maxWidth = 1024;

    /**
     * The maximum height that a map can be rendered at.
     *
     * @var int
     */
    protected $maxHeight = 1024;

    /**
     * The size (width and height, since it's square of a tile, that
     * makes up the map
     *
     * @var int
     */
    protected $tileSize = 256;

    /**
     * Class constants that represent the source of tiles
     */
    const TILE_SOURCE_MAPNIK            =   'mapnik';
    const TILE_SOURCE_OSMARENDERER      =   'osmarenderer';
    const TILE_SOURCE_CYCLE             =   'cycle';

    /**
     * This defines the available sources for the tiles.
     *
     * @var array
     */
    protected $tileSrcUrl = [
        'mapnik'            =>  'http://tile.openstreetmap.org/{Z}/{X}/{Y}.png',
        'osmarenderer'      =>  'http://otile1.mqcdn.com/tiles/1.0.0/osm/{Z}/{X}/{Y}.png',
        'cycle'             =>  'http://a.tile.opencyclemap.org/cycle/{Z}/{X}/{Y}.png',
    ];

    /**
     * The default tile source
     *
     * @var string
     */
    protected $tileDefaultSrc = 'mapnik';

    /**
     * The filepath of the directory that holds the marker images
     *
     * @var string
     */
    protected $markerBaseDir = __DIR__.'/../../../resources/images/markers';

    /**
     * The path to the copyright image.
     *
     * @var string
     */
    protected $copyrightImage = __DIR__.'/../../../resources/images/copyright.png';

    /**
     * Whether to cache the map tile images
     *
     * @var bool
     */
    protected $useTileCache = false;

    /**
     * The filepath of the cache directory for storing tiles
     *
     * @var string
     */
    protected $tileCacheBaseDir = '../cache/tiles';

    /**
     * Whether to cache the map images
     *
     * @var bool
     */
    protected $useMapCache = false;

    /**
     * The filepath of the cache directory for storing maps
     *
     * @var string
     */
    protected $mapCacheBaseDir = '../cache/maps';

    /**
     * The map cache ID
     *
     * @var string
     */
    protected $mapCacheID = '';

    /**
     * The map cache filename
     *
     * @var string
     */
    protected $mapCacheFile = '';

    /**
     * The map cache extension
     *
     * @var string
     */
    protected $mapCacheExtension = 'png';

    /**
     * The zoom level
     *
     * @var int
     */
    protected $zoom;

    /**
     * The center of the map (i.e. the lat/lng pair)
     *
     * @var LatLng
     */
    protected $center;

    /**
     * The latitude of the center of the map
     *
     * @var float
     */
    protected $lat;

    /**
     * The longitude of the center of the map
     *
     * @var float
     */
    protected $lng;

    /**
     * The width of the map, in pixels
     *
     * @var int
     */
    protected $width;

    /**
     * The height of the map, in pixels
     *
     * @var int
     */
    protected $height;

    /**
     * Th emarkers
     *
     * @var array
     */
    protected $markers;

    /**
     * A circle to include in the map
     *
     * @var array
     */
    protected $circle;

    /**
     * The image being created
     *
     * @var resource
     */
    protected $image;

    /**
     * The type of map
     *
     * @var string
     */
    protected $maptype = self::TILE_SOURCE_MAPNIK;

    /**
     * The point at the center of the map
     *
     * @var Point
     */
    protected $centerPoint;

    /**
     * @var int The X offset
     */
    protected $offsetX;

    /**
     * @var int The Y offset
     */
    protected $offsetY;

    /**
     * Map constructor.
     */
    public function __construct( )
    {
        $this->zoom         =   12;
        $this->lat          =   0;
        $this->lng          =   0;
        $this->center       =   new LatLng( 0, 0 );
        $this->width        =   500;
        $this->height       =   350;
        $this->markers      =   [ ];
        $this->maptype      =   $this->tileDefaultSrc;
    }

    /**
     * Set the zoom level
     *
     * @param int $value
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setZoom( int $value )
    {
        if ( $value < 0 || $value > 18 ) {
            throw new InvalidArgumentException('The zoom level must be between 0 and 18' );
        }
        $this->zoom = $value;
        return $this;
    }

    /**
     * Set the zoom level to its minimum level
     *
     * @return $this
     */
    public function minimumZoom( )
    {
        return $this->setZoom( 0 );
    }

    /**
     * Set the zoom level to its maximum level
     *
     * @return $this
     */
    public function maximumZoom( )
    {
        return $this->setZoom( 18 );
    }

    /**
     * Set the latitude
     *
     * @param float $value
     * @return $this
     */
    public function setLatitude( $value )
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
    public function setLongitude( $value )
    {
        $this->lng = $value;
        return $this;
    }

    /**
     * Set the co-ordinates of the center of the map
     *
     * @param LatLng $center
     * @return $this
     */
    public function setCenter( LatLng $center )
    {
        $this->setLatitude( $center->getLatitude( ) );
        $this->setLongitude( $center->getLongitude( ) );
        $this->center = $center;
        return $this;
    }

    /**
     * Set the width, in pixels
     *
     * @param int $value
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function setWidth( $value )
    {
        if ( $value > $this->maxWidth ) {
            throw new InvalidArgumentException(
                sprintf( 'The width should be no greater than %d', $this->maxWidth )
            );
        }
        $this->width = intval( $value );
        return $this;
    }

    /**
     * Set the height, in pixels
     *
     * @param int $value
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function setHeight( $value )
    {
        if ( $value > $this->maxHeight ) {
            throw new InvalidArgumentException(
                sprintf( 'The height should be no greater than %d', $this->maxHeight )
            );
        }
        $this->height = intval( $value );
        return $this;
    }

    /**
     * Set the map type
     *
     * @param string $value
     * @return $this
     */
    public function setMapType( $value )
    {
        $this->maptype = $value;
        return $this;
    }

    /**
     * Set the path to the copyright image. Setting this to null
     * means a copyright won't be added; this is useful if, for example, you add the
     * copyright notice as an overlaid element or beneath the image.
     *
     * Refer to the copyright requirements here: https://www.openstreetmap.org/copyright
     *
     * @param string $filepath
     * @return $this
     */
    public function setCopyrightImage( $filepath )
    {
        $this->copyrightImage = $filepath;
        return $this;
    }

    /**
     * Indicate that tiles should be cached (or not)
     *
     * @param bool $cache
     * @return $this
     */
    public function cacheTiles( $cache = true )
    {
        $this->useTileCache = $cache;
        return $this;
    }

    /**
     * Indicate that maps should be cached (or not)
     *
     * @param bool $cache
     * @return $this
     */
    public function cacheMaps( $cache = true )
    {
        $this->useMapCache = $cache;
        return $this;
    }

    /**
     * Set the cache directory
     *
     * @param string $path
     * @return $this
     * @throws CacheDirectoryNotFoundException
     * @throws CacheDirectoryNotWriteableException
     */
    public function setCacheDirectory( $path )
    {
        // Ensure that it exists, and is a directory
        if ( ! file_exists( $path ) || ! is_dir( $path ) ) {
            throw new CacheDirectoryNotFoundException( );
        }

        // Check that the directory is writeable
        if ( ! is_writable( $path ) ) {
            throw new CacheDirectoryNotWriteableException( );
        }

        $this->mapCacheBaseDir = $path;
        return $this;
    }

    public function parseParams()
    {
        global $_GET;

        if (!empty($_GET['show'])) {
            $this->parseOjwParams();
        }
        else {
            $this->parseLiteParams();
        }
    }
    
    /**
     * Given a latitude, calculate the position within a tile.
     *
     * @param float $lat
     * @param int $zoom
     * @return float|int
     */
    public function latToTile($lat, $zoom)
    {
        return ( 1 - log(
            tan( $lat * pi( ) / 180 ) + 1 / cos( $lat * pi( ) / 180 ) ) / pi( ) )
            / 2 * pow( 2, $zoom );
    }

    /**
     * Given a longitude, calculate the position within a tile.
     *
     * @param float $lng
     * @param int $zoom
     * @return float|int
     */
    public function lngToTile( $lng, $zoom )
    {
        return ( ( $lng + 180 ) / 360 ) * pow( 2, $zoom );
    }

    /**
     * Convert a lat/lng pair to a point
     *
     * @param \Lukaswhite\StaticMaps\LatLng $latLng
     * @return Point
     */
    protected function latLngToPoint( LatLng $latLng, $zoom )
    {
        /**
        return new Point(
            $this->latToTile( $latLng->getLatitude( ), $zoom ),
            $this->lngToTile( $latLng->getLongitude( ), $zoom )
        );
         * */

        return new Point(
            floor(( $this->width / 2 ) - $this->tileSize * ( $this->centerPoint->getX( ) - $this->lngToTile( $latLng->getLatitude( ), $this->zoom) ) ),
            floor(( $this->height / 2 ) - $this->tileSize * ($this->centerPoint->getY( ) - $this->latToTile( $latLng->getLongitude( ), $this->zoom ) ) )
        );
        //$destX =
        //$destY = floor(($this->height / 2) - $this->tileSize * ($this->centerPoint->getY( ) - $this->latToTile($markerLat, $this->zoom)));
    }

    /**
     * Calculate the point (x,y) for the center of the map.
     *
     * @return Point
     */
    protected function calculateCenterPoint( )
    {
        return $this->latLngToPoint(
            $this->center,
            $this->zoom
        );
    }

    /**
     * Initialize the co-ordinates
     */
    public function initCoords()
    {
        // Calculate the position of the center of the map, which is based on the lat/lng and
        // the zoom level
        $this->centerPoint = new Point(
            $this->lngToTile( $this->lng, $this->zoom ),
            $this->latToTile( $this->lat, $this->zoom )
        );

        // Now calculate the offsets
        $this->offsetX = floor(
            ( floor( $this->centerPoint->getX( ) ) - $this->centerPoint->getX( ) ) *
            $this->tileSize
        );

        $this->offsetY = floor(
            ( floor( $this->centerPoint->getY( ) ) - $this->centerPoint->getY( ) ) *
            $this->tileSize
        );

    }

    /**
     * Create the base map; this first creates an image of the appropriate size, then
     * builds the map itself from one or more tiles.
     *
     * @return void
     */
    public function createBaseMap()
    {
        // Create the image with the appropriate dimensions
        $this->image = imagecreatetruecolor( $this->width, $this->height );

        // Calculate the positioning information for the tiles that are going to
        // make up this map
        $startX = floor($this->centerPoint->getX( ) - ($this->width / $this->tileSize) / 2);
        $startY = floor($this->centerPoint->getY( ) - ($this->height / $this->tileSize) / 2);
        $endX = ceil($this->centerPoint->getX( ) + ($this->width / $this->tileSize) / 2);
        $endY = ceil($this->centerPoint->getY( ) + ($this->height / $this->tileSize) / 2);
        $this->offsetX = -floor(($this->centerPoint->getX( ) - floor($this->centerPoint->getX( ))) * $this->tileSize);
        $this->offsetY = -floor(($this->centerPoint->getY( ) - floor($this->centerPoint->getY( ))) * $this->tileSize);
        $this->offsetX += floor($this->width / 2);
        $this->offsetY += floor($this->height / 2);
        $this->offsetX += floor($startX - floor($this->centerPoint->getX( ))) * $this->tileSize;
        $this->offsetY += floor($startY - floor($this->centerPoint->getY( ))) * $this->tileSize;

        // Now start iterating through the required tiles
        for ($x = $startX; $x <= $endX; $x++) {

            for ($y = $startY; $y <= $endY; $y++) {

                // Generate the URL to the tile, which comprises the zoom, lat/lng and map type
                $url = str_replace(
                    [ '{Z}', '{X}', '{Y}' ],
                    [ $this->zoom, $x, $y ],
                    $this->tileSrcUrl[ $this->maptype ]
                );

                // Fetch the tile, either from the remote source or from the cache
                $tileData = $this->fetchTile($url);

                if ( $tileData ) {
                    $tileImage = imagecreatefromstring( $tileData );
                } else {
                    $tileImage = imagecreate($this->tileSize, $this->tileSize);
                    $color = imagecolorallocate($tileImage, 255, 255, 255);
                    @imagestring($tileImage, 1, 127, 127, 'err', $color);
                }

                // Calculate the point at which to insert this tile into the image
                $destX = ( $x - $startX ) * $this->tileSize + $this->offsetX;
                $destY = ( $y - $startY ) * $this->tileSize + $this->offsetY;

                // Inject the tile into the map image
                imagecopy(
                    $this->image,
                    $tileImage,
                    $destX, $destY,
                    0,
                    0,
                    $this->tileSize,
                    $this->tileSize // Tiles are always square, so width == height == tile size
                );
            }
        }
    }

    /**
     * Place the markers on the map.
     *
     * @return void
     */
    public function placeMarkers( )
    {
        foreach ( $this->markers as $marker ) {

            /** @var $marker Marker */

            // Calculate the position
            $destX = floor(
                ( $this->width / 2 ) - $this->tileSize * (
                    $this->centerPoint->getX( ) - $this->lngToTile(
                        $marker->getCoordinates()->getLongitude(),
                        $this->zoom
                    )
                ) );
            $destY = floor(
                ( $this->height / 2 ) - $this->tileSize * (
                    $this->centerPoint->getY( ) - $this->latToTile(
                        $marker->getCoordinates( )->getLatitude(),
                        $this->zoom
                    )
                )
            );

            // Get the image data
            $imgData = $marker->getImageData( );

            // Insert the marker into the map image
            imagecopy(
                $this->image,
                $imgData,
                $destX + intval( $marker->getOffsetX( ) ),
                $destY + intval( $marker->getOffsetY( ) ),
                0,
                0,
                imagesx( $imgData ),
                imagesy( $imgData )
            );
        }
    }

    /**
     * Draw a circle on the map. This shows a radius around a point, typically around the center.
     *
     * @param \Lukaswhite\StaticMaps\LatLng|null $latLng
     * @param float|int $radius
     * @param string $unit
     * @param Color $color
     */
    public function drawCircle( LatLng $latLng = null, $radius, $unit = 'mi', Color $color )
    {
        $this->circle = [
            'center'    =>  $latLng,
            'radius'    =>  $radius,
            'unit'      =>  $unit,
            'color'     =>  $color,
        ];

    }

    /**
     * Render a circle, which has been added using drawCircle(), on the map
     *
     * @return void
     */
    private function renderCircle( )
    {
        // Sanity check; if there's no circle to be rendered, simply exit
        if ( ! $this->circle ) {
            return;
        }

        // First, convert the lat/lng pair to a point on the map
        $point = $this->latLngToPoint( $this->circle[ 'center' ], $this->zoom );

        // If necessary, convert the radius into meters
        switch ( $this->circle[ 'unit' ] ) {
            case 'mi':
                $radius = Math::milesToMeters( $this->circle[ 'radius' ] );
                break;
            case 'km':
                $radius = Math::kilometersToMeters( $this->circle[ 'center' ] );
                break;
            default:
                $radius = $this->circle[ 'radius' ];
        }

        // Now calculate the radius as a number of pixels
        $radiusInPixels = $this->calculateRadius( $radius );

        // Finally, draw the circle
        imagefilledellipse (
            $this->image,
            floor( $this->width / 2 ),
            floor( $this->height / 2 ),
            $radiusInPixels,
            $radiusInPixels, // It's a circle, so width == height == radius
            $this->circle[ 'color' ]->allocate( $this->image )
        );

    }

    /**
     * Given the URL to a tile, return the filename, for the purposes of caching.
     *
     * @param string $url
     * @return string
     */
    public function tileUrlToFilename($url)
    {
        return sprintf(
            '%s/%s',
            $this->tileCacheBaseDir,
            str_replace( [ 'http://' ], '', $url )
        );

        //$this->tileCacheBaseDir . "/" . str_replace( [ 'http://' ], '', $url);
    }

    /**
     * Check to see if the tile represented by the specified URL is present in the tile cache
     *
     * @param string $url
     * @return bool|string
     */
    public function checkTileCache( $url )
    {
        $filename = $this->tileUrlToFilename( $url );

        if ( file_exists( $filename ) ) {
            return file_get_contents( $filename );
        }
    }

    /**
     * Check whether the map we're trying to build exists in the cache.
     *
     * @return bool
     */
    public function checkMapCache( )
    {
        // Generate a unique identifier for the map
        $this->mapCacheID = md5( $this->serializeParams( ) );

        // From that, derive a filename
        $filename = $this->mapCacheIDToFilename( );

        // Check whether a matching map can be found
        if ( file_exists( $filename ) ) {
            return true;
        }
    }

    /**
     * Serialize the parameters that define the map into a string; this is then used to define
     * a cache ID that uniquely represents it.
     *
     * @return string
     * @todo Incorporate circles
     * @todo Incorporate copyright notice
     */
    public function serializeParams()
    {
        return join("&", [
            $this->zoom,
            $this->lat,
            $this->lng,
            $this->width,
            $this->height,
            serialize( $this->markers ),
            $this->maptype
        ] );
    }

    /**
     * Given a cache ID for a map, return the name of the file used for caching.
     * @return string
     */
    public function mapCacheIDToFilename()
    {
        if ( ! $this->mapCacheFile ) {
            $this->mapCacheFile =
                sprintf(
                    '%s/%s/%d/cache_%s/%s/%s/%s',
                    $this->mapCacheBaseDir,
                    $this->maptype,
                    $this->zoom,
                    substr( $this->mapCacheID, 0, 2 ),
                    substr( $this->mapCacheID, 2, 2 ),
                    substr( $this->mapCacheID, 4 )
                );
                //$this->mapCacheBaseDir . "/" . $this->maptype . "/" . $this->zoom . "/cache_" . substr( $this->mapCacheID, 0, 2 ) . "/" . substr($this->mapCacheID, 2, 2) . "/" . substr($this->mapCacheID, 4);
        }

        // Append the extension, and return
        return sprintf(
            '%s.%s',
            $this->mapCacheFile,
            $this->mapCacheExtension
        );
    }

    /**
     * Recursively create a directory
     *
     * @param string $pathname
     * @param int $mode
     * @return bool
     */
    public function mkdir_recursive($pathname, $mode)
    {
        is_dir( dirname( $pathname ) ) || $this->mkdir_recursive( dirname( $pathname ), $mode );
        return is_dir( $pathname ) || @mkdir( $pathname, $mode );
    }

    /**
     * Write a tile to the cache
     *
     * @param string $url
     * @param mixed $data
     */
    public function writeTileToCache($url, $data)
    {
        $filename = $this->tileUrlToFilename( $url );
        $this->mkdir_recursive( dirname( $filename ), 0777 );
        file_put_contents( $filename, $data );
    }

    /**
     * Fetch a tile from the appropriate source
     *
     * @param $url
     * @return bool|mixed|string
     *
     * @todo Refactor to use Guzzle
     */
    public function fetchTile($url)
    {
        // If caching is enabled, check the cache first
        if ( $this->useTileCache && ( $cached = $this->checkTileCache( $url ) ) ) {
            return $cached;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0");
        curl_setopt($ch, CURLOPT_URL, $url);
        $tile = curl_exec($ch);
        curl_close($ch);
        if ($tile && $this->useTileCache) {
            $this->writeTileToCache($url, $tile);
        }
        return $tile;

    }

    /**
     * Add a copyright notice to the map
     */
    public function addCopyrightNotice()
    {
        $logoImg = imagecreatefrompng( $this->copyrightImage );

        imagecopy(
            $this->image,
            $logoImg,
            imagesx( $this->image ) - imagesx( $logoImg ),
            imagesy( $this->image ) - imagesy( $logoImg ),
            0,
            0,
            imagesx($logoImg), imagesy($logoImg));
    }

    /**
     * Given a distance in meters, this method calculates the number of pixels that represents that
     * on this map. This can be used, for example, to overlay a circle onto the map.
     *
     * @param int $m
     * @return int
     */
    public function calculateRadius( $m )
    {
        // offsets in meters
        $dn = $m;
        $de = $m;

        // Coordinate offsets in radians
        $dLat = $dn / Math::R;
        $dLng = $de / ( Math::R * cos(pi( ) * $this->lat / 180 ) );

        // Now calculate the lat/lng of this new co-ordinate
        $toLatLng = new LatLng(
            $this->lat + $dLat * 180 / pi( ),
            $this->lng + $dLng * 180 / pi( )
        );

        //dd( $toLatLng);

        $centerX = floor( $this->width / 2 );
        $centerY = floor( $this->height / 2 );

        // Calculate the position
        $x = floor(
            ( $this->width / 2 ) - $this->tileSize *
            ( $this->centerPoint->getX( ) - $this->lngToTile( $toLatLng->getLongitude( ), $this->zoom ) )
        );
        $y = floor(
            ( $this->height / 2 ) - $this->tileSize *
            ( $this->centerPoint->getY( ) - $this->latToTile( $toLatLng->getLatitude( ), $this->zoom ) )
        );

        $a = new Point(
            $this->lngToTile($this->lng, $this->zoom),
            $this->latToTile($this->lat, $this->zoom)
        );



        //dd( $a );

        $b = $this->latLngToPoint( $toLatLng, $this->zoom );

        //dd( $b );
        //return floor( sqrt( pow( ( $x - $centerX ), 2 ) + pow( ( $centerY - $y ), 2 ) ) );

        return Math::calculateDistance(
            $centerX,
            $y,
            $x,
            $centerY
        );

        return floor( sqrt( pow( ( $x - $centerX ), 2 ) + pow( ( $centerY - $y ), 2 ) ) );

        return floor( sqrt( pow( ( $x - $centerX ), 2 ) - pow( ( $centerY - $y ), 2 ) ) );

        //$y = floor(($this->height / 2) - $this->tileSize * ($this->centerPoint->getY( ) - $this->latToTile($toLatLng->getLatitude(), $this->zoom)));
        dd(( $y - $centerY ));
        return ( ( $y - $centerY ) * -1 );
        return ( $x - $centerX );

        //dd( $x );

        //return ( floor( $this->width / 2 ) ) - $x;
        //dd( $toLatLng );
        $to = $this->latLngToPoint( new LatLng(
            $this->lat + $dLat * 180 / pi( ),
            $this->lng + $dLng * 180 / pi( )
        ), $this->zoom );

dd( $to );
        // Get the center of the map, as a point
        $from = $this->calculateCenterPoint( );
//dd($from);
        //dd( $to );

        // The radius then is simply the X co-ordinate of the "to" point, minus the X co-ordinate
        // of the center (because it's a circle, we could use Y for the same result)
        return intval( $to->getY( ) - $from->getY( ) );
    }

    /**
     * Set the headers
     *
     * @return void
     */
    public function setHeaders()
    {
        header('Content-Type: image/png');
        $expires = 60 * 60 * 24 * 14;
        header("Pragma: public");
        header("Cache-Control: maxage=" . $expires);
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
    }

    /**
     * Builds the map
     */
    public function makeMap()
    {
        // Initialize the co-ordinates, which are necessary to render the map
        $this->initCoords( );

        // Create the base map; that's to say, the map without the markers, copyright notice
        // or any circles
        $this->createBaseMap( );

        // Optionally draw a circle
        if ( $this->circle ) {
            $this->renderCircle( );
        }

        // Optionally add the markers to the map
        if ( count( $this->markers ) ) {
            $this->placeMarkers( );
        }

        // Add the copyright notice
        if ( $this->copyrightImage ) {
            $this->addCopyrightNotice();
        }

    }

    /**
     * Save the map to the specified filepath
     *
     * @param string $filepath
     */
    public function save( $filepath )
    {
        $this->makeMap( );
        imagepng( $this->image, $filepath );
    }

    public function showMap()
    {
        //$this->parseParams();
        if ($this->useMapCache) {
            // use map cache, so check cache for map
            if (!$this->checkMapCache()) {
                // map is not in cache, needs to be build
                $this->makeMap();
                $this->mkdir_recursive(dirname($this->mapCacheIDToFilename()), 0777);
                imagepng($this->image, $this->mapCacheIDToFilename(), 9);
                $this->setHeaders();
                if (file_exists($this->mapCacheIDToFilename())) {
                    return file_get_contents($this->mapCacheIDToFilename());
                } else {
                    return imagepng($this->image);
                }
            } else {
                // map is in cache
                $this->setHeaders();
                return file_get_contents($this->mapCacheIDToFilename());
            }

        } else {
            // no cache, make map, send headers and deliver png
            $this->makeMap();
            $this->setHeaders();
            return imagepng($this->image);

        }
    }

    /**
     * Add a marker to the map
     *
     * @param Marker $marker
     * @return $this
     */
    public function addMarker( Marker $marker )
    {
        $this->markers[ ] = $marker;
        return $this;
    }

    /**
     * Add a multiple markers to the map
     *
     * @param array $markers
     * @return $this
     */
    public function addMarkers( array $markers )
    {
        foreach( $markers as $marker ) {
            $this->addMarker( $marker );
        }
        return $this;
    }

}