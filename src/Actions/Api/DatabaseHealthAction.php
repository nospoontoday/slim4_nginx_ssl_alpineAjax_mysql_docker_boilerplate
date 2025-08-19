<?php

declare(strict_types=1);

namespace App\Actions\Api;

use App\Services\DatabaseHealthService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Monolog\Logger;

/**
 * API endpoint for database health monitoring
 * 
 * @ai-purpose Expose database health information via API
 * @ai-dependencies DatabaseHealthService, Logger
 * @ai-pattern Action pattern for API endpoints
 */
final class DatabaseHealthAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly DatabaseHealthService $healthService,
        private readonly Logger $logger
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        
        try {
            $this->logger->info('Database health check requested via API');
            
            $health = $this->healthService->performHealthCheck();
            
            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $health,
                'timestamp' => date('c')
            ], JSON_PRETTY_PRINT));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
                
        } catch (\Exception $e) {
            $this->logger->error('Database health check failed: ' . $e->getMessage());
            
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Failed to perform health check',
                'message' => $e->getMessage(),
                'timestamp' => date('c')
            ], JSON_PRETTY_PRINT));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}
