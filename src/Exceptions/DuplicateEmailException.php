<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

/**
 * Duplicate Email Exception
 * 
 * @ai-purpose Handle duplicate email registration attempts
 * @ai-usage Throw when user tries to register with existing email
 */
final class DuplicateEmailException extends Exception
{
    public function __construct(string $email, string $message = 'Email already exists')
    {
        parent::__construct($message);
        $this->email = $email;
    }
    
    /**
     * Get the duplicate email
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
