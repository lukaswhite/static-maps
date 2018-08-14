<?php

use PHPUnit\Framework\TestCase;

class MarkerPrototypeTest extends TestCase
{
    public function testFilepath( )
    {
        $prototype = new \Lukaswhite\StaticMaps\MarkerPrototype( );
        $prototype->filepath( __DIR__ . '/../resources/images/markers/google_md.png' );
        $this->assertEquals(
            __DIR__ . '/../resources/images/markers/google_md.png',
            $prototype->getFilepath( )
        );
        $this->assertEquals(
            'google_md.png',
            $prototype->getFilename( )
        );
    }

    /**
     * @expectedException  \Lukaswhite\StaticMaps\Exception\MarkerFileNotFoundException
     */
    public function testFilepathThatDoesNotExist( )
    {
        $prototype = new \Lukaswhite\StaticMaps\MarkerPrototype( );
        $prototype->filepath( '/path/that/does/not/exist' );
    }

    public function testDimensions( )
    {
        $prototype = new \Lukaswhite\StaticMaps\MarkerPrototype( );
        $prototype->filepath( __DIR__ . '/../resources/images/markers/google_md.png' );
        $this->assertEquals( [
            'width' => 48,
            'height' => 48,
        ], $prototype->getDimensions( ) );
        $this->assertEquals( 48, $prototype->getWidth( ) );
        $this->assertEquals( 48, $prototype->getHeight( ) );
    }

    public function testOffsets( )
    {
        $prototype = new \Lukaswhite\StaticMaps\MarkerPrototype( );
        $this->assertEquals( 0, $prototype->getOffsetX( ) );
        $this->assertEquals( 0, $prototype->getOffsetY( ) );
        $prototype->offsetLeft( 20 )->offsetDown( 10 );
        $this->assertEquals( -20, $prototype->getOffsetX( ) );
        $this->assertEquals( -10, $prototype->getOffsetY( ) );
    }

    public function testGetImageData( )
    {
        $prototype = new \Lukaswhite\StaticMaps\MarkerPrototype( );
        $prototype->filepath( __DIR__ . '/../resources/images/markers/google_md.png' );
        $this->assertTrue(
            is_resource(
                $prototype->getImageData( )
            )
        );
    }

    public function testCopy( )
    {
        $one = new \Lukaswhite\StaticMaps\MarkerPrototype( );
        $one->filepath( __DIR__ . '/../resources/images/markers/google_md.png' )
            ->offsetDown( 20 )
            ->offsetLeft( 10 );

        $two = new \Lukaswhite\StaticMaps\MarkerPrototype( );
        $two->copyFrom( $one );

        $this->assertEquals( $one->getFilepath( ), $two->getFilepath( ) );
        $this->assertEquals( $one->getOffsetX( ), $two->getOffsetX( ) );
        $this->assertEquals( $one->getOffsetY( ), $two->getOffsetY( ) );
    }


}