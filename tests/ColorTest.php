<?php

use PHPUnit\Framework\TestCase;

class ColorTest extends TestCase
{
    public function testConstruct( )
    {
        $color = new \Lukaswhite\StaticMaps\Color( 1, 2, 3 );
        $this->assertEquals( 1, $color->getRed( ) );
        $this->assertEquals( 2, $color->getGreen( ) );
        $this->assertEquals( 3, $color->getBlue( ) );
    }

    public function testCreateFromHex( )
    {
        $color = \Lukaswhite\StaticMaps\Color::createFromHex( 'f395e6' );
        $this->assertEquals( 243, $color->getRed( ) );
        $this->assertEquals( 149, $color->getGreen( ) );
        $this->assertEquals( 230, $color->getBlue( ) );
    }

    public function testGettersAndSetters( )
    {
        $color = new \Lukaswhite\StaticMaps\Color( 1, 2, 3 );
        $this->assertEquals( $color, $color->setRed( 4 ) );
        $this->assertEquals( 4, $color->getRed( ) );
        $this->assertEquals( $color, $color->setGreen( 5 ) );
        $this->assertEquals( 5, $color->getGreen( ) );
        $this->assertEquals( $color, $color->setBlue( 6 ) );
        $this->assertEquals( 6, $color->getBlue( ) );
        $this->assertEquals( $color, $color->setAlpha( 10 ) );
        $this->assertEquals( 10, $color->getAlpha( ) );
    }

    public function testAllocate( )
    {
        $image = imagecreatefrompng( __DIR__ . '/../resources/images/markers/test.png' );
        $color = \Lukaswhite\StaticMaps\Color::createFromHex( 'f395e6' );
        $this->assertEquals( 15963622, $color->allocate( $image ) );
        $color->setAlpha( 10 );
        $this->assertEquals( 183735782, $color->allocate( $image ) );
    }

    public function testSerialization( )
    {
        $color = \Lukaswhite\StaticMaps\Color::createFromHex( 'f395e6' );
        $serialized = $color->serialize( );
        $this->assertEquals( '243|149|230', $serialized );
        $color->setAlpha( 10 );
        $this->assertEquals( '243|149|230|10', $color->serialize( ) );
        $unserialized = new $color( 0, 0, 0 );
        $unserialized->unserialize( $serialized );
        $this->assertEquals( 243, $unserialized->getRed( ) );
        $this->assertEquals( 149, $unserialized->getGreen( ) );
        $this->assertEquals( 230, $unserialized->getBlue( ) );
        $unserialized2 = new $color( 0, 0, 0 );
        $unserialized2->unserialize( '243|149|230|10' );
        $this->assertEquals( 10, $unserialized2->getAlpha( ) );
    }

    public function testJsonSerialization( )
    {
        $color = \Lukaswhite\StaticMaps\Color::createFromHex( 'f395e6' );
        $this->assertEquals(
            [
                'red'   =>  243,
                'green' =>  149,
                'blue'  =>  230,
            ],
            $color->jsonSerialize( )
        );
        $color->setAlpha( 10 );
        $this->assertEquals(
            [
                'red'   =>  243,
                'green' =>  149,
                'blue'  =>  230,
                'alpha' =>  10,
            ],
            $color->jsonSerialize( )
        );
    }
}