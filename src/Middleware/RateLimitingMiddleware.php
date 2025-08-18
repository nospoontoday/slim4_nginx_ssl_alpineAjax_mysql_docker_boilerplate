<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Http\ApiResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Redis;

/**
 * Rate Limiting Middleware
 * 
 * @ai-purpose Protect API endpoints from abuse with rate limiting
 * @ai-usage Add to API route groups for security
 */
class RateLimitingMiddleware implements MiddlewareInterface
{
    private Redis $redis;
    private int $maxRequests;
    private int $windowSeconds;
    
    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect(
            $_ENV['REDIS_HOST'] ?? 'localhost',
            (int) ($_ENV['REDIS_PORT'] ?? 6379)
        );
        
        $this->maxRequests = (int) ($_ENV['RATE_LIMIT_MAX_REQUESTS'] ?? 100);
        $this->windowSeconds = (int) ($_ENV['RATE_LIMIT_WINDOW_SECONDS'] ?? 3600);
    }
    
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $clientId = $this->getClientId($request);
        $key = "rate_limit:{$clientId}";
        
        if (!$this->checkRateLimit($key)) {
            return ApiResponse::error(
                $request,
                new \Slim\Psr7\Response(),
                'Too Many Requests',
                [],
                429
            );
        }
        
        return $handler->handle($request);
    }
    
    private function getClientId(Request $request): string
    {
        // Use IP address as client identifier
        $ip = $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown';
        
        // For API requests, also consider user ID if authenticated
        $userId = $request->getAttribute('user_id');
        if ($userId) {
            return "user_{$userId}";
        }
        
        return "ip_{$ip}";
    }
    
    private function checkRateLimit(string $key): bool
    {
        $current = $this->redis->get($key);
        
        if ($current === false) {
            // First request in window
            $this->redis->setex($key, $this->windowSeconds, 1);
            return true;
        }
        
        if ((int) $current >= $this->maxRequests) {
            return false;
        }
        
        // Increment counter
        $this->redis->incr($key);
        return true;
    }
}
