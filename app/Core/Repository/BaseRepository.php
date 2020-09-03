<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Repository
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Repository;

use App\Core\Entity\BaseEntity;
use App\Core\Entity\EntityInterface;
use App\Core\Query\QueryBuilder;
use Illuminate\Database\Connection;
use Aikrof\Hydrator\Hydrator;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\UndefinedOperatorException;

/**
 * Class BaseRepository
 *
 * @var string $from
 */
abstract class BaseRepository implements RepositoryInterface
{
    /**
     * @var QueryBuilder
     */
    private $query;

    /**
     * BaseRepository constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->query = new QueryBuilder($connection);
    }

    /**
     * Get table, this table have same name with entity.
     *
     * @param string|null $entity
     *
     * @return string
     */
    protected function getTable(string $entity = null): string
    {
        $entity = $entity ?? $this->getEntity();

        if ($entity === null || !\class_exists($entity)) {
            throw new EntityNotFoundException;
        }

        $exp = \explode('\\', $entity);
        $entityName = $exp[\count($exp) - 1];

        $table = $entityName === 'Entity'
            ? $entityName
            : preg_replace('/Entity*$/', '', $entityName);

        return \mb_strtolower($table);
    }

    /**
     * @param string|null $tableName
     *
     * @return QueryBuilder
     */
    protected function getQuery(?string $tableName = null): QueryBuilder
    {
        $this->query->setTable($tableName ?: $this->getTable());

        return $this->query;
    }

    /**
     * Check if value exist in table.
     *
     * @param string    $field
     * @param mixed     $value
     *
     * @return bool
     */
    public function valueExist(string $field, $value): bool
    {
        return $this->getQuery()->where($field, $value)->exists();
    }

    /**
     * Save data to DB.
     *
     * @param EntityInterface $entity
     * @param array $exclude
     * @param array $include
     *
     * @return bool
     */
    public function save(EntityInterface $entity, array $exclude = [], array $include = []): bool
    {
        $rawData = Hydrator::extract($entity, $exclude, true);

        if (!empty($include)) {
            $rawData = \array_merge($rawData, $include);
        }

        return (bool) $this->getQuery()->insert($rawData);
    }

    /**
     * Update data in DB
     *
     * @param EntityInterface $entity
     *
     * @param array $exclude
     * @param array $include
     * @return bool
     */
    public function update(EntityInterface $entity, array $exclude = [], array $include = []): bool
    {
        $rawData = Hydrator::extract($entity, $exclude, true);

        if (!empty($include)) {
            $rawData = \array_merge($rawData, $include);
        }

        return (bool) $this->getQuery()->where('uid', $entity->getUid())->update($rawData);
    }

    /**
     * Delete data from DB
     *
     * @param EntityInterface $entity
     *
     * @return bool
     */
    public function delete(EntityInterface $entity): bool
    {
        return (bool) $this->getQuery()->where('uid', $entity->getUid())->delete();
    }

    /**
     * Get data from table, by key - value.
     *
     * @param string    $fieldValue
     * @param string    $fieldKey
     *
     * @return EntityInterface|null
     *
     * @throws EntityNotFoundException
     * @throws UndefinedOperatorException
     */
    public function find(string $fieldValue, string $fieldKey = 'uid'): ?EntityInterface
    {
        return $this->getQuery()->where($fieldKey, $fieldValue)->first();
    }

    /**
     * Get all data from table, by key - value.
     *
     * @param string $fieldValue
     * @param string $fieldKey
     *
     * @return EntityInterface[]|null
     */
    public function findAll(string $fieldValue, string $fieldKey = 'uid'): ?array
    {
        return $this->getQuery()->where($fieldKey, $fieldValue)->get();
    }

    /**
     * @param string $uid
     *
     * @return EntityInterface|null
     *
     * @throws EntityNotFoundException
     * @throws UndefinedOperatorException
     */
    public function findByUid(string $uid): ?EntityInterface
    {
        return $this->getQuery()->find($uid);
    }

    /**
     * Get all data from table
     *
     * @return BaseEntity[]
     */
    public function getAll(): array
    {
        return $this->getQuery()->get();
    }
}
