<?php


namespace tests\auth;


use app\dto\Auth\LoginDto;
use app\dto\Auth\RegisterDto;
use app\entities\User;
use app\entities\UserAuthEntity;
use app\middlewares\AccessMiddleware;
use app\repositories\AuthRepository\AuthRepository;
use app\Requests\ResponseCodes;
use app\services\AuthService\AuthService;
use app\services\AuthService\JwtService;
use PHPUnit\Framework\MockObject\MockObject;
use tests\auth\TestUser;
use tests\auth\AccessMiddlewareTest;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    private AuthService $authService;
    private EntityManager $entityManager;
    private JwtService $jwtService;

    protected function setUp(): void
    {
        $this->authService = app()->config('container')->get(AuthService::class);
        $this->jwtService = app()->config('container')->get(JwtService::class);
        $this->entityManager = app()->config('entityManager');
    }


    private function registerUser()
    {
        $registerData = new RegisterDto([
            'login' => 'John Doe',
            'email' => 'jofdsfdshndoe@example.com',
            'password' => 'password123'
        ]);

        return $this->authService->register($registerData);
    }

    public function testRegisterWithValidData(): void
    {
        $this->entityManager->beginTransaction();

        $response = $this->registerUser();

        $this->assertEquals(ResponseCodes::OK, $response['code']);
        $this->assertNotNull($response['result']);
        $this->entityManager->rollback();
    }


    public function testRegisterWithExistUser(): void
    {
        $this->entityManager->beginTransaction();

        $this->registerUser();

        $response = $this->registerUser();

        $this->assertEquals(ResponseCodes::USER_ALREADY_EXISTS, $response['code']);
        $this->entityManager->rollback();
    }

    public static function loginDataProvider()
    {
        return [
            [
                new LoginDto([
                    'email' => 'testuser@example.com',
                    'password' => 'testPassword'
                ]),
                1,
            ],
            [
                new LoginDto([
                    'email' => 'testuser@example.com',
                    'password' => 'testPasswor'
                ]),
                null
            ]
        ];
    }

    /**
     * @dataProvider loginDataProvider
     */
    public function testLogin($loginDto, $expectedResult)
    {
        $user = new TestUser(1);
        $user->setEmail('testuser@example.com');
        $user->setPassword('testPassword');

        $authRepositoryMock = $this->getMockBuilder(AuthRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $authRepositoryMock->method('getUser')
            ->willReturn($user);

        $authService = new AuthService($authRepositoryMock);
        $result = $authService->login($loginDto);
        $this->assertEquals($expectedResult, $result);
    }

    public function testAccessMiddlewareWithAccessToken()
    {
        $user = new TestUser(1);
        $user->setEmail('testuser@example.com');
        $user->setPassword('testPassword');
        $accessToken = $this->jwtService->generateAccessToken(1);

        $authRepositoryMock = $this->getMockBuilder(AuthRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $authRepositoryMock->method('getUserById')
            ->willReturn($user);
        $middleware = new AccessMiddlewareTest($accessToken,'random', $authRepositoryMock);
        try {
            $result = $middleware->call();
            $user = UserAuthEntity::getInstance()->getUser();
            $this->assertNull($result);
            $this->assertInstanceOf(TestUser::class, $user);
        } catch (\Exception $exception) {
            $this->fail($exception->getMessage());
        }
    }

}