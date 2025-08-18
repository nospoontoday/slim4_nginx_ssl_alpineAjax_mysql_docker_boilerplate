<?php

declare(strict_types=1);

namespace App\Actions\Web;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response as SlimResponse;

/**
 * @ai-endpoint GET /
 * @ai-auth none
 */
final class HomeAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $response = new SlimResponse();
        $response->getBody()->write('<h1>Welcome to Our App</h1><p>A modern PHP application built with Slim 4, HTMX, and Alpine.js</p>');
        return $response->withHeader('Content-Type', 'text/html');
    }
}
