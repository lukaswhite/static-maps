<?php namespace Lukaswhite\StaticMaps\Exception;

/**
 * Class CacheDirectoryNotWriteableException
 *
 * This exception gets thrown if the cache directory cannot be written to.
 *
 * @package Lukaswhite\StaticMaps\Exception
 */
class CacheDirectoryNotWriteableException extends \Exception
{
    /**
     * The exception message
     * 
     * @var string
     */
    protected $message = 'The cache directory is not writeable';
}