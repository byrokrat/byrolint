![byrokrat](res/logo.svg)

# byrolint

[![Packagist Version](https://img.shields.io/packagist/v/byrokrat/byrolint.svg?style=flat-square)](https://packagist.org/packages/byrokrat/byrolint)
[![Build Status](https://img.shields.io/travis/byrokrat/byrolint/master.svg?style=flat-square)](https://travis-ci.com/github/byrokrat/byrolint)

Command line utility for linting swedish bureaucratic content.

## Installation

### Using phive (recommended)

Install using [phive][1]:

```shell
phive install byrokrat/byrolint
```

### As a phar archive

Download the latest version from the github [releases][2] page.

Optionally rename `byrolint.phar` to `byrolint` for a smoother experience.

### Using composer

Install as a [composer][3] dependency:

```shell
composer require byrokrat/byrolint
```

This will make `byrolint` avaliable as `vendor/bin/byrolint`.

### From source

To build you need `make`

```shell
make
sudo make install
```

The build script uses [composer][3] to handle dependencies and [phive][1] to
handle build tools. If they are not installed as `composer` or `phive`
respectivly you can use something like

```shell
make COMPOSER_CMD=./composer.phar PHIVE_CMD=./phive.phar
```

## Usage

Linting a regular account number

```shell
byrolint --account 123456678
```

Linting a personal id number

```shell
byrolint --personal-id 123456678
```

Check a number using all the linters

```shell
byrolint 123456678
```

For more information use

```shell
byrolint --help
```

[1]: <https://phar.io/>
[2]: <https://github.com/byrokrat/byrolint/releases>
[3]: <https://getcomposer.org/>
