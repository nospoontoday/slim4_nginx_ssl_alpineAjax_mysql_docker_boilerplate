<?php
/**
 * @file AuthenticationException.php
 * @purpose Authentication-specific exception
 * @ai-context Custom exception for authentication failures
 */

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

/**
 * Authentication exception
 * 
 * @ai-purpose Thrown when authentication fails
 */
class AuthenticationException extends Exception
{
    public function __construct(string $message = 'Authentication failed', int $code = 401, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}