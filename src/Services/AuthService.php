<?php
/**
 * @file AuthService.php
 * @purpose Authentication business logic
 * @ai-context Service layer for user authentication
 * @ai-patterns JWT tokens, password hashing, session management
 */

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use App\Exceptions\AuthenticationException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Authentication service
 * 
 * @ai-purpose Handle user authentication, JWT tokens, and sessions
 * @ai-dependencies UserRepository, JWT library
 * @ai-flow
 *   1. Validate credentials
 *   2. Generate JWT token
 *   3. Set session data
 *   4. Return user data and token
 */
class AuthService
{
    private string $jwtSecret;
    private int $jwtExpiration;

    public function __construct(
        private readonly UserRepository $userRepository
    ) {
        $this->jwtSecret = $_ENV['JWT_SECRET'] ?? 'your-secret-key';
        $this->jwtExpiration = (int)($_ENV['JWT_EXPIRATION'] ?? 3600); // 1 hour
    }

    /**
     * Authenticate user with email and password
     * 
     * @ai-endpoint POST /api/auth/login
     * @ai-validation LoginValidator
     * 
     * @param string $email User email
     * @param string $password User password
     * @param bool $remember Whether to extend session
     * 
     * @return array{
     *   user: array{id: int, name: string, email: string},
     *   token: string,
     *   expires_at: int
     * } Authentication result
     * 
     * @throws AuthenticationException When credentials are invalid
     */
    public function authenticate(string $email, string $password, bool $remember = false): array
    {
        // Find user by email
        $user = $this->userRepository->findByEmail($email);
        
        if (!$user) {
            // Log failed login attempt for security monitoring
            error_log("Failed login attempt for email: {$email} - User not found");
            throw new AuthenticationException('Invalid email or password');
        }

        // Verify password exists
        if (empty($user['password'])) {
            error_log("Authentication error for user {$email}: No password hash found");
            throw new AuthenticationException('Invalid email or password');
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            error_log("Failed login attempt for email: {$email} - Invalid password");
            throw new AuthenticationException('Invalid email or password');
        }

        // Check if user is active
        if (!$user['is_active']) {
            error_log("Login attempt for deactivated account: {$email}");
            throw new AuthenticationException('Account is deactivated');
        }

        // Generate JWT token
        $expiresAt = time() + ($remember ? $this->jwtExpiration * 24 : $this->jwtExpiration);
        $token = $this->generateJwtToken($user['id'], $expiresAt);

        // Update last login
        try {
            $this->userRepository->updateLastLogin($user['id']);
        } catch (\Exception $e) {
            // Log but don't fail authentication for this
            error_log("Failed to update last login for user {$user['id']}: " . $e->getMessage());
        }

        // Return user data without password
        unset($user['password']);

        return [
            'user' => $user,
            'token' => $token,
            'expires_at' => $expiresAt
        ];
    }

    /**
     * Validate JWT token
     * 
     * @param string $token JWT token
     * @return array{id: int, email: string} User data from token
     * @throws AuthenticationException When token is invalid
     */
    public function validateToken(string $token): array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            
            return [
                'id' => $decoded->user_id,
                'email' => $decoded->email
            ];
        } catch (\Exception $e) {
            throw new AuthenticationException('Invalid token');
        }
    }

    /**
     * Generate JWT token
     * 
     * @param int $userId User ID
     * @param int $expiresAt Expiration timestamp
     * @return string JWT token
     */
    private function generateJwtToken(int $userId, int $expiresAt): string
    {
        $payload = [
            'user_id' => $userId,
            'iat' => time(),
            'exp' => $expiresAt
        ];

        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }

    /**
     * Logout user (invalidate token)
     * 
     * @param string $token JWT token to invalidate
     * @return bool Success status
     */
    public function logout(string $token): bool
    {
        // In a production app, you'd want to maintain a blacklist of tokens
        // For now, we'll just return true as the client will discard the token
        return true;
    }

    /**
     * Refresh JWT token
     * 
     * @param string $token Current JWT token
     * @return array{token: string, expires_at: int} New token data
     * @throws AuthenticationException When token is invalid
     */
    public function refreshToken(string $token): array
    {
        $userData = $this->validateToken($token);
        
        $expiresAt = time() + $this->jwtExpiration;
        $newToken = $this->generateJwtToken($userData['id'], $expiresAt);

        return [
            'token' => $newToken,
            'expires_at' => $expiresAt
        ];
    }
}