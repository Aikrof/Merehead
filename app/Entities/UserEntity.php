<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Entities
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Entities;

use App\Core\Entity\BaseEntity;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Core\Rest\Traits\AuthenticatableTrait;

/**
 * Class UserEntity
 *
 * @var string $uid
 */
class UserEntity extends BaseEntity implements Authenticatable
{
    use AuthenticatableTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string|null
     */
    protected $jwtUid;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return static
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     *
     * @return static
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getJwtUid(): ?string
    {
        return $this->jwtUid;
    }

    /**
     * @param string|null $jwtUid
     *
     * @return static
     */
    public function setJwtUid(?string $jwtUid): self
    {
        $this->jwtUid = $jwtUid;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function hide(): array
    {
        return ['password', 'jwtUid'];
    }
}
