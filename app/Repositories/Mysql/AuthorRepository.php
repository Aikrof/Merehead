<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Repositories\Mysql
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Repositories\Mysql;

use App\Core\Repository\BaseRepository;
use App\Entities\AuthorEntity;
use App\Entities\BookEntity;
use App\Entities\UserEntity;
use App\Repositories\Interfaces\AuthorRepositoryInterface;

class AuthorRepository extends BaseRepository implements AuthorRepositoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getEntity(): ?string
    {
        return AuthorEntity::class;
    }
}
