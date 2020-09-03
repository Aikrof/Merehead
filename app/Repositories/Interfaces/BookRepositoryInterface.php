<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Repositories\Interfaces
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Repositories\Interfaces;

use App\Core\Repository\RepositoryInterface;
use App\Entities\AuthorEntity;
use App\Entities\BookEntity;

/**
 * Interface BookRepositoryInterface
 */
interface BookRepositoryInterface extends RepositoryInterface
{
    /**
     * Get all books from author
     *
     * @param AuthorEntity $author
     *
     * @return array
     */
    public function getAllAuthorBooks(AuthorEntity $author): array;

    /**
     * Save book data to DB.
     *
     * @param BookEntity $entity
     * @param array $exclude
     * @param array $include
     *
     * @return bool
     */
    public function saveBook(BookEntity $entity, array $exclude = [], array $include = []): bool;

    /**
     * Update book
     *
     * @param BookEntity $entity
     *
     * @return bool
     */
    public function updateBook(BookEntity $entity): bool;

    /**
     * Find book by uid.
     *
     * @param string $uid
     *
     * @return BookEntity|null
     */
    public function findBook(string $uid): ?BookEntity;

    /**
     * Find all user books by user uid.
     *
     * @param string $userUid
     *
     * @return BookEntity[]
     */
    public function findAllUserBooks(string $userUid): array;

    /**
     * Find user book by user uid and book uid.
     *
     * @param string $userUid
     * @param string $bookUid
     *
     * @return BookEntity
     */
    public function findUserBook(string $userUid, string $bookUid): BookEntity;
}
