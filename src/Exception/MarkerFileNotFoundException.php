<?php namespace Lukaswhite\StaticMaps\Exception;

/**
 * Class MarkerFileNotFoundException
 *
 * This exception gets thrown if the marker image cannot be found.
 *
 * @package Lukaswhite\StaticMaps\Exception
 */
class MarkerFileNotFoundException extends \Exception
{
    /**
     * The exception message
     *
     * @var string
     */
    protected $message = 'Marker file not found';
}