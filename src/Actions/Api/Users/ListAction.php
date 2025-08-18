<?php

declare(strict_types=1);

namespace App\Actions\Api\Users;

use App\Http\ApiResponse;
use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @ai-endpoint GET /api/v1/users
 * @ai-auth required
 * @ai-validation none
 */
final class ListAction
{
    public function __construct(
        private readonly UserService $service
    ) {}
    
    public function __invoke(Request $request, Response $response): Response
    {
        $queryParams = $request->getQueryParams();
        $page = (int) ($queryParams['page'] ?? 1);
        $perPage = (int) ($queryParams['per_page'] ?? 20);
        $search = $queryParams['search'] ?? '';
        
        $filters = [
            'search' => $search,
            'page' => $page,
            'per_page' => $perPage
        ];
        
        $result = $this->service->getUsers($filters);
        
        return ApiResponse::paginated(
            $request,
            $response,
            $result['users'],
            $page,
            $perPage,
            $result['total']
        );
    }
}
