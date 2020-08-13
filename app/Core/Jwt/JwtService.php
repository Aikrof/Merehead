<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Jwt
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Jwt;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;
use App\Exceptions\InvalidAlgorithmException;
use Lcobucci\JWT\ValidationData;

/**
 * Class JwtService
 */
class JwtService
{
    /**
     * @var JwtConfig
     */
    private $jwtConfig;

    /**
     * Supported algorithms
     */
    public const ALGORITHMS = [
        'HS256' => \Lcobucci\JWT\Signer\Hmac\Sha256::class,
        'HS384' => \Lcobucci\JWT\Signer\Hmac\Sha384::class,
        'HS512' => \Lcobucci\JWT\Signer\Hmac\Sha512::class,
        'RS256' => \Lcobucci\JWT\Signer\Rsa\Sha256::class,
        'RS384' => \Lcobucci\JWT\Signer\Rsa\Sha384::class,
        'RS512' => \Lcobucci\JWT\Signer\Rsa\Sha512::class,
    ];

    /**
     * JwtService constructor.
     *
     * @param JwtConfig $jwtConfig
     */
    public function __construct(JwtConfig $jwtConfig)
    {
        $this->jwtConfig = $jwtConfig ?: new JwtConfig();
    }

    /**
     * Get JwtConfig.
     *
     * @return JwtConfig
     */
    public function getJwtConfig(): JwtConfig
    {
        return $this->jwtConfig;
    }

    /**
     * Get all supported algorithms.
     *
     * @return array
     */
    public static function getSupportedAlgs(): array
    {
        return array_keys(self::ALGORITHMS);
    }

    /**
     * Check if algorithm supported
     *
     * @param string $algorithm
     *
     * @return bool
     */
    public static function isAlgSupported(string $algorithm): bool
    {
        return (bool) \in_array($algorithm, self::getSupportedAlgs(), true);
    }

    /**
     * Generate Jwt token.
     *
     * @param array     $payload token payload data
     * @param int       $ttl token time to live
     * @param string    $jti token unique identifier
     *
     * @return Token
     *
     * @throws InvalidAlgorithmException
     */
    public function generateToken(array $payload, int $ttl = 0, string $jti = null): Token
    {
        $config = $this->getJwtConfig();

        if ($ttl !== 0) {
            $config->setTimeToLive($ttl);
        }

        $builder = (new Builder())
            ->withHeader('typ', $config->getType())
            ->issuedBy($config->getIssuer())
            ->permittedFor($config->getAudience())
            ->issuedAt($config->getIssuedAt())
            ->withClaim('ttl', $config->getTimeToLive())
            ->canOnlyBeUsedAfter($config->getCanOnlyBeUsedAfter())
            ->expiresAt($config->getExpiredAt());

        $jti = $jti ?: $config->getTokenUid();
        if ($jti !== null) {
            $builder->identifiedBy($jti);
        }

        foreach ($payload as $key => $value) {
            $builder->withClaim($key, $value);
        }

        $algorithm = $config->getAlgorithm();
        if (!self::isAlgSupported($algorithm)) {
            throw new InvalidAlgorithmException(
                'Algorithm: ' . $algorithm . ' is not supported.'
            );
        }

        $algorithmClass = self::ALGORITHMS[$algorithm];

        $signer = new $algorithmClass;
        $key = new Key($config->getKey());

        return $builder->getToken($signer, $key);
    }

    /**
     * Validate token
     *
     * @param Token $token
     *
     * @return bool
     */
    public function validate(Token $token): bool
    {
        if (!self::isAlgSupported($token->getHeader('alg'))) {
            return false;
        }

        if (!$token->getClaim('uid')) {
            return false;
        }

        $validateData = \App::make(ValidationData::class);

        $validateData->setIssuer($this->jwtConfig->getIssuer());
        $validateData->setAudience($this->jwtConfig->getAudience());

        if (!$token->validate($validateData)) {
            return false;
        }

        return $this->verify($token);
    }

    /**
     * Validate token signature
     *
     * @param Token $token
     *
     * @return bool
     */
    public function verify(Token $token): bool
    {
        $algorithmClass = self::ALGORITHMS[$token->getHeader('alg')];

        $signer = new $algorithmClass;
        $key = new Key($this->jwtConfig->getKey());

        return $token->verify($signer, $key);
    }

    /**
     * Parse given bearer
     *
     * @param string $bearer
     * @return Token
     */
    public function parse(string $bearer): Token
    {
        return (\App::make(Parser::class))->parse($bearer);
    }
}