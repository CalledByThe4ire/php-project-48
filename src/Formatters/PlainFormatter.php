<?php

namespace Differ\Formatters\PlainFormatter;

use function Differ\Utils\Stringify\toString;
use function Differ\Utils\Sort\quickSort;

function format(array $diffs): string
{
    return implode("\n", recursiveFormat($diffs, ''));
}

function recursiveFormat(array $diffs, string $parentName): array
{
    $sortedDiffs = quickSort($diffs, fn (array $arr1, array $arr2) => $arr1['key'] <=> $arr2['key']);

    return array_reduce($sortedDiffs, function ($acc, $meta) use ($parentName) {
        $key = $parentName === '' ? $meta['key'] : sprintf("%s.%s", $parentName, $meta['key']);
        $state = $meta['state'];
        $value = $meta['value'] ?? null;
        if ($state === 'changed') {
            return [...$acc, generatePlainString($state, $key, [$meta['oldValue'], $meta['newValue']])];
        } else {
            $reportStr = generatePlainString($state, $key, $value);
            if (strlen($reportStr) !== 0) {
                return [...$acc, $reportStr];
            }

            if (is_array($value)) {
                return [...$acc, ...recursiveFormat($value, $key)];
            }
        }

        return $acc;
    }, []);
}

function generatePlainString(string $mode, string $key, mixed $value): string
{
    switch ($mode) {
        case 'added':
            $newValue = is_string($value)
                ? sprintf("'%s'", $value)
                : toString($value);

            return sprintf("Property '%s' was added with value: %s", $key, $newValue);
        case 'changed':
            $oldValue = is_string($value[0])
                ? sprintf("'%s'", $value[0])
                : toString($value[0]);

            $newValue = is_string($value[1])
                ? sprintf("'%s'", $value[1])
                : toString($value[1]);

            return sprintf("Property '%s' was updated. From %s to %s", $key, $oldValue, $newValue);
        case 'removed':
            return sprintf("Property '%s' was removed", $key);
    }

    return '';
}
