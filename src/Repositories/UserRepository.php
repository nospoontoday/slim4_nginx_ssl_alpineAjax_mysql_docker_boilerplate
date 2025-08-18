<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

/**
 * User data access layer
 * 
 * @ai-purpose Direct database access for user operations
 * @ai-dependencies PDO connection
 * @ai-pattern Repository pattern with prepared statements
 */
final class UserRepository
{
    public function __construct(
        private readonly PDO $db
    ) {}
    
    /**
     * Find all users with filtering and pagination
     * 
     * @param array{
     *   search?: string,
     *   page?: int,
     *   per_page?: int
     * } $filters
     * 
     * @return array<int, array{id: int, first_name: string, last_name: string, email: string, role: string, created_at: string}>
     */
    public function findAll(array $filters = []): array
    {
        $sql = 'SELECT id, first_name, last_name, email, role, created_at FROM users WHERE 1=1';
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= ' AND (first_name LIKE :search OR last_name LIKE :search OR email LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }
        
        $sql .= ' ORDER BY created_at DESC';
        
        if (isset($filters['per_page'])) {
            $offset = (($filters['page'] ?? 1) - 1) * $filters['per_page'];
            $sql .= ' LIMIT :limit OFFSET :offset';
            $params['limit'] = $filters['per_page'];
            $params['offset'] = $offset;
        }
        
        $stmt = $this->db->prepare($sql);
        
        // Bind parameters with proper types
        foreach ($params as $key => $value) {
            if ($key === 'limit' || $key === 'offset') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Count total users with filters
     * 
     * @param array{search?: string} $filters
     */
    public function count(array $filters = []): int
    {
        $sql = 'SELECT COUNT(*) FROM users WHERE 1=1';
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= ' AND (first_name LIKE :search OR last_name LIKE :search OR email LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return (int) $stmt->fetchColumn();
    }
    
    /**
     * Find user by ID
     * 
     * @param positive-int $id
     * 
     * @return array{id: int, uuid: string, first_name: string, last_name: string, email: string, role: string, created_at: string}|null
     */
    public function findById(int $id): ?array
    {
        $sql = 'SELECT id, uuid, first_name, last_name, email, role, created_at FROM users WHERE id = :id';
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ?: null;
    }
    
    /**
     * Find user by email
     * 
     * @return array{id: int, uuid: string, first_name: string, last_name: string, email: string, role: string, password_hash: string}|null
     */
    public function findByEmail(string $email): ?array
    {
        $sql = 'SELECT id, uuid, first_name, last_name, email, role, password_hash FROM users WHERE email = :email';
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ?: null;
    }
    
    /**
     * Create new user
     * 
     * @param array{uuid: string, first_name: string, last_name: string, email: string, password_hash: string, role?: string} $data
     * 
     * @return positive-int The ID of the created user
     */
    public function create(array $data): int
    {
        $sql = 'INSERT INTO users (uuid, first_name, last_name, email, password_hash, role) VALUES (:uuid, :first_name, :last_name, :email, :password_hash, :role)';
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'uuid' => $data['uuid'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'],
            'role' => $data['role'] ?? 'user'
        ]);
        
        return (int) $this->db->lastInsertId();
    }
    
    /**
     * Update user
     * 
     * @param positive-int $id
     * @param array{first_name?: string, last_name?: string, email?: string, password_hash?: string, role?: string} $data
     */
    public function update(int $id, array $data): void
    {
        $fields = [];
        $params = ['id' => $id];
        
        foreach (['first_name', 'last_name', 'email', 'password_hash', 'role'] as $field) {
            if (isset($data[$field])) {
                $fields[] = "{$field} = :{$field}";
                $params[$field] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            return;
        }
        
        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ', updated_at = CURRENT_TIMESTAMP WHERE id = :id';
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }
    
    /**
     * Delete user
     * 
     * @param positive-int $id
     */
    public function delete(int $id): void
    {
        $sql = 'DELETE FROM users WHERE id = :id';
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}
