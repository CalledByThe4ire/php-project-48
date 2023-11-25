<?php

namespace Differ\Differ;

use Exception;

use function Differ\DiffGenerator\FindDifferences\getDifferences;
use function Differ\Utils\Stringify\toJSON as toJSON;

/**
 * @throws Exception
 */
function getFormatter(string $format): callable
{
    switch ($format) {
        case 'json':
            return fn ($arr) => toJSON($arr);
        default:
            throw new Exception("Unknown format: {$format}");
    }
}

/**
 * @throws Exception
 */
function getParcer(string $format): callable
{
    switch ($format) {
        case 'json':
            return fn ($json) => json_decode($json, true);
        default:
            throw new Exception("Unknown format: {$format}");
    }
}

/**
 * @throws Exception
 */
function genDiff(
    string $path1,
    string $path2,
    string $format = 'json',
): string {
    $data1 = file_get_contents($path1);
    $data2 = file_get_contents($path2);
    $array1 = $data1 ? getParcer($format)($data1) : [];
    $array2 = $data1 ? getParcer($format)($data2) : [];

    $diff = getDifferences($array1, $array2);

    return getFormatter($format)($diff);
}
