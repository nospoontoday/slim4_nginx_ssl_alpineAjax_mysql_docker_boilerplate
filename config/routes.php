<?php
/**
 * @file routes.php
 * @purpose Route definitions
 * @ai-context Main routing configuration
 */

declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Middleware\CorsMiddleware;
use App\Middleware\ApiAuthenticationMiddleware;
use App\Middleware\RateLimitingMiddleware;

return function (App $app) {
    // Web routes
    $app->get('/', 'App\Actions\Web\HomeAction');
    $app->get('/login', 'App\Actions\Web\LoginAction');
    $app->get('/register', 'App\Actions\Web\RegisterAction');
    $app->get('/dashboard', 'App\Actions\Web\DashboardAction');
    $app->get('/database-test', 'App\Actions\Web\DatabaseTestAction');
    
    // API routes
    $app->group('/api/v1', function (RouteCollectorProxy $group) {
        // Public endpoints
        $group->post('/auth/login', 'App\Actions\Api\Auth\LoginAction');
        $group->post('/auth/register', 'App\Actions\Api\Auth\RegisterAction');
        $group->post('/auth/refresh', 'App\Actions\Api\Auth\RefreshAction');
        
        // Database health monitoring (public endpoint)
        $group->get('/health/database', 'App\Actions\Api\DatabaseHealthAction');
        
        // Protected endpoints
        $group->group('', function (RouteCollectorProxy $group) {
            // Users
            $group->get('/users', 'App\Actions\Api\Users\ListAction');
            $group->get('/users/{id:[0-9]+}', 'App\Actions\Api\Users\GetAction');
            $group->post('/users', 'App\Actions\Api\Users\CreateAction');
            $group->put('/users/{id:[0-9]+}', 'App\Actions\Api\Users\UpdateAction');
            $group->delete('/users/{id:[0-9]+}', 'App\Actions\Api\Users\DeleteAction');
            
            // Posts
            $group->get('/posts', 'App\Actions\Api\Posts\ListAction');
            $group->get('/posts/{id:[0-9]+}', 'App\Actions\Api\Posts\GetAction');
            $group->post('/posts', 'App\Actions\Api\Posts\CreateAction');
            $group->put('/posts/{id:[0-9]+}', 'App\Actions\Api\Posts\UpdateAction');
            $group->delete('/posts/{id:[0-9]+}', 'App\Actions\Api\Posts\DeleteAction');
            
            // Search
            $group->get('/search', 'App\Actions\Api\Search\SearchAction');
            
        })->add(new ApiAuthenticationMiddleware());
        
    })->add(new CorsMiddleware())
      ->add(new RateLimitingMiddleware());
    
    // HTMX-specific routes for dynamic content
    $app->group('/htmx', function (RouteCollectorProxy $group) {
        $group->get('/users-table', 'App\Actions\Htmx\UsersTableAction');
        $group->get('/posts-list', 'App\Actions\Htmx\PostsListAction');
        $group->get('/search-results', 'App\Actions\Htmx\SearchResultsAction');
    });
};