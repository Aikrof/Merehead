<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Query
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Query;

use App\Core\Entity\BaseEntity;
use Illuminate\Database\Query\Builder;

/**
 * Class QueryBuilder
 */
class QueryBuilder extends Builder
{
    use QueryTrait;

    /**
     * {@inheritDoc}
     *
     * @param  array|string  $columns
     *
     * @return BaseEntity[]|null
     */
    public function get($columns = ['*']): ?array
    {
        $queryColumns = $this->buildQueryColumns($columns);

        $data = parent::get($queryColumns);

        return $this->hydrate(\Arr::splitByDot($data));
    }

    /**
     * {@inheritDoc}
     *
     * @param  array|string  $columns
     *
     * @return BaseEntity|null
     */
    public function first($columns = ['*']): ?BaseEntity
    {
        $current = \current($this->take(1)->get($columns));

        return $current === false ? null : $current;
    }

    /**
     * {@inheritDoc}
     *
     * @param  int|string   $uid
     * @param  array        $columns
     *
     * @return BaseEntity|null
     */
    public function find($uid, $columns = ['*']): ?BaseEntity
    {
        return $this->where('uid', '=', $uid)->first($columns);
    }
}
