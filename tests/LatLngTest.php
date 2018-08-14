<?php

use PHPUnit\Framework\TestCase;

class LatLngTest extends TestCase
{
    public function testConstruct( )
    {
        $latLng = new \Lukaswhite\StaticMaps\LatLng( 53.48095000,-2.23743000 );
        $this->assertEquals( 53.48095000, $latLng->getLatitude( ) );
        $this->assertEquals( -2.23743000, $latLng->getLongitude( ) );
    }

    public function testGettersAndSetters( )
    {
        $latLng = new \Lukaswhite\StaticMaps\LatLng( );
        $this->assertEquals( $latLng, $latLng->setLatitude( 53.48095000 ) );
        $this->assertEquals( 53.48095000, $latLng->getLatitude( ) );
        $this->assertEquals( $latLng, $latLng->setLongitude( -2.23743000 ) );
        $this->assertEquals( -2.23743000, $latLng->getLongitude( ) );
    }

    public function testValidation( )
    {
        $latLng = new \Lukaswhite\StaticMaps\LatLng( 53.48095000,-2.23743000 );
        $this->assertTrue( $latLng->isValid( ) );

        $this->assertTrue(
            ( new \Lukaswhite\StaticMaps\LatLng( -90, -180 ) )->isValid( )
        );

        $this->assertTrue(
            ( new \Lukaswhite\StaticMaps\LatLng( 90, 180 ) )->isValid( )
        );

        $this->assertFalse(
            ( new \Lukaswhite\StaticMaps\LatLng( -91, -2.23743000 ) )->isValid( )
        );
        $this->assertFalse(
            ( new \Lukaswhite\StaticMaps\LatLng( 95, -2.23743000 ) )->isValid( )
        );
        $this->assertFalse(
            ( new \Lukaswhite\StaticMaps\LatLng( 53.48095000, -200 ) )->isValid( )
        );
        $this->assertFalse(
            ( new \Lukaswhite\StaticMaps\LatLng( 53.48095000, 190 ) )->isValid( )
        );

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