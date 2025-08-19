<?php
/**
 * @file TestErrorsAction.php
 * @purpose Test action for verifying HTMX error display
 * @ai-context Test endpoint for debugging login error display
 * @ai-patterns Testing, debugging, error display
 */

declare(strict_types=1);

namespace App\Actions\Api\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Test errors action
 * 
 * @ai-purpose Test endpoint for verifying HTMX error display
 * @ai-endpoint GET /api/v1/auth/test-errors
 * @ai-auth none
 */
final class TestErrorsAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        // Return test error messages
        ob_start();
        $errors = [
            'email' => ['Invalid email format'],
            'password' => ['Password is required'],
            'general' => ['Authentication failed - this is a test']
        ];
        include __DIR__ . '/../../../../templates/fragments/login-errors.php';
        $html = ob_get_clean();
        
        $response->getBody()->write($html);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
}
