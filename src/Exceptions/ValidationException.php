<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

/**
 * Validation Exception
 * 
 * @ai-purpose Handle validation errors with detailed field-specific messages
 * @ai-usage Throw when input validation fails
 */
final class ValidationException extends Exception
{
    private array $errors;
    
    public function __construct(array $errors, string $message = 'Validation failed')
    {
        parent::__construct($message);
        $this->errors = $errors;
    }
    
    /**
     * Get validation errors
     * 
     * @return array<string, string> Field-specific error messages
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    /**
     * Get first error message
     */
    public function getFirstError(): ?string
    {
        return reset($this->errors) ?: null;
    }
}
