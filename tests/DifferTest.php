<?php

namespace Differ\Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\DiffGenerator\FindDifferences\getDifferences;

class DifferTest extends TestCase
{
    public function testGetDifferences(): void
    {
        $array1 = [
            "follow" => false,
            "host" => "hexlet.io",
            "proxy" => "123.234.53.22",
            "timeout" => 50,
        ];
        $array2 = [
            "host" => "hexlet.io",
            "timeout" => 20,
            "verbose" => true,
        ];
        $expected = [
            0 =>  [
                "key" => "follow",
                "value" => false,
                "meta" => "removed",
            ],
            1 =>  [
                "key" => "host",
                "value" => "hexlet.io",
                "meta" => "unchanged",
            ],
            2 =>  [
                "key" => "proxy",
                "value" => "123.234.53.22",
                "meta" => "removed",
            ],
            3 =>  [
                "key" => "timeout",
                "value" =>  [
                    0 => 50,
                    1 => 20
                ],
                "meta" => "changed",
            ],
            4 =>  [
                "key" => "verbose",
                "value" => true,
                "meta" => "added",
            ]
        ];

        $actual = getDifferences($array1, $array2);

        $this->assertEquals($expected, $actual);
    }
}
