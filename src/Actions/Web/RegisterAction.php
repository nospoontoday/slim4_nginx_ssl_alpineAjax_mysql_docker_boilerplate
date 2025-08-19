<?php

declare(strict_types=1);

namespace App\Actions\Web;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

/**
 * @ai-endpoint GET /register
 * @ai-auth none
 */
final class RegisterAction
{
    public function __construct(
        private readonly PhpRenderer $renderer
    ) {}

    public function __invoke(Request $request, Response $response): Response
    {
        $data = [
            'title' => 'Sign Up | Sugar Clone',
            'description' => 'Join Sugar Clone, the premium sugar dating platform. Create your profile and connect with attractive sugar babies and generous sugar daddies!'
        ];

        return $this->renderer->render($response, 'pages/register.php', $data);
    }
}
