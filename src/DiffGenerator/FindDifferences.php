<?php

namespace Differ\DiffGenerator\FindDifferences;

use function Differ\Utils\Sort\sort;

function getDifferences(array $arr1, array $arr2): array
{
    $array1 = sort($arr1);
    $array2 = sort($arr2);
    $array1Keys = array_keys($array1);
    $array2Keys = array_keys($array2);

    return array_reduce(
        array_values(array_unique(array_merge($array1Keys, $array2Keys))),
        function ($acc, $key) use ($array1, $array1Keys, $array2, $array2Keys) {
            if (in_array($key, $array1Keys) && in_array($key, $array2Keys)) {
                if ($array1[$key] === $array2[$key]) {
                    $acc[] = ['key' => $key, 'value' => $array1[$key], 'meta' => 'unchanged'];
                } else {
                    $acc[] = ['key' => $key, 'value' => [$array1[$key], $array2[$key]], 'meta' => 'changed'];
                }
            } elseif (in_array($key, $array1Keys) && !in_array($key, $array2Keys)) {
                $acc[] = ['key' => $key, 'value' => $array1[$key], 'meta' => 'removed'];
            } elseif (!in_array($key, $array1Keys) && in_array($key, $array2Keys)) {
                $acc[] = ['key' => $key, 'value' => $array2[$key], 'meta' => 'added'];
            }

            return $acc;
        },
        []
    );
}
