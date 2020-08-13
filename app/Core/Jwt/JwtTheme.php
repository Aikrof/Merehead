<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Jwt
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Jwt;

use JsonSerializable;
use Lcobucci\JWT\Token;
use App\Exceptions\InvalidAlgorithmException;

/**
 * Class JwtTheme
 */
class JwtTheme implements JsonSerializable
{
    /**
     * @var JwtService
     */
    private $jwtService;

    /**
     * Jwt Auth token
     *
     * @var Token|null
     */
    protected $token;

    /**
     * Refresh Jwt Auth token
     *
     * @var Token|null
     */
    protected $refreshToken;

    /**
     * JwtTheme constructor.
     *
     * @param JwtService $jwtService
     */
    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Create token
     *
     * @param array     $payload
     * @param int       $ttl
     * @param string    $jti
     *
     * @return static
     *
     * @throws InvalidAlgorithmException
     */
    public function createToken(array $payload, int $ttl = 0, string $jti = null): self
    {
        $this->setToken($this->jwtService->generateToken($payload, $ttl, $jti));

        return $this;
    }

    /**
     * @param array     $payload
     * @param int       $ttl
     * @param string    $jti
     *
     * @return static
     *
     * @throws InvalidAlgorithmException
     */
    public function createRefreshToken(array $payload, int $ttl = 0, string $jti = null): self
    {
        $this->setRefreshToken($this->jwtService->generateToken($payload));

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
       return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->token !== null) {
            $data['token'] = (string) $this->token;
        }

        if ($this->refreshToken !== null) {
            $data['refresh'] = (string) $this->refreshToken;
        }

        return $data;
    }

    /**
     * @return Token|null
     */
    public function getToken(): ?Token
    {
        return $this->token;
    }

    /**
     * @param Token|null    $token
     *
     * @return static
     */
    public function setToken(?Token $token = null): self
    {
        if ($token !== null) {
            $this->token = $token;
        }

        return $this;
    }

    /**
     * @return Token|null
     */
    public function getRefreshToken(): ?Token
    {
        return $this->refreshToken;
    }

    /**
     * @param Token|null    $refreshToken
     *
     * @return static
     */
    public function setRefreshToken(?Token $refreshToken = null): self
    {
        if ($refreshToken !== null) {
            $this->refreshToken = $refreshToken;
        }

        return $this;
    }
}