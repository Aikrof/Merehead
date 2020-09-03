<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Entities
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Entities;

use App\Core\Entity\BaseEntity;

/**
 * Class AuthorEntity
 *
 * @var string $uid
 */
class AuthorEntity extends BaseEntity
{
    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return static
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return static
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
}
