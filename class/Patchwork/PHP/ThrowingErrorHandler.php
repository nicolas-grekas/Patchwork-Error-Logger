<?php // vi: set fenc=utf-8 ts=4 sw=4 et:
/*
 * Copyright (C) 2012 Nicolas Grekas - p@tchwork.com
 *
 * This library is free software; you can redistribute it and/or modify it
 * under the terms of the (at your option):
 * Apache License v2.0 (http://apache.org/licenses/LICENSE-2.0.txt), or
 * GNU General Public License v2.0 (http://gnu.org/licenses/gpl-2.0.txt).
 */

namespace Patchwork\PHP;

/**
 * ThrowingErrorHandler turns fatal errors to ErrorExceptions.
 *
 * This does no magic: until PHP allows us to catch other error type,
 * only E_RECOVERABLE_ERROR | E_USER_ERROR is really usefull here.
 */
class ThrowingErrorHandler
{
    protected static $caughtToStringException;


    /**
     * Registers the error handler for otherwise fatal errors.
     */
    static function register(self $h = null)
    {
        isset($h) or $h = new self;
        set_error_handler(
            array($h, 'handleError'),
            E_RECOVERABLE_ERROR | E_USER_ERROR | E_PARSE | E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR
        );
    }

    /**
     * Restores __toString()'s ability to throw exceptions.
     *
     * Throwing an exception inside __toString() doesn't work, unless
     * you use this static method as return value instead of throwing.
     *
     * The hack is to return null, which is illegal for __toString() and
     * triggers an E_RECOVERABLE_ERROR that is caught by ->handleError()
     * who rethrows the $e Exception.
     */
    static function handleToStringException(\Exception $e)
    {
        self::$caughtToStringException = $e;
        return null;
    }


    /**
     * Turns errors to ErrorExceptions.
     */
    function handleError($type, $message, $file, $line, &$scope)
    {
        if (isset(self::$caughtToStringException))
        {
            $e = self::$caughtToStringException;
            self::$caughtToStringException = null;
            throw $e;
        }
        else
        {
            throw new \ErrorException($message, 0, $type, $file, $line);
        }
    }
}