<?php
/**
 * @file LoginValidator.php
 * @purpose Login form validation
 * @ai-context Input validation for login requests
 * @ai-patterns Form validation, security checks
 */

declare(strict_types=1);

namespace App\Validators;

use App\Exceptions\ValidationException;

/**
 * Login form validator
 * 
 * @ai-purpose Validate login form input
 * @ai-validation Email format, password requirements
 */
class LoginValidator
{
    /**
     * Validate login form data
     * 
     * @param array{
     *   email?: string,
     *   password?: string,
     *   remember?: bool
     * } $data Login form data
     * 
     * @return array{
     *   email: string,
     *   password: string,
     *   remember: bool
     * } Validated data
     * 
     * @throws ValidationException When validation fails
     */
    public function validate(array $data): array
    {
        $errors = [];

        // Email validation
        if (empty($data['email'])) {
            $errors['email'] = ['Email is required'];
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = ['Please enter a valid email address'];
        } elseif (strlen($data['email']) > 255) {
            $errors['email'] = ['Email address is too long'];
        }

        // Password validation
        if (empty($data['password'])) {
            $errors['password'] = ['Password is required'];
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = ['Password must be at least 6 characters long'];
        } elseif (strlen($data['password']) > 255) {
            $errors['password'] = ['Password is too long'];
        }

        // Remember me validation (optional)
        $remember = false;
        if (isset($data['remember'])) {
            $remember = filter_var($data['remember'], FILTER_VALIDATE_BOOLEAN);
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }

        return [
            'email' => trim(strtolower($data['email'])),
            'password' => $data['password'],
            'remember' => $remember
        ];
    }

    /**
     * Validate email format only (for quick checks)
     * 
     * @param string $email Email to validate
     * @return bool Whether email is valid
     */
    public function isValidEmail(string $email): bool
    {
        return !empty($email) && 
               filter_var($email, FILTER_VALIDATE_EMAIL) !== false &&
               strlen($email) <= 255;
    }

    /**
     * Validate password strength (for registration)
     * 
     * @param string $password Password to validate
     * @return array{valid: bool, errors: array<string>} Validation result
     */
    public function validatePasswordStrength(string $password): array
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter';
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter';
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number';
        }

        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'Password must contain at least one special character';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}