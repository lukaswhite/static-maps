<?php

use PHPUnit\Framework\TestCase;

class MathTest extends TestCase
{
    public function testMilesToMeters( )
    {
        $this->assertEquals( 1609, \Lukaswhite\StaticMaps\Math::milesToMeters( 1 ) );
        $this->assertEquals( 5954, \Lukaswhite\StaticMaps\Math::milesToMeters( 3.7 ) );
    }

    public function testMetersToMiles( )
    {
        $this->assertEquals( 16400, \Lukaswhite\StaticMaps\Math::kilometersToMeters( 16.4 ) );
    }

    public function testHex2RGB( )
    {
        $this->assertEquals(
            [
                'red' => 243,
                'green' => 149,
                'blue'    =>  230
            ],
            \Lukaswhite\StaticMaps\Math::hex2RGB( '#f395e6' )
        );

        $this->assertEquals(
            [
                'red' => 243,
                'green' => 149,
                'blue'    =>  230
            ],
            \Lukaswhite\StaticMaps\Math::hex2RGB( 'f395e6' )
        );

        $this->assertEquals(
            [
                'red' => 255,
                'green' => 34,
                'blue'    =>  85
            ],
            \Lukaswhite\StaticMaps\Math::hex2RGB( 'f25' )
        );
    }

    public function testCalculateDistance( )
    {
        $this->assertEquals(
            2703,
            \Lukaswhite\StaticMaps\Math::calculateDistance( 120, 300, 250, 3000 )
        );
    }
}