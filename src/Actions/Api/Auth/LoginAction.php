<?php
/**
 * @file LoginAction.php
 * @purpose Handles login API requests
 * @ai-context API action for user authentication
 * @ai-patterns Authentication, JWT tokens, validation
 */

declare(strict_types=1);

namespace App\Actions\Api\Auth;

use App\Services\AuthService;
use App\Validators\LoginValidator;
use App\Http\ApiResponse;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthenticationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Login API action
 * 
 * @ai-purpose Authenticate user and return JWT token
 * @ai-endpoint POST /api/v1/auth/login
 * @ai-auth none
 * @ai-validation LoginValidator
 */
final class LoginAction
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly LoginValidator $validator
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $data = $request->getParsedBody() ?? [];
            
            // Validate input
            $validatedData = $this->validator->validate($data);
            
            // Attempt authentication
            $authResult = $this->authService->authenticate(
                $validatedData['email'],
                $validatedData['password'],
                $validatedData['remember'] ?? false
            );
            
            // For HTMX requests, return success fragment
            if ($request->hasHeader('HX-Request')) {
                return $response
                    ->withHeader('HX-Redirect', '/dashboard')
                    ->withStatus(200);
            }
            
            // For API requests, return JSON with token
            return ApiResponse::success($request, $response, [
                'user' => $authResult['user'],
                'token' => $authResult['token'],
                'expires_at' => $authResult['expires_at']
            ]);
            
        } catch (ValidationException $e) {
            if ($request->hasHeader('HX-Request')) {
                ob_start();
                $errors = $e->getErrors();
                include __DIR__ . '/../../../../templates/fragments/login-errors.php';
                $html = ob_get_clean();
                
                $response->getBody()->write($html);
                return $response
                    ->withHeader('Content-Type', 'text/html')
                    ->withHeader('HX-Retarget', '#login-errors')
                    ->withStatus(422);
            }
            
            return ApiResponse::error($request, $response, 'Validation failed', $e->getErrors(), 422);
            
        } catch (AuthenticationException $e) {
            if ($request->hasHeader('HX-Request')) {
                ob_start();
                $errors = ['general' => [$e->getMessage()]];
                include __DIR__ . '/../../../../templates/fragments/login-errors.php';
                $html = ob_get_clean();
                
                $response->getBody()->write($html);
                return $response
                    ->withHeader('Content-Type', 'text/html')
                    ->withHeader('HX-Retarget', '#login-errors')
                    ->withStatus(401);
            }
            
            return ApiResponse::error($request, $response, $e->getMessage(), [], 401);
        }
    }
}