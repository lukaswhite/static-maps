<?php

use PHPUnit\Framework\TestCase;

class MarkerFactoryTest extends TestCase
{
    public function testDefaults( )
    {
        $factory = new \Lukaswhite\StaticMaps\MarkerFactory( );
        $marker = $factory->create( 'google_lg', new \Lukaswhite\StaticMaps\LatLng( ) );
        $this->assertEquals( 'google_lg.png', $marker->getFilename( ) );
        $this->assertEquals( -32, $marker->getOffsetX( ) );
        $this->assertEquals( -64, $marker->getOffsetY( ) );
    }

    public function testRegister( )
    {
        $prototype = new \Lukaswhite\StaticMaps\MarkerPrototype( );
        $prototype->filepath( __DIR__ . '/../resources/images/markers/test.png' );
        $factory = new \Lukaswhite\StaticMaps\MarkerFactory( );
        $factory->register( $prototype, 'test' );
        $marker = $factory->create( 'test', new \Lukaswhite\StaticMaps\LatLng( ) );
        $this->assertEquals( 'test.png', $marker->getFilename( ) );

    }

    public function testRegisterWithoutName( )
    {
        $prototype = new \Lukaswhite\StaticMaps\MarkerPrototype( );
        $prototype->filepath( __DIR__ . '/../resources/images/markers/test.png' );
        $factory = new \Lukaswhite\StaticMaps\MarkerFactory( );
        $factory->register( $prototype );
        $marker = $factory->create( 'test.png', new \Lukaswhite\StaticMaps\LatLng( ) );
        $this->assertEquals( 'test.png', $marker->getFilename( ) );

    }

    /**
     * @expectedException  \Lukaswhite\StaticMaps\Exception\MarkerFileNotFoundException
     */
    public function testCreatingWhenMarkerNotDefined( )
    {
        $factory = new \Lukaswhite\StaticMaps\MarkerFactory( );
        $marker = $factory->create( 'foobar', new \Lukaswhite\StaticMaps\LatLng( ) );
    }


}