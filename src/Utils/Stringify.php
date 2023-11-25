<?php

namespace Differ\Utils\Stringify;

use Exception;

/**
 * @throws Exception
 */
function toJSON(array $array): string
{
    $reduced = array_reduce($array, function ($acc, $record) {
        ['key' => $key, 'value' => $value, 'meta' => $meta] = $record;

        switch ($meta) {
            case 'changed':
                $acc["- {$key}"] = $value[0];
                $acc["+ {$key}"] = $value[1];
                break;
            case 'unchanged':
                $acc[$key] = $value;
                break;
            case 'added':
                $acc["+ {$key}"] = $value;
                break;
            case 'removed':
                $acc["- {$key}"] = $value;
                break;
            default:
                throw new Exception("Unknown meta: {$meta}");
        }

        return $acc;
    }, []);

    $mapped = array_map(function ($key, $value) {
        $paddedKey = str_repeat(' ', 3) . $key;
        $normalizedValue = is_bool($value) ? ($value ? "true" : "false") : $value;

        return "{$paddedKey}: {$normalizedValue}";
    }, array_keys($reduced), $reduced);

    $stringified = implode("\n", $mapped);

    return "{" . "\n" . $stringified . "\n" . "}";
}
