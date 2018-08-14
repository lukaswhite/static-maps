<?php namespace Lukaswhite\StaticMaps\Exception;

/**
 * Class MarkerPrototypeNotFoundException
 *
 * This exception gets thrown if you try to create a marker based on a prototype that
 * has not yet been registered.
 *
 * @package Lukaswhite\StaticMaps\Exception
 */
class MarkerPrototypeNotFoundException extends \Exception
{
    /**
     * The exception message
     *
     * @var string
     */
    protected $message = 'Marker prototype not found';
}