<?php

namespace Differ\Differ\Tests;

use PHPUnit\Framework\TestCase;
use Exception;

use function Differ\DiffGenerator\FindDifferences\getDifferences;
use function Differ\Parsers\Factory\ParserFactory\getParser;

class DifferTest extends TestCase
{
    private array $array1;
    private array $array2;

    public function setUp(): void
    {

        $this->array1 = [
            "common" =>  [
                "setting1" => "Value 1",
                "setting2" => 200,
                "setting3" => true,
                "setting6" =>  [
                    "key" => "value",
                    "doge" => [
                        "wow" => ""
                    ],
                ],
            ],
            "group1" =>  [
                "baz" => "bas",
                "foo" => "bar",
                "nest" =>  [
                    "key" => "value"
                ],
            ],
            "group2" =>  [
                "abc" => 12345,
                "deep" =>  [
                    "id" => 45,
                ],
            ],
        ];

        $this->array2 = [
            "common" =>  [
                "follow" => false,
                "setting1" => "Value 1",
                "setting3" => null,
                "setting4" => "blah blah",
                "setting5" =>  [
                    "key5" => "value5",
                ],
                "setting6" =>  [
                    "key" => "value",
                    "ops" => "vops",
                    "doge" =>  [
                        "wow" => "so much"
                    ],
                ],
            ],
            "group1" =>  [
                "foo" => "bar",
                "baz" => "bars",
                "nest" => "str",
            ],
            "group3" =>  [
                "deep" =>  [
                    "id" =>  [
                        "number" => 45,
                    ],
                ],
                "fee" => 100500
            ],
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
            $this->assertEqualsCanonicalizing(
                $this->array1,
                getParser($extension)($filepath . '.' . $extension)
            );
        }
    }

    public function testGetDifferences(): void
    {
        $expected = [
            0 =>  [
                "key" => "common",
                "state" => "unchanged",
                "value" =>  [
                    0 =>  [
                        "key" => "setting1",
                        "state" => "unchanged",
                        "value" => "Value 1",
                    ],
                    1 =>  [
                        "key" => "setting2",
                        "state" => "removed",
                        "value" => 200,
                    ],
                    2 =>  [
                        "key" => "setting3",
                        "state" => "changed",
                        "oldValue" => true,
                        "newValue" => null,
                    ],
                    3 =>  [
                        "key" => "setting6",
                        "state" => "unchanged",
                        "value" =>  [
                            0 =>  [
                                "key" => "key",
                                "state" => "unchanged",
                                "value" => "value",
                            ],
                            1 =>  [
                                "key" => "doge",
                                "state" => "unchanged",
                                "value" =>  [
                                    0 =>  [
                                        "key" => "wow",
                                        "state" => "changed",
                                        "oldValue" => "",
                                        "newValue" => "so much",
                                    ],
                                ],
                            ],
                            2 =>  [
                                "key" => "ops",
                                "state" => "added",
                                "value" => "vops",
                            ],
                        ],
                    ],
                    4 =>  [
                        "key" => "follow",
                        "state" => "added",
                        "value" => false,
                    ],
                    5 =>  [
                        "key" => "setting4",
                        "state" => "added",
                        "value" => "blah blah",
                    ],
                    6 =>  [
                        "key" => "setting5",
                        "state" => "added",
                        "value" =>  [
                            0 =>  [
                                "key" => "key5",
                                "state" => "unchanged",
                                "value" => "value5",
                            ],
                        ],
                    ],
                ],
            ],
            1 =>  [
                "key" => "group1",
                "state" => "unchanged",
                "value" =>  [
                    0 =>  [
                        "key" => "baz",
                        "state" => "changed",
                        "oldValue" => "bas",
                        "newValue" => "bars",
                    ],
                    1 =>  [
                        "key" => "foo",
                        "state" => "unchanged",
                        "value" => "bar",
                    ],
                    2 =>  [
                        "key" => "nest",
                        "state" => "changed",
                        "oldValue" =>  [
                            0 =>  [
                                "key" => "key",
                                "state" => "unchanged",
                                "value" => "value",
                            ],
                        ],
                        "newValue" => "str",
                    ],
                ],
            ],
            2 =>  [
                "key" => "group2",
                "state" => "removed",
                "value" =>  [
                    0 =>  [
                        "key" => "abc",
                        "state" => "unchanged",
                        "value" => 12345,
                    ],
                    1 =>  [
                        "key" => "deep",
                        "state" => "unchanged",
                        "value" =>  [
                            0 =>  [
                                "key" => "id",
                                "state" => "unchanged",
                                "value" => 45,
                            ],
                        ],
                    ],
                ],
            ],
            3 =>  [
                "key" => "group3",
                "state" => "added",
                "value" =>  [
                    0 =>  [
                        "key" => "deep",
                        "state" => "unchanged",
                        "value" =>  [
                            0 =>  [
                                "key" => "id",
                                "state" => "unchanged",
                                "value" =>  [
                                    0 =>  [
                                        "key" => "number",
                                        "state" => "unchanged",
                                        "value" => 45,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    1 =>  [
                        "key" => "fee",
                        "state" => "unchanged",
                        "value" => 100500,
                    ],
                ],
            ],
        ];

        $actual = getDifferences($this->array1, $this->array2);

        $this->assertEquals($expected, $actual);
    }
}
