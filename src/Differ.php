<?php

namespace Differ\Differ;

use Exception;

/**
 * @throws Exception
 */
function genDiff(string $path1, string $path2): string
{
    $array1 = json_decode(file_get_contents($path1), true);
    $array2 = json_decode(file_get_contents($path2), true);

    ksort($array1);
    ksort($array2);

    $array1Keys = array_keys($array1);
    $array2Keys = array_keys($array2);

    $reduced = array_reduce(
        array_values(array_unique(array_merge($array1Keys, $array2Keys))),
        function ($acc, $key) use ($array1, $array1Keys, $array2, $array2Keys) {
            if (in_array($key, $array1Keys) && in_array($key, $array2Keys)) {
                if ($array1[$key] === $array2[$key]) {
                    $acc[str_repeat(' ', 2) . $key] = $array1[$key];
                } else {
                    $acc["- {$key}"] = $array1[$key];
                    $acc["+ {$key}"] = $array2[$key];
                }
            } elseif (in_array($key, $array1Keys) && !in_array($key, $array2Keys)) {
                $acc["- {$key}"] = $array1[$key];
            } elseif (!in_array($key, $array1Keys) && in_array($key, $array2Keys)) {
                $acc["+ {$key}"] = $array2[$key];
            }

            return $acc;
        },
        []
    );

    $mapped = array_map(function ($key, $value) {
        $paddedKey = str_repeat(' ', 3) . $key;
        $normalizedValue = is_bool($value) ? ($value ? "true" : "false") : $value;

        return "{$paddedKey}: {$normalizedValue}";
    }, array_keys($reduced), $reduced);

    $stringified = implode("\n", $mapped);

    return "{" . "\n" . $stringified . "\n" . "}";
}
