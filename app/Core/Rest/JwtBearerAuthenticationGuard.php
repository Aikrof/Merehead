<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Rest
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Rest;

use App\Core\Jwt\JwtService;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Lcobucci\JWT\Token;

/**
 * Class AuthenticationGuard
 */
class JwtBearerAuthenticationGuard implements Guard
{
    /**
     * @var UserProvider
     */
    private $provider;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * @var JwtService
     */
    private $jwtService;

    /**
     * The currently authenticated user.
     *
     * @var Authenticatable
     */
    protected $user;

    /**
     * AuthenticationGuard constructor.
     *
     * @param UserProvider                  $provider
     * @param Request                       $request
     * @param JwtService|null               $jwtService
     * @param UserRepositoryInterface|null  $repository
     */
    public function __construct(
        UserProvider $provider,
        Request $request,
        JwtService $jwtService = null,
        UserRepositoryInterface $repository = null
    ) {
        $this->provider = $provider;
        $this->request = $request;

        $this->jwtService = $jwtService ?: \App::make(JwtService::class);
        $this->repository = $repository ?: \App::make(UserRepositoryInterface::class);
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function check(): bool
    {
        return (bool) $this->user();
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function guest(): bool
    {
        return $this->check();
    }

    /**
     * {@inheritDoc}
     *
     * @return Authenticatable|null
     */
    public function user(): ?Authenticatable
    {
        if ($this->user !== null) {
            return $this->user;
        }

        if (!$this->getUserFromBearerToken()) {
            return null;
        }

        return $this->user;
    }

    /**
     * Get user from request bearer token.
     *
     * @return bool true - if user was set, false - if token is not valid or user is not exist
     */
    private function getUserFromBearerToken(): bool
    {
        $token = $this->getTokenFromBearer();
        if ($token === null || $this->jwtService->validate($token) === false) {
            return false;
        }

        $uid = $token->getClaim('uid');
        $jti = $token->getClaim('jti');

        $user = $this->repository->findByUid($uid);
        if ($user === null || $user->getJwtUid() !== $jti) {
            return false;
        }

        $this->setUser($user);

        return true;
    }

    /**
     * Get token from encoded string
     *
     * @return Token|null
     */
    private function getTokenFromBearer(): ?Token
    {
        $bearer = $this->request->bearerToken();
        if ($bearer === null) {
            return null;
        }

        try {
            $token = $this->jwtService->parse($bearer);
        } catch (\InvalidArgumentException $e ) {
            return null;
        } catch (\RuntimeException $e) {
           return null;
        }

        return $token;
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function id(): ?string
    {
        return $this->user !== null ? $this->user->getAuthIdentifier() : null;
    }

    /**
     * {@inheritDoc}
     *
     * @param array $credentials
     *
     * @return bool
     */
    public function validate(array $credentials = []): bool
    {
    }

    /**
     * {@inheritDoc}
     *
     * @param Authenticatable $user
     */
    public function setUser(Authenticatable $user): void
    {
        $this->user = $user;
    }

    /**
     * Get the user provider used by the guard.
     *
     * @return \Illuminate\Contracts\Auth\UserProvider
     */
    public function getProvider(): UserProvider
    {
        return $this->provider;
    }
}
