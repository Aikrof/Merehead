<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Repositories\Mysql
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Repositories\Mysql;

use App\Core\Repository\BaseRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Entities\UserEntity;

/**
 * Class UserRepository
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getEntity(): ?string
    {
        return UserEntity::class;
    }

    /**
     * {@inheritDoc}
     *
     * @param UserEntity $entity
     *
     * @return bool
     */
    public function updateTokenUid(UserEntity $entity): bool
    {
       $query = $this->getQuery()
           ->where('uid', $entity->getUid())
           ->update(['jwtUid' => $entity->getJwtUid()]);

        return (bool) $query;
    }
}
