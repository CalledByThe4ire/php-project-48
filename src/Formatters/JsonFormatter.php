<?php

namespace Differ\Formatters\JsonFormatter;

function format(array $diffs): string|false
{
    return json_encode($diffs, JSON_PRETTY_PRINT);
}
