<?php

use PHPUnit\Framework\TestCase;

class MapTest extends TestCase
{
    public function testZoom( )
    {
        $map = new \Lukaswhite\StaticMaps\Map( );
        $this->assertEquals( $map, $map->setZoom( 10 ) );
        $this->assertAttributeEquals( 10, 'zoom', $map );
        $map->minimumZoom( );
        $this->assertAttributeEquals( 0, 'zoom', $map );
        $map->maximumZoom( );
        $this->assertAttributeEquals( 18, 'zoom', $map );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testZoomOutOfRange( )
    {
        $map = new \Lukaswhite\StaticMaps\Map( );
        $map->setZoom( 20 );
    }

    public function testSettingCenter( )
    {
        $map = new \Lukaswhite\StaticMaps\Map( );
        $center = new \Lukaswhite\StaticMaps\LatLng( 53.48095000,-2.23743000 );
        $map->setCenter( $center );
        $this->assertAttributeEquals( $center, 'center', $map );
    }

    public function testSettingWidth( )
    {
        $map = new \Lukaswhite\StaticMaps\Map( );
        $this->assertEquals( $map, $map->setWidth( 300 ) );
        $this->assertAttributeEquals( 300, 'width', $map );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingWidthOutOfRange( )
    {
        $map = new \Lukaswhite\StaticMaps\Map( );
        $map->setWidth( 2000 );
    }

    public function testSettingHeight( )
    {
        $map = new \Lukaswhite\StaticMaps\Map( );
        $this->assertEquals( $map, $map->setHeight( 200 ) );
        $this->assertAttributeEquals( 200, 'height', $map );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingHeightOutOfRange( )
    {
        $map = new \Lukaswhite\StaticMaps\Map( );
        $map->setHeight( 2000 );
    }

    public function testSetMapType( )
    {
        $map = new \Lukaswhite\StaticMaps\Map( );
        $this->assertEquals( $map, $map->setMapType( 'osmarenderer' ) );
        $this->assertAttributeEquals( 'osmarenderer', 'maptype', $map );
    }

    public function testSetCopyrightImage( )
    {
        $map = new \Lukaswhite\StaticMaps\Map( );
        $this->assertEquals( $map, $map->setCopyrightImage( __DIR__.'/../../../resources/images/copyright.png' ) );
        $this->assertAttributeEquals(
            __DIR__.'/../../../resources/images/copyright.png',
            'copyrightImage',
            $map
        );
    }
}