<?php namespace Lukaswhite\StaticMaps;

use InvalidArgumentException;
use Lukaswhite\StaticMaps\Exception\MarkerFileNotFoundException;

/**
 * Class Marker
 *
 * @package Lukaswhite\StaticMaps
 */
class MarkerPrototype
{

    /**
     * The filepath to the image used to render the marker
     *
     * @var string
     */
    protected $filepath;

    /**
     * The filepath to the image used to add a shadow to the marker, if appropriate
     *
     * @var string|null
     */
    protected $shadowFilepath;

    /**
     * The X offset
     *
     * @var integer
     */
    protected $offsetX = 0;

    /**
     * The Y offset
     *
     * @var integer
     */
    protected $offsetY = 0;

    /**
     * The X offset of the shadow image
     *
     * @var integer
     */
    protected $shadowOffsetX;

    /**
     * The Y offset of the shadow image
     *
     * @var integer
     */
    protected $shadowOffsetY;

    /**
     * The width of the marker
     *
     * @var integer
     */
    protected $width;

    /**
     * The height of the marker
     *
     * @var integer
     */
    protected $height;

    /**
     * Set the filepath (to the pin image)
     *
     * @param string $filepath
     * @return $this
     * @throws MarkerFileNotFoundException
     */
    public function filepath( $filepath )
    {
        if ( ! file_exists( $filepath ) ) {
            throw new MarkerFileNotFoundException( );
        }
        $this->filepath = $filepath;
        $this->calculateDimensions( );
        return $this;
    }

    /**
     * Offset the pin to the left by the specified number of pixels.
     *
     * For example, suppose the image is a 30 pixel wide pin, with the "point" in the
     * center. In that case, you'd offset left by 15px.
     *
     * @param $value
     * @return $this
     */
    public function offsetLeft( $value )
    {
        $this->offsetX = ( $value * -1 );
        return $this;
    }

    /**
     * Offset the pin downwards by the specified number of pixels.
     *
     * For example, suppose the image is a 60 pixel tall pin, with the "point" at the very bottom of
     * the image. In that case the offset would be 60px, since the image needs to be placed in such a way that
     * the bottom of the image - i.e. the tip of the pin - "points" to the point the marker is supposed to
     * represent.
     *
     * @param $value
     * @return $this
     */
    public function offsetDown( $value )
    {
        $this->offsetY = ( $value * -1 );
        return $this;
    }

    /**
     * Get the image data
     *
     * @return resource
     */
    public function getImageData( )
    {
        return imagecreatefrompng( $this->filepath );
    }

    /**
     * Calculate the dimensions of the marker image.
     */
    protected function calculateDimensions( )
    {
        list( $width, $height, $type, $attr ) = getimagesize( $this->filepath );
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Get the dimensions of the marker image
     *
     * @return array
     */
    public function getDimensions( )
    {
        return [
            'width'     =>  $this->width,
            'height'    =>  $this->height,
        ];
    }

    /**
     * Get the width of the marker image
     *
     * @return int
     */
    public function getWidth( )
    {
        return $this->width;
    }

    /**
     * Get the height of the marker image
     *
     * @return int
     */
    public function getHeight( )
    {
        return $this->height;
    }

    /**
     * Get the X offset
     *
     * @return int
     */
    public function getOffsetX( )
    {
        return $this->offsetX;
    }

    /**
     * Get the Y offset
     *
     * @return int
     */
    public function getOffsetY( )
    {
        return $this->offsetY;
    }

    /**
     * Get the filepath
     *
     * @return string
     */
    public function getFilepath( )
    {
        return $this->filepath;
    }

    /**
     * Get the filename
     *
     * @return string
     */
    public function getFilename( )
    {
        return basename( $this->filepath );
    }

    /**
     * Copy the relevant information from one prototype into another (i.e. this one)
     *
     * @param MarkerPrototype $prototype
     * @throws MarkerFileNotFoundException
     */
    public function copyFrom( MarkerPrototype $prototype )
    {
        $this->filepath( $prototype->getFilepath( ) );
        $this->offsetX = $prototype->getOffsetX( );
        $this->offsetY = $prototype->getOffsetY( );
    }

}