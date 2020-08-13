<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Repository
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Repository;

use App\Core\Entity\EntityInterface;
use DB;
use Aikrof\Hydrator\Hydrator;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\UndefinedOperatorException;

/**
 * Class BaseRepository
 */
abstract class BaseRepository implements RepositoryInterface
{
    /**
     * @var DB
     */
    protected $connection;

    /**
     * @var string[]
     */
    private $operators = [
        'update',
        'select',
        'create',
        'insert',
        'delete',
    ];

    /**
     * Repository constructor.
     *
     * @param DB $db
     */
    public function __construct(DB $db)
    {
        $this->connection = $db;
    }

    /**
     * Get current table, this table have same name like entity
     * with we will mapped data from this table.
     *
     * @return string
     *
     * @throws EntityNotFoundException
     */
    protected function getTable(): string
    {
        $entity = $this->getEntity();

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


    protected function getQuery(string $query, array $values = [])
    {
        $operator = preg_replace('/ .+/', '', $query);

        if (!\in_array($operator, $this->operators, true)) {
            throw new UndefinedOperatorException("Undefined operator `{$operator}`");
        }

        return $this->connection::$operator($query, $values);
    }

    public function valueExist(string $field, $value): bool
    {
        $query = $this->getQuery("select {$field} from {$this->getTable()} where {$field} = ?", [$value]);

        return (bool) $query;
    }

    /**
     * Save data to DB.
     *
     * @param EntityInterface $entity
     *
     * @return bool
     *
     * @throws EntityNotFoundException
     */
    public function save(EntityInterface $entity): bool
    {
        $rawData = Hydrator::extract($entity, [], true);
        $keys = implode(', ', array_keys($rawData));
        $values = implode(', ', array_map(function ($value){
            return "'{$value}'";
        }, $rawData));

        $query = $this->getQuery("insert into {$this->getTable()} ({$keys}) values({$values})");

        return (bool) $query;
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
        $query = $this->getQuery("select * from {$this->getTable()} where `{$fieldKey}` = '{$fieldValue}'");

        $data = (array) \current($query);

        return !empty($query) ? Hydrator::hydrate($this->getEntity(), $data) : null;
    }

    /**
     * @param string $uid
     *
     * @return EntityInterface|null
     *
     * @throws EntityNotFoundException
     * @throws UndefinedOperatorException
     */
    public function getByUid(string $uid): ?EntityInterface
    {
        return $this->find($uid);
    }
}