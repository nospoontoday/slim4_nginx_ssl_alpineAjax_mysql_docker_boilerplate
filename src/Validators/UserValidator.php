<?php

declare(strict_types=1);

namespace App\Validators;

use App\Exceptions\ValidationException;

/**
 * User input validation
 * 
 * @ai-purpose Validate user input data before processing
 * @ai-dependencies ValidationException
 * @ai-pattern Comprehensive validation with detailed error messages
 */
final class UserValidator
{
    /**
     * Validate user creation data
     * 
     * @param array $data
     * 
     * @return array Validated data
     * 
     * @throws ValidationException When validation fails
     */
    public function validateCreate(array $data): array
    {
        $errors = [];
        
        // First name validation
        if (empty($data['first_name'])) {
            $errors['first_name'] = 'First name is required';
        } elseif (strlen($data['first_name']) < 2) {
            $errors['first_name'] = 'First name must be at least 2 characters';
        } elseif (strlen($data['first_name']) > 100) {
            $errors['first_name'] = 'First name cannot exceed 100 characters';
        }
        
        // Last name validation
        if (empty($data['last_name'])) {
            $errors['last_name'] = 'Last name is required';
        } elseif (strlen($data['last_name']) < 2) {
            $errors['last_name'] = 'Last name must be at least 2 characters';
        } elseif (strlen($data['last_name']) > 100) {
            $errors['last_name'] = 'Last name cannot exceed 100 characters';
        }
        
        // Email validation
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        } elseif (strlen($data['email']) > 255) {
            $errors['email'] = 'Email cannot exceed 255 characters';
        }
        
        // Password validation
        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $data['password'])) {
            $errors['password'] = 'Password must contain at least one lowercase letter, one uppercase letter, and one number';
        }
        
        // Role validation
        if (isset($data['role']) && !in_array($data['role'], ['user', 'admin'])) {
            $errors['role'] = 'Role must be either "user" or "admin"';
        }
        
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        
        return $data;
    }
    
    /**
     * Validate user update data
     * 
     * @param array $data
     * 
     * @return array Validated data
     * 
     * @throws ValidationException When validation fails
     */
    public function validateUpdate(array $data): array
    {
        $errors = [];
        
        // First name validation (optional for updates)
        if (isset($data['first_name'])) {
            if (strlen($data['first_name']) < 2) {
                $errors['first_name'] = 'First name must be at least 2 characters';
            } elseif (strlen($data['first_name']) > 100) {
                $errors['first_name'] = 'First name cannot exceed 100 characters';
            }
        }
        
        // Last name validation (optional for updates)
        if (isset($data['last_name'])) {
            if (strlen($data['last_name']) < 2) {
                $errors['last_name'] = 'Last name must be at least 2 characters';
            } elseif (strlen($data['last_name']) > 100) {
                $errors['last_name'] = 'Last name cannot exceed 100 characters';
            }
        }
        
        // Email validation (optional for updates)
        if (isset($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email format';
            } elseif (strlen($data['email']) > 255) {
                $errors['email'] = 'Email cannot exceed 255 characters';
            }
        }
        
        // Password validation (optional for updates)
        if (isset($data['password'])) {
            if (strlen($data['password']) < 8) {
                $errors['password'] = 'Password must be at least 8 characters';
            } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $data['password'])) {
                $errors['password'] = 'Password must contain at least one lowercase letter, one uppercase letter, and one number';
            }
        }
        
        // Role validation (optional for updates)
        if (isset($data['role']) && !in_array($data['role'], ['user', 'admin'])) {
            $errors['role'] = 'Role must be either "user" or "admin"';
        }
        
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        
        return $data;
    }
    
    /**
     * Validate login data
     * 
     * @param array $data
     * 
     * @return array Validated data
     * 
     * @throws ValidationException When validation fails
     */
    public function validateLogin(array $data): array
    {
        $errors = [];
        
        // Email validation
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }
        
        // Password validation
        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        }
        
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        
        return $data;
    }
}
