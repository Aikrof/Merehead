<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Query
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Query;

use Aikrof\Hydrator\Hydrator;
use App\Core\Entity\BaseEntity;

/**
 * Trait QueryTrait
 */
trait QueryTrait
{
    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->from;
    }

    /**
     * @param  \Closure|QueryBuilder|string   $table
     * @param  string|null                    $as
     *
     * @return static
     */
    public function setTable($table, $as = null): self
    {
        return $this->from($table, $as);
    }

    /**
     * @param array $data
     *
     * @return BaseEntity[]
     */
    private function hydrate(array $data): array
    {
        $entityNamespace = 'App\Entities\\' . \ucfirst($this->getTable()) . 'Entity';

        $result = [];
        foreach ($data as $arr) {
            $result[] = Hydrator::hydrate($entityNamespace, $arr);
        }

        return $result;
    }

    /**
     * @param array|string $columns
     *
     * @return array|string
     */
    private function buildQueryColumns($columns)
    {
        $queryColumns = [];
        if ($this->joins !== null && \is_array($columns) && \current($columns) === '*') {
            $queryColumns[] = "{$this->from}.*";
            foreach ($this->joins as $joinClause) {
                $tableName = $joinClause->table;
                $tableFields = \Schema::getColumnListing($tableName);
                foreach ($tableFields as $field) {
                    $queryColumns[] = "{$tableName}.{$field} as {$tableName}.{$field}";
                }
            }
        }

        if (empty($queryColumns)) {
            $queryColumns = $columns;
        }

        return $queryColumns;
    }
}
