<?php

namespace Differ\Formatters\StylishFormatter;

use function Differ\Utils\Sort\quickSort;
use function Differ\Utils\Stringify\toString;

const SIGN_VALUES = [
    'added' => '+',
    'removed' => '-',
    'changed' => '',
    'unchanged' => ''
];

function format(array $diffs): string
{
    return recursiveFormat($diffs);
}

function recursiveFormat(array $diffs): string
{
    $sortedDiffs = quickSort($diffs, fn (array $arr1, array $arr2) => $arr1['key'] <=> $arr2['key']);
    $output = array_reduce($sortedDiffs, function ($acc, $meta) {
        $key = $meta['key'];
        $state = $meta['state'];
        $value = $meta['value'] ?? null;

        if ($state !== 'changed') {
            return [...$acc, generateStylishString($state, $key, $value)];
        } else {
            $oldStr = generateStylishString('removed', $key, $meta['oldValue']);
            $newStr = generateStylishString('added', $key, $meta['newValue']);

            return [...$acc, implode("\n", [$oldStr, $newStr])];
        }
    }, []);

    $normalizedOutput = ['{', ...$output, '}'];

    return implode("\n", $normalizedOutput);
}

function generateStylishString(string $mode, string $key, mixed $value): string
{
    return sprintf("%3s %s: %s", SIGN_VALUES[$mode], $key, is_array($value) ? arrayToString($value) : toString($value));
}

function arrayToString(array $array): string
{
    $strings = recursiveFormat($array);
    $tabStrings = array_map(
        fn (string $str) => sprintf("%4s%s", ' ', $str),
        array_slice(explode("\n", $strings), 1)
    );

    $resultStrings = ["{", ...$tabStrings];

    return implode("\n", $resultStrings);
}
