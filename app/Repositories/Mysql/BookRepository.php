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
use App\Repositories\Interfaces\BookRepositoryInterface;

/**
 * Class BookRepository
 */
class BookRepository extends BaseRepository implements BookRepositoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getEntity(): ?string
    {
        return BookEntity::class;
    }

    /**
     * {@inheritDoc}
     *
     * @param AuthorEntity $author
     *
     * @return BookEntity[]
     */
    public function getAllAuthorBooks(AuthorEntity $author): array
    {
        $bookTable = $this->getTable();
        $userTable = $this->getTable(UserEntity::class);
        $authorTable = $this->getTable(AuthorEntity::class);

        $query = $this->getQuery()
            ->where('author_uid', $author->getUid())
            ->join(
                $userTable,
                "{$bookTable}.user_uid",
                "{$userTable}.uid"
            )
            ->join($authorTable , "{$bookTable}.author_uid", "{$authorTable}.uid")
            ->get();

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @param BookEntity    $entity
     * @param array         $exclude
     * @param array         $include
     *
     * @return bool
     */
    public function saveBook(BookEntity $entity, array $exclude = [], array $include = []): bool
    {
        $exclude = $exclude ?: ['user', 'author'];
        $include = $include ?: [
            'user_uid' => $entity->getUser()->getUid(),
            'author_uid' => $entity->getAuthor()->getUid(),
        ];

        return $this->save($entity, $exclude, $include);
    }

    /**
     * {@inheritDoc}
     *
     * @param BookEntity $entity
     *
     * @return bool
     */
    public function updateBook(BookEntity $entity): bool
    {
        $exclude = ['user', 'author', 'uid'];
        $include = [
//            'user_uid' => $entity->getUser()->getUid(),
//            'author_uid' => $entity->getAuthor()->getUid(),
        ];

        return $this->update($entity, $exclude, $include);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $uid
     *
     * @return BookEntity|null
     */
    public function findBook(string $uid): ?BookEntity
    {
        $query = $this->getQuery()
            ->where('book.uid', $uid)
            ->join('user', 'user.uid', 'book.user_uid')
            ->join('author', 'author.uid', 'book.author_uid')
            ->first();

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $userUid
     *
     * @return BookEntity[]
     */
    public function findAllUserBooks(string $userUid): array
    {
        $query = $this->getQuery()
            ->where('user_uid', $userUid)
            ->join('user', 'user.uid', 'book.user_uid')
            ->join('author', 'author.uid', 'book.author_uid')
            ->get();

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $userUid
     * @param string $bookUid
     *
     * @return BookEntity
     */
    public function findUserBook(string $userUid, string $bookUid): BookEntity
    {
        $query = $this->getQuery()
            ->where('user_uid', $userUid)
            ->where('book.uid', $bookUid)
            ->join('user', 'user.uid', 'book.user_uid')
            ->join('author', 'author.uid', 'book.author_uid')
            ->first();

        return $query;
    }
}
