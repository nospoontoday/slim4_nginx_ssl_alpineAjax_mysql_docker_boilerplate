<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Monolog\Logger;

/**
 * User management service
 * 
 * @ai-purpose Business logic for user operations
 * @ai-dependencies UserRepository, UserValidator, Logger
 * @ai-flow
 *   1. Validate input
 *   2. Execute business logic
 *   3. Trigger events
 *   4. Return formatted data
 * 
 * @example
 * ```php
 * $userService = new UserService($repo, $validator, $logger);
 * $user = $userService->createUser(['name' => 'John', 'email' => 'john@example.com']);
 * ```
 */
final class UserService
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly UserValidator $validator,
        private readonly Logger $logger
    ) {}
    
    /**
     * Get users with pagination and filtering
     * 
     * @param array{
     *   search?: string,
     *   page?: int,
     *   per_page?: int
     * } $filters
     * 
     * @return array{
     *   users: array<int, array{id: int, name: string, email: string}>,
     *   total: int
     * }
     */
    public function getUsers(array $filters = []): array
    {
        $this->logger->info('Fetching users', $filters);
        
        $users = $this->repository->findAll($filters);
        $total = $this->repository->count($filters);
        
        return [
            'users' => $users,
            'total' => $total
        ];
    }
    
    /**
     * Create a new user
     * 
     * @ai-endpoint POST /api/users
     * @ai-auth required
     * @ai-validation UserCreateValidator
     * 
     * @param array{
     *   first_name: string,
     *   last_name: string,
     *   email: string,
     *   password: string,
     *   role?: 'user'|'admin'
     * } $data User data from request
     * 
     * @return array{
     *   id: int,
     *   first_name: string,
     *   last_name: string,
     *   email: string,
     *   created_at: string
     * } Created user data
     * 
     * @throws \App\Exceptions\ValidationException When validation fails
     * @throws \App\Exceptions\DuplicateEmailException When email exists
     * 
     * @ai-test
     * ```php
     * $data = ['first_name' => 'Test', 'last_name' => 'User', 'email' => 'test@example.com', 'password' => 'secret123'];
     * $user = $service->createUser($data);
     * assert($user['email'] === 'test@example.com');
     * ```
     */
    public function createUser(array $data): array
    {
        // @ai-step 1: Validate input data
        $validatedData = $this->validator->validateCreate($data);
        
        // @ai-step 2: Check if email already exists
        if ($this->repository->findByEmail($validatedData['email'])) {
            throw new \App\Exceptions\DuplicateEmailException('Email already exists');
        }
        
        // @ai-step 3: Hash password
        $validatedData['password_hash'] = password_hash($validatedData['password'], PASSWORD_DEFAULT);
        unset($validatedData['password']);
        
        // @ai-step 4: Generate UUID
        $validatedData['uuid'] = \Ramsey\Uuid\Uuid::uuid4()->toString();
        
        // @ai-step 5: Create user
        $userId = $this->repository->create($validatedData);
        
        $this->logger->info('User created', ['user_id' => $userId, 'email' => $validatedData['email']]);
        
        // @ai-step 6: Return created user data
        return $this->repository->findById($userId);
    }
    
    /**
     * Find user by ID
     * 
     * @param positive-int $id
     */
    public function findById(int $id): ?array
    {
        return $this->repository->findById($id);
    }
    
    /**
     * Update user
     * 
     * @param positive-int $id
     * @param array $data
     */
    public function updateUser(int $id, array $data): array
    {
        $validatedData = $this->validator->validateUpdate($data);
        
        if (isset($validatedData['password'])) {
            $validatedData['password_hash'] = password_hash($validatedData['password'], PASSWORD_DEFAULT);
            unset($validatedData['password']);
        }
        
        $this->repository->update($id, $validatedData);
        
        $this->logger->info('User updated', ['user_id' => $id]);
        
        return $this->repository->findById($id);
    }
    
    /**
     * Delete user
     * 
     * @param positive-int $id
     */
    public function deleteUser(int $id): void
    {
        $this->repository->delete($id);
        
        $this->logger->info('User deleted', ['user_id' => $id]);
    }
}
