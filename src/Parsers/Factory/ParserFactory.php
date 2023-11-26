<?php

namespace Differ\Parsers\Factory\ParserFactory;

use Exception;
use Symfony\Component\Yaml\Exception\ParseException;

use function Differ\Parsers\YamlParser\parse as parseYaml;
use function Differ\Parsers\JsonParser\parse as parseJson;

/**
 * @throws Exception
 */
function getParser(string $filepath): callable
{
    $arr = explode('.', $filepath);
    $extension = end($arr);
    switch ($extension) {
        case 'yml':
        case 'yaml':
            return fn(string $path) => parseYaml($path);
        case 'json':
            return fn(string $path) => parseJson($path);
    }

    throw new Exception("Unsupported extension: {$extension}.");
}
