<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Repositories\Interfaces
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Repositories\Interfaces;

use App\Core\Repository\RepositoryInterface;
use App\Entities\UserEntity;

/**
 * Interface UserRepositoryInterface
 */
interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Save valid token uid for user
     *
     * @param UserEntity $entity
     *
     * @return bool
     */
    public function updateTokenUid(UserEntity $entity): bool;
}