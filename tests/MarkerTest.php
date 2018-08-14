<?php

use PHPUnit\Framework\TestCase;

class MarkerTest extends TestCase
{
    public function testConstruct( )
    {
        $latLng = new \Lukaswhite\StaticMaps\LatLng( 53.48095000,-2.23743000 );
        $marker = new \Lukaswhite\StaticMaps\Marker( $latLng );
        $this->assertEquals( 53.48095000, $marker->getCoordinates( )->getLatitude( ) );
        $this->assertEquals( -2.23743000, $marker->getCoordinates( )->getLongitude( ) );
    }

    public function testGettersAndSetters( )
    {
        $marker = new \Lukaswhite\StaticMaps\Marker( new \Lukaswhite\StaticMaps\LatLng( ) );
        $this->assertEquals( $marker, $marker->setLatitude( 53.48095000 ) );
        $this->assertEquals( 53.48095000, $marker->getCoordinates( )->getLatitude( ) );
        $this->assertEquals( $marker, $marker->setLongitude( -2.23743000 ) );
        $this->assertEquals( -2.23743000, $marker->getCoordinates( )->getLongitude( ) );
    }

    public function testSetCoordinates( )
    {
        $marker = new \Lukaswhite\StaticMaps\Marker( new \Lukaswhite\StaticMaps\LatLng( ) );
        $marker->setCoordinates( [53.48095000, -2.23743000 ] );
        $this->assertEquals( 53.48095000, $marker->getCoordinates( )->getLatitude( ) );
        $this->assertEquals( -2.23743000, $marker->getCoordinates( )->getLongitude( ) );

    }

    public function testSerialization( )
    {
        $latLng = new \Lukaswhite\StaticMaps\LatLng( 53.48095000,-2.23743000 );
        $this->assertEquals( '53.480950,-2.237430', $latLng->serialize( ) );
        $unserialized = new \Lukaswhite\StaticMaps\LatLng( );
        $unserialized->unserialize( '53.480950,-2.237430' );
        $this->assertEquals( 53.48095, $unserialized->getLatitude( ) );
        $this->assertEquals( -2.23743, $unserialized->getLongitude( ) );
    }

    public function testCreateStringRepresentation( )
    {
        $latLng = new \Lukaswhite\StaticMaps\LatLng( 53.48095000,-2.23743000 );
        $this->assertEquals( '53.480950,-2.237430', $latLng->toString( ) );
        $this->assertEquals( '53.480950,-2.237430', ( string ) $latLng );
    }

    public function testSerializeToJson( )
    {
        $latLng = new \Lukaswhite\StaticMaps\LatLng( 53.48095000,-2.23743000 );
        $this->assertEquals(
            [
                'lat'   =>  53.48095000,
                'lng'   =>  -2.23743000
            ],
            $latLng->jsonSerialize( )
        );
    }
}