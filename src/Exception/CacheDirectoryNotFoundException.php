<?php namespace Lukaswhite\StaticMaps\Exception;

/**
 * Class CacheDirectoryNotFoundException
 *
 * This exception gets thrown if the cache directory cannot be found.
 *
 * @package Lukaswhite\StaticMaps\Exception
 */
class CacheDirectoryNotFoundException extends \Exception
{
    /**
     * The exception message
     * 
     * @var string
     */
    protected $message = 'The cache directory cannot be found';
}