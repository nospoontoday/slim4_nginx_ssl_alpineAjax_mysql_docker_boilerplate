<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Http\ApiResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * API Authentication Middleware
 * 
 * @ai-purpose Protect API endpoints with JWT authentication
 * @ai-usage Add to protected route groups
 */
class ApiAuthenticationMiddleware implements MiddlewareInterface
{
    private string $jwtSecret;
    
    public function __construct()
    {
        $this->jwtSecret = $_ENV['JWT_SECRET'] ?? 'your-secret-key';
    }
    
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $token = $this->extractToken($request);
        
        if (!$token || !$this->validateToken($token)) {
            return ApiResponse::error(
                $request,
                new \Slim\Psr7\Response(),
                'Unauthorized',
                [],
                401
            );
        }
        
        // Add user data to request attributes
        $userData = $this->getUserDataFromToken($token);
        $request = $request->withAttribute('user_id', $userData['user_id']);
        $request = $request->withAttribute('user_role', $userData['role']);
        
        return $handler->handle($request);
    }
    
    private function extractToken(Request $request): ?string
    {
        $header = $request->getHeaderLine('Authorization');
        
        if (preg_match('/Bearer\s+(.+)/', $header, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
    
    private function validateToken(string $token): bool
    {
        try {
            JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function getUserDataFromToken(string $token): array
    {
        $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        return (array) $decoded;
    }
}
