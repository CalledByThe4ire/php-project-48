<?php

namespace Differ\Differ;

use Exception;

use function Differ\DiffGenerator\FindDifferences\getDifferences;
use function Differ\Parsers\ParserFactory\getParser;
use function Differ\Utils\Stringify\stringify;

/**
 * @throws Exception
 */
function genDiff(
    string $path1,
    string $path2,
): string {
    [, $ext] = explode('.', $path1);
    $array1 = getParser($path1)($path1);
    $array2 = getParser($path2)($path2);
    dump($array1);

    $diff = getDifferences($array1, $array2);

    return stringify($diff);
}
