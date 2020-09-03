<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Entities
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Entity;

use Aikrof\Hydrator\Traits\HydratorTrait;
use Ramsey\Uuid\Uuid;

/**
 * Class BaseEntity
 *
 * This class is base for all entities classes.
 */
abstract class BaseEntity implements EntityInterface
{
    use HydratorTrait, EntityTrait;

    /**
     * @var string
     */
    protected $uid;

    /**
     * @var array
     */
    protected $hide;

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     *
     * @return static
     */
    public function setUid(string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Generate Uid, if it is not set.
     *
     * @return static
     */
    public function generateUid(): self
    {
        if (empty($this->uid)) {
            $this->uid = (string)Uuid::uuid4();
        }

        return $this;
    }

    /**
     * Validate uid.
     *
     * @param string $uid
     *
     * @return bool
     */
    public function validateUid(string $uid): bool
    {
        return Uuid::isValid($uid);
    }

    /**
     * Hide fields
     *
     * @return array
     */
    public function hide(): array
    {
        return [];
    }
}
