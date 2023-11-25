<?php

namespace Differ\Utils\Sort;

function sort(array $array): array
{
    $arrayCopy = [...$array];
    ksort($arrayCopy);

    return $arrayCopy;
}
