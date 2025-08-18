<?php

declare(strict_types=1);

namespace App\Http;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * API Response Handler
 * 
 * @ai-purpose Handle content negotiation between JSON API and HTMX responses
 * @ai-pattern Returns HTML for HTMX requests, JSON for API requests
 */
class ApiResponse
{
    /**
     * Success response
     */
    public static function success(
        Request $request,
        Response $response,
        mixed $data,
        array $meta = [],
        int $status = 200
    ): Response {
        if ($request->hasHeader('HX-Request')) {
            return self::htmxResponse($response, $data);
        }
        
        $payload = [
            'status' => 'success',
            'data' => $data,
            'meta' => array_merge([
                'timestamp' => time(),
                'version' => '1.0'
            ], $meta)
        ];
        
        return $response
            ->withJson($payload)
            ->withStatus($status);
    }
    
    /**
     * Error response
     */
    public static function error(
        Request $request,
        Response $response,
        string $message,
        array $errors = [],
        int $status = 400
    ): Response {
        if ($request->hasHeader('HX-Request')) {
            return self::htmxError($response, $message, $errors);
        }
        
        $payload = [
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
            'meta' => [
                'timestamp' => time(),
                'version' => '1.0'
            ]
        ];
        
        return $response
            ->withJson($payload)
            ->withStatus($status);
    }
    
    /**
     * Paginated response
     */
    public static function paginated(
        Request $request,
        Response $response,
        array $items,
        int $page,
        int $perPage,
        int $total
    ): Response {
        $lastPage = (int) ceil($total / $perPage);
        
        $meta = [
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => $lastPage,
                'from' => ($page - 1) * $perPage + 1,
                'to' => min($page * $perPage, $total)
            ]
        ];
        
        return self::success($request, $response, $items, $meta);
    }
    
    /**
     * HTMX response
     */
    private static function htmxResponse(Response $response, mixed $data): Response
    {
        if (is_string($data)) {
            $response->getBody()->write($data);
        } else {
            $response->getBody()->write(json_encode($data));
        }
        
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withHeader('HX-Trigger', 'responseReceived');
    }
    
    /**
     * HTMX error response
     */
    private static function htmxError(Response $response, string $message, array $errors = []): Response
    {
        $html = '<div class="alert alert-error">';
        $html .= '<strong>Error:</strong> ' . htmlspecialchars($message);
        
        if (!empty($errors)) {
            $html .= '<ul>';
            foreach ($errors as $field => $error) {
                $html .= '<li>' . htmlspecialchars($error) . '</li>';
            }
            $html .= '</ul>';
        }
        
        $html .= '</div>';
        
        $response->getBody()->write($html);
        
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withHeader('HX-Trigger', 'errorReceived')
            ->withStatus(422);
    }
}
