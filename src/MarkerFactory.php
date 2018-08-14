<?php namespace Lukaswhite\StaticMaps;

use Lukaswhite\StaticMaps\Exception\MarkerFileNotFoundException;
use Lukaswhite\StaticMaps\Exception\MarkerPrototypeExistsException;

/**
 * Class MarkerFactory
 *
 * Manually creating markers; at least, setting the path to the image, offsets etc
 * can become tedious pretty quickly.
 *
 * This class allows you to "register" what are called prototypes, which is basically
 * a combination of file path and offsets, which can then be created simply by providing
 * the co-ordinates.
 *
 * Here's an example:
 *
 * $factory = new MarkerFactory( );
 * $factory->register(
 *   ( new MarkerPrototype( ) )
 *     ->filepath( '/path/to/your/custom/markers/blue.png' )
 *     ->offsetLeft( 20 )
 *     ->offsetDown( 40 ),
 *     'blue'
 * );
 *
 * Then to create a new one, you can simply do this:
 *
 * $marker = $factory->create( 'blue', new LatLng( 53.48095000, -2.23743000 ) )
 *
 * @package Lukaswhite\StaticMaps
 */
class MarkerFactory extends MarkerPrototype
{
    /**
     * The prototypes
     *
     * @var array
     */
    protected $prototypes = [ ];

    /**
     * MarkerFactory constructor.
     */
    public function __construct( )
    {
        try {
            $this->createDefaultMarkers( );
        // @codeCoverageIgnoreStart
        } catch ( MarkerFileNotFoundException $e ) {
            // this should not get thrown, because they're in-built
        }
        // @codeCoverageIgnoreEnd

    }

    /**
     * Register a prototype
     *
     * @param MarkerPrototype $prototype
     * @param string $name
     * @return $this
     */
    public function register( MarkerPrototype $prototype, $name = null )
    {
        if ( ! $name ) {
            $name = $prototype->getFilename( );
        }

        $this->prototypes[ $name ] = $prototype;

        return $this;
    }

    /**
     * Create a new marker
     *
     * @param string $name
     * @param LatLng $coordinates
     * @return Marker
     * @throws MarkerFileNotFoundException
     */
    public function create( $name, LatLng $coordinates )
    {
        if ( ! isset( $this->prototypes[ $name ] ) ) {
            throw new MarkerFileNotFoundException( );
        }

        $prototype = $this->prototypes[ $name ];
        $marker = new Marker( $coordinates );
        $marker->copyFrom( $prototype );
        return $marker;
    }

    /**
     * Create the default (i.e. pre-defined) marker prototypes.
     *
     * @return void
     * @throws MarkerFileNotFoundException
     */
    protected function createDefaultMarkers( )
    {
        $markerDirectory = realpath( sprintf( '%s/../resources/images/markers', __DIR__ ) );

        $this->register(
            ( new MarkerPrototype( ) )
                ->filepath( sprintf( '%s/google_sm.png', $markerDirectory ) )
                ->offsetLeft( 16 )
                ->offsetDown( 32 ),
            'google_sm'
        );

        $this->register(
            ( new MarkerPrototype( ) )
                ->filepath( sprintf( '%s/google_md.png', $markerDirectory ) )
                ->offsetLeft( 24 )
                ->offsetDown( 48 ),
            'google_md'
        );

        $this->register(
            ( new MarkerPrototype( ) )
                ->filepath( sprintf( '%s/google_lg.png', $markerDirectory ) )
                ->offsetLeft( 32 )
                ->offsetDown( 64 ),
            'google_lg'
        );

        $this->register(
            ( new MarkerPrototype( ) )
                ->filepath( sprintf( '%s/blue.png', $markerDirectory ) )
                ->offsetLeft( 20 )
                ->offsetDown( 40 ),
            'blue'
        );

        $this->register(
            ( new MarkerPrototype( ) )
                ->filepath( sprintf( '%s/blue2.png', $markerDirectory ) )
                ->offsetLeft( 12 )
                ->offsetDown( 36 ),
            'blue2'
        );

        $this->register(
            ( new MarkerPrototype( ) )
                ->filepath( sprintf( '%s/pin.png', $markerDirectory ) )
                ->offsetLeft( 6 )
                ->offsetDown( 42 ),
            'pin'
        );

        $this->register(
            ( new MarkerPrototype( ) )
                ->filepath( sprintf( '%s/greyscale.png', $markerDirectory ) )
                ->offsetLeft( 12 )
                ->offsetDown( 36 ),
            'greyscale'
        );
    }

}