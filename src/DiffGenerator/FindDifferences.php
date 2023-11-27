<?php

namespace Differ\DiffGenerator\FindDifferences;

function getDifferences(array $arr1, array $arr2): array
{
    $allKeys = array_merge(array_keys($arr1), array_keys($arr2));
    $uniqueKeys = array_unique($allKeys);

    $diffBuilder = array_reduce($uniqueKeys, function ($acc, $key) use ($arr1, $arr2) {
        $keyState = getKeyState($arr1, $arr2, $key);

        if ($keyState !== 'unchanged') {
            $value = $keyState === 'added' ? $arr2[$key] : $arr1[$key];
            return [...$acc, generateMeta($keyState, $key, $value)];
        } elseif ($arr2[$key] === $arr1[$key]) {
            return [...$acc, generateMeta('unchanged', $key, $arr1[$key])];
        } elseif (is_array($arr1[$key]) && is_array($arr2[$key])) {
            $newValue = getDifferences($arr1[$key], $arr2[$key]);
            return [...$acc, generateMeta('unchanged', $key, $newValue)];
        }

        return [...$acc, generateMeta('changed', $key, [$arr1[$key], $arr2[$key]])];
    }, []);

    return formatEntriesToMeta($diffBuilder);
}

function getKeyState(array $keys1, array $keys2, string $key): string
{
    if (!in_array($key, array_keys($keys2), true)) {
        return 'removed';
    }

    if (!in_array($key, array_keys($keys1), true)) {
        return 'added';
    }

    return 'unchanged';
}

function generateMeta(string $state, string $key, mixed $value): array
{
    if ($state !== 'changed') {
        return ['key' => $key, 'state' => $state, 'value' => $value];
    }

    $oldValue = $value[0];
    $newValue = $value[1];

    return [
        'key' => $key, 'state' => $state,
        'oldValue' => $oldValue, 'newValue' => $newValue
    ];
}

function formatEntriesToMeta(array $diffs): array
{
    return array_reduce(array_keys($diffs), function ($acc, $key) use ($diffs) {

        $meta = $diffs[$key];
        if (!is_array($meta)) {
            return [...$acc, generateMeta('unchanged', $key, $meta)];
        } elseif (!array_key_exists('state', $meta)) {
            $newValue = formatEntriesToMeta($meta);
            return [...$acc, generateMeta('unchanged', $key, $newValue)];
        }

        return [...$acc, formatMetaValueToMeta($meta)];
    }, []);
}

function formatMetaValueToMeta(array $meta): array
{
    $key = $meta['key'];
    if ($meta['state'] === 'changed') {
        $oldValue = is_array($meta['oldValue'])
            ? formatEntriesToMeta($meta['oldValue'])
            : $meta['oldValue'];

        $newValue = is_array($meta['newValue'])
            ? formatEntriesToMeta($meta['newValue'])
            : $meta['newValue'];

        return generateMeta('changed', $key, [$oldValue, $newValue]);
    }

    if (is_array($meta['value'])) {
        $newValue = formatEntriesToMeta($meta['value']);

        return generateMeta($meta['state'], $key, $newValue);
    }

    return generateMeta($meta['state'], $key, $meta['value']);
}
