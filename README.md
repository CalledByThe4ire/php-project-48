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
