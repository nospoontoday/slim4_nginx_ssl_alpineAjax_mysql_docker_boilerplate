<?php

declare(strict_types=1);

namespace App\Actions\Web;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\PhpRenderer;

/**
 * Web action for database connection testing page
 * 
 * @ai-purpose Serve database test interface for users
 * @ai-dependencies PhpRenderer
 * @ai-pattern Web action pattern
 */
final class DatabaseTestAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly PhpRenderer $view
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->view->render(
            new \Slim\Psr7\Response(),
            'pages/database-test.php',
            [
                'title' => 'Database Connection Test',
                'description' => 'Monitor and test your database connections'
            ]
        );
    }
}
