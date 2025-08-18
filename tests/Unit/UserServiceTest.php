<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\UserService;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

/**
 * UserService Unit Tests
 * 
 * @ai-purpose Test business logic for user operations
 * @ai-pattern Unit testing with mocked dependencies
 */
class UserServiceTest extends TestCase
{
    private UserService $service;
    private UserRepository $repository;
    private UserValidator $validator;
    private Logger $logger;
    
    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepository::class);
        $this->validator = $this->createMock(UserValidator::class);
        $this->logger = $this->createMock(Logger::class);
        
        $this->service = new UserService(
            $this->repository,
            $this->validator,
            $this->logger
        );
    }
    
    /**
     * @test
     */
    public function it_creates_user_with_valid_data(): void
    {
        // Arrange
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'Secret123'
        ];
        
        $validatedData = $data + ['uuid' => 'test-uuid'];
        $userId = 1;
        
        $this->validator
            ->expects($this->once())
            ->method('validateCreate')
            ->with($data)
            ->willReturn($validatedData);
        
        $this->repository
            ->expects($this->once())
            ->method('findByEmail')
            ->with('john@example.com')
            ->willReturn(null);
        
        $this->repository
            ->expects($this->once())
            ->method('create')
            ->willReturn($userId);
        
        $this->repository
            ->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn(['id' => $userId, 'email' => 'john@example.com']);
        
        $this->logger
            ->expects($this->once())
            ->method('info');
        
        // Act
        $user = $this->service->createUser($data);
        
        // Assert
        $this->assertArrayHasKey('id', $user);
        $this->assertEquals($userId, $user['id']);
        $this->assertEquals('john@example.com', $user['email']);
    }
    
    /**
     * @test
     */
    public function it_throws_exception_when_email_exists(): void
    {
        // Arrange
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'existing@example.com',
            'password' => 'Secret123'
        ];
        
        $this->validator
            ->expects($this->once())
            ->method('validateCreate')
            ->willReturn($data);
        
        $this->repository
            ->expects($this->once())
            ->method('findByEmail')
            ->with('existing@example.com')
            ->willReturn(['id' => 1, 'email' => 'existing@example.com']);
        
        // Act & Assert
        $this->expectException(\App\Exceptions\DuplicateEmailException::class);
        $this->service->createUser($data);
    }
    
    /**
     * @test
     */
    public function it_gets_users_with_filters(): void
    {
        // Arrange
        $filters = ['search' => 'john', 'page' => 1, 'per_page' => 20];
        $users = [
            ['id' => 1, 'first_name' => 'John', 'email' => 'john@example.com'],
            ['id' => 2, 'first_name' => 'Johnny', 'email' => 'johnny@example.com']
        ];
        $total = 2;
        
        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->with($filters)
            ->willReturn($users);
        
        $this->repository
            ->expects($this->once())
            ->method('count')
            ->with(['search' => 'john'])
            ->willReturn($total);
        
        $this->logger
            ->expects($this->once())
            ->method('info');
        
        // Act
        $result = $this->service->getUsers($filters);
        
        // Assert
        $this->assertArrayHasKey('users', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertEquals($users, $result['users']);
        $this->assertEquals($total, $result['total']);
    }
}
