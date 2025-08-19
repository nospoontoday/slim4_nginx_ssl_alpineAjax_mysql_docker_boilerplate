<?php
/**
 * @file LoginAction.php
 * @purpose Handles login page display
 * @ai-context Web action for login page rendering
 * @ai-patterns Page rendering, form display
 */

declare(strict_types=1);

namespace App\Actions\Web;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Login page action
 * 
 * @ai-purpose Display login form and handle authentication
 * @ai-endpoint GET /login
 * @ai-auth none
 */
final class LoginAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        // Check if user is already logged in
        $session = $request->getAttribute('session');
        if ($session && isset($session['user_id'])) {
            return $response
                ->withHeader('Location', '/dashboard')
                ->withStatus(302);
        }

        // Get any flash messages (errors, success messages)
        $flashMessages = $session['flash'] ?? [];
        unset($session['flash']);

        // Render login page
        ob_start();
        include __DIR__ . '/../../../templates/pages/login.php';
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html');
    }
}