<?php

use PHPUnit\Framework\TestCase;

class RequestParserTest extends TestCase
{
    public function testParse( )
    {
        $center = new \Lukaswhite\StaticMaps\LatLng( 53.48095000,-2.23743000 );

        $parser = new \Lukaswhite\StaticMaps\RequestParser(
            [
                'center'    =>  ( string ) $center,
                'zoom'      =>  10,
                'size'      =>  '300x200',
                'maptype'   =>  'osmarenderer',
            ]
        );

        $map = $parser->getMap( );

        $this->assertAttributeEquals( $center, 'center', $map );
        $this->assertAttributeEquals( 10, 'zoom', $map );
        $this->assertAttributeEquals( 300, 'width', $map );
        $this->assertAttributeEquals( 200, 'height', $map );
        $this->assertAttributeEquals( 'osmarenderer', 'maptype', $map );

    }

    /**
     * @expectedException  \Lukaswhite\StaticMaps\Exception\InvalidRequestException
     * @expectedExceptionMessage The center parameter is required
     */
    public function testCenterIsRequired( )
    {
        $parser = new \Lukaswhite\StaticMaps\RequestParser(
            [
                'zoom'      =>  10,
                'size'      =>  '300x200',
                'maptype'   =>  'osmarenderer',
            ]
        );

        $parser->getMap( );
    }

    /**
     * @expectedException  \Lukaswhite\StaticMaps\Exception\InvalidRequestException
     * @expectedExceptionMessage The center parameter should be in the format lat,lng
     */
    public function testCenterMustBeInCorrectFormat( )
    {
        $parser = new \Lukaswhite\StaticMaps\RequestParser(
            [
                'center'    =>  'somewhere',
                'zoom'      =>  10,
                'size'      =>  '300x200',
                'maptype'   =>  'osmarenderer',
            ]
        );

        $map = $parser->getMap( );
    }

    /**
     * @expectedException  \Lukaswhite\StaticMaps\Exception\InvalidRequestException
     * @expectedExceptionMessage The size parameter should be in the format widthxheight; e.g. 200x200
     */
    public function testSizeMustBeInCorrectFormat( )
    {
        $center = new \Lukaswhite\StaticMaps\LatLng( 53.48095000,-2.23743000 );

        $parser = new \Lukaswhite\StaticMaps\RequestParser(
            [
                'center'    =>  ( string ) $center,
                'zoom'      =>  10,
                'size'      =>  'really big',
                'maptype'   =>  'osmarenderer',
            ]
        );

        $map = $parser->getMap( );
    }
}