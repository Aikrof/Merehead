<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Entity
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Entity;

use JsonSerializable;

/**
 * Interface EntityInterface
 */
interface EntityInterface extends JsonSerializable
{
    /**
     * Generate Uid, if it is not set.
     *
     * @return static
     */
    public function generateUid(): self;

    /**
     * Validate uid.
     *
     * @param string $uid
     *
     * @return bool
     */
    public function validateUid(string $uid): bool;

    /**
     * Hide fields
     *
     * @return array
     */
    public function hide(): array;
}
