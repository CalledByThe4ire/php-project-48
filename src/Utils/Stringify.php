<?php

namespace Differ\Utils\Stringify;

function toString(mixed $value): string
{

    if ($value === true) {
        return 'true';
    } elseif ($value === false) {
        return 'false';
    } elseif ($value === null) {
        return 'null';
    } elseif (is_array($value)) {
        return '[complex value]';
    }

    return $value;
}
