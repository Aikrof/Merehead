<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Jwt
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Jwt;

/**
 * Class JwtConfig
 *
 * Basic config for Jwt token
 *
 * @see https://jwt.io/introduction/
 */
class JwtConfig
{
    /**
     * Type of token (typ)
     *
     * @var string
     */
    private $type;

    /**
     * Signature algorithm (alg)
     *
     * @var string
     */
    private $algorithm;

    /**
     * Who created token (iss)
     *
     * @var string
     */
    private $issuer;

    /**
     * Token intended for (aud)
     *
     * @var string
     */
    private $audience;

    /**
     * Token created time (iat)
     *
     * @var int
     */
    private $issuedAt;

    /**
     * Token time to live (ttl)
     *
     * @var int
     */
    private $timeToLive;

    /**
     * Can only be used after time (nbf)
     *
     * @var int
     */
    private $canOnlyBeUsedAfter;

    /**
     * Not valid after (exp)
     *
     * @var int
     */
    private $expiredAt;

    /**
     * Token unique identifier (jti)
     *
     * @var string
     */
    private $tokenUid;

    /**
     * Private key to generate token signature
     *
     * @var string
     */
    private $key;

    /**
     * JwtConfig constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Base config for Jwt Auth
     */
    private function init(): void
    {
        $this
            ->setType(env('JWT_TYPE') ?: 'JWT')
            ->setAlgorithm(env('JWT_ALG') ?: 'HS256')
            ->setIssuer(env('APP_NAME') ?: 'Local')
            ->setAudience(env('JWT_AUD') ?: env('APP_URL'))
            ->setIssuedAt()
            ->setTimeToLive(env('JWT_TTL'))
            ->setCanOnlyBeUsedAfter(env("JWT_NBF"))
            ->setExpiredAt(env('JWT_EXP'))
            ->setKey(env('JWT_KEY') ?: env('APP_KEY'));
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return static
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    /**
     * @param string $algorithm
     *
     * @return static
     */
    public function setAlgorithm(string $algorithm): self
    {
        $this->algorithm = $algorithm;

        return $this;
    }

    /**
     * @return string
     */
    public function getIssuer(): string
    {
        return $this->issuer;
    }

    /**
     * @param string $issuer
     *
     * @return static
     */
    public function setIssuer(string $issuer): self
    {
        $this->issuer = $issuer;

        return $this;
    }

    /**
     * @return string
     */
    public function getAudience(): string
    {
        return $this->audience;
    }

    /**
     * @param string $audience
     *
     * @return static
     */
    public function setAudience(string $audience): self
    {
        $this->audience = $audience;

        return $this;
    }

    /**
     * @return int
     */
    public function getIssuedAt(): int
    {
        return $this->issuedAt;
    }

    /**
     * @param int $issuedAt
     *
     * @return static
     */
    public function setIssuedAt(int $issuedAt = null): self
    {
        $issuedAt = $issuedAt ?: \time();

        $this->issuedAt = $issuedAt;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeToLive(): int
    {
        return $this->timeToLive;
    }

    /**
     * @param int $timeToLive
     *
     * @return static
     */
    public function setTimeToLive(int $timeToLive = null): self
    {
        $timeToLive = $timeToLive ?: 86400;

        $this->timeToLive = $timeToLive;

        return $this;
    }

    /**
     * @return int
     */
    public function getCanOnlyBeUsedAfter(): int
    {
        return $this->canOnlyBeUsedAfter ?? $this->getIssuedAt();
    }

    /**
     * @param int $canOnlyBeUsedAfter
     *
     * @return static
     */
    public function setCanOnlyBeUsedAfter(int $canOnlyBeUsedAfter = null): self
    {
        if ($canOnlyBeUsedAfter !== null) {
            $this->canOnlyBeUsedAfter = $canOnlyBeUsedAfter;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getExpiredAt(): int
    {
        return $this->expiredAt ?? $this->getIssuedAt() + $this->getTimeToLive();
    }

    /**
     * @param int $expiredAt
     *
     * @return static
     */
    public function setExpiredAt(int $expiredAt = null): self
    {
        if ($expiredAt !== null) {
            $this->expiredAt = $expiredAt;
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTokenUid(): ?string
    {
        return $this->tokenUid;
    }

    /**
     * @param string $tokenUid
     *
     * @return static
     */
    public function setTokenUid(string $tokenUid): self
    {
        $this->tokenUid = $tokenUid;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return static
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }
}