<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Support
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Support;

use Illuminate\Support\Arr as ArrSupport;
use Illuminate\Support\Collection;

/**
 * Class Arr
 *
 * laravel array support
 */
class Arr extends ArrSupport
{
    /**
     * @param Collection|array $data
     *
     * @return array|null
     */
    public static function splitByDot($data): ?array
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        $result = [];
        foreach ($data as $arr) {
            $nestedResult = [];
            foreach ((array) $arr as $key => $value) {
                $dotKeys = \explode('.', $key);
                if (\count($dotKeys) > 1) {
                    self::createNestedArray($dotKeys, $nestedResult, $value);
                }
                else {
                    $nestedResult[$key] = $value;
                }
            }

            $result[] = $nestedResult;
        }

        return $result;
    }

    /**
     * Create nested arrays by keys array.
     *
     * @param array         $keys
     * @param array|null    $data
     * @param mixed         $value
     */
    public static function createNestedArray(array $keys, ?array &$data, $value): void
    {
        $current = \current($keys);
        $next = \next($keys);

        if ($next === false) {
            $data[$current] = $value;

            return;
        }

        if (!isset($data[$current])) {
            $data[$current] = null;
        }

        self::createNestedArray($keys, $data[$current], $value);
    }
}
