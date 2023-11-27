# hexlet-code

[![PHP CI](https://github.com/CalledByThe4ire/php-project-48/actions/workflows/php-ci.yml/badge.svg)](https://github.com/CalledByThe4ire/php-project-48/actions/workflows/php-ci.yml)

[![Maintainability](https://api.codeclimate.com/v1/badges/c03904e59b06786133e2/maintainability)](https://codeclimate.com/github/CalledByThe4ire/php-project-48/maintainability)

[![Test Coverage](https://api.codeclimate.com/v1/badges/c03904e59b06786133e2/test_coverage)](https://codeclimate.com/github/CalledByThe4ire/php-project-48/test_coverage)

# Gendiff

«Gendiff» — a program that finds the difference between two data structures.

## Description

Can be used as a separate CLI application or imported as a php package.

Program gets 2 paths and output format as input and returns structures differences in user defined format.

Utility features:
- Recursive processing;
- Support for different input formats: yaml and json;
- Report generation in the form of plain text, "stylish" and json.

## Prerequisites

* Linux, Macos, WSL
* PHP >=8.2
* Xdebug
* Make
* Git

## Addons

Use <http://psysh.org/>

## Setup

```bash
git clone https://github.com/CalledByThe4ire/php-project-48.git
cd php-project-48
make install
```

## Run tests

```sh
make test
```

Usage
-----

### [](https://github.com/CalledByThe4ire/gendiff#cli)CLI

```source-shell
# format plain
$ ./gendiff --format plain path/to/file.yml another/path/file.json

Property 'common.follow' was added with value: false
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group2' was removed

# format by default (stylish)
$ ./gendiff filepath1.json filepath2.json

{
  + follow: false
    setting1: Value 1
  - setting2: 200
  - setting3: true
  + setting3: {
        key: value
    }
  + setting4: blah blah
  + setting5: {
        key5: value5
    }
}

$ ./gendiff -h # for help
```

### [](https://github.com/CalledByThe4ire/gendiff#php-package-1)Php package

```text-html-php
use function Differ\Differ\genDiff;

// formats: stylish (default), plain, json
$diff = genDiff($pathToFile1, $pathToFile2, $format);
print_r($diff);
```
