<?php

namespace Differ\Differ\Tests;

use PHPUnit\Framework\TestCase;
use Exception;

use function Differ\DiffGenerator\FindDifferences\getDifferences;
use function Differ\Parsers\JsonParser\parse as JsonParser;
use function Differ\Parsers\YamlParser\parse as YamlParser;

class DifferTest extends TestCase
{
    private array $array1;
    private array $array2;

    public function setUp(): void
    {

        $this->array1 = [
            "follow" => false,
            "host" => "hexlet.io",
            "proxy" => "123.234.53.22",
            "timeout" => 50,
        ];

        $this->array2 = [
            "host" => "hexlet.io",
            "timeout" => 20,
            "verbose" => true,
        ];
    }

    /**
     * @throws Exception
     */
    public function testParsers(): void
    {
        $extensions = ['json', 'yaml'];
        $filepath = 'tests/fixtures/file1';

        foreach ($extensions as $extension) {
            switch ($extension) {
                case 'json':
                    $this->assertEqualsCanonicalizing(
                        $this->array1,
                        JsonParser($filepath . '.' . $extension)
                    );
                    break;
                case 'yaml':
                    $this->assertEqualsCanonicalizing(
                        $this->array1,
                        YamlParser($filepath . '.' . $extension)
                    );
                    break;
                default:
                    throw new Exception("Unknown format: {$extension}");
            }
        }
    }

    public function testGetDifferences(): void
    {
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

        $actual = getDifferences($this->array1, $this->array2);

        $this->assertEquals($expected, $actual);
    }
}
