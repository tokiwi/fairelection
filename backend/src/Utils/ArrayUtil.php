<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Utils;

class ArrayUtil
{
    public static function groupBy(string $key, array $data): array
    {
        $result = [];

        foreach ($data as $val) {
            if (\array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            }
        }

        return $result;
    }
}
