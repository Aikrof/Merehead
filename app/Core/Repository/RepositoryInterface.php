<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Repository
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Repository;

/**
 * Interface RepositoryInterface
 */
interface RepositoryInterface
{
    /**
     * Get entity which matches a table in the database.
     *
     * @return string|null
     */
    public function getEntity(): ?string;
}