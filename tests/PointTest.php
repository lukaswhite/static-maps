<?php

use PHPUnit\Framework\TestCase;

class PointTest extends TestCase
{
    public function testConstruct( )
    {
        $point = new \Lukaswhite\StaticMaps\Point( 100, 200 );
        $this->assertEquals( 100, $point->getX( ) );
        $this->assertEquals( 200, $point->getY( ) );
    }

    public function testGettersAndSetters( )
    {
        $point = new \Lukaswhite\StaticMaps\Point( );
        $this->assertEquals( $point, $point->setX( 100 ) );
        $this->assertEquals( 100, $point->getX( ) );
        $this->assertEquals( $point, $point->setY( 200 ) );
        $this->assertEquals( 200, $point->getY( ) );
    }

}