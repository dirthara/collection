---
id: installation
title: Installation
sidebar_position: 2
description: PHP requirements, Composer installation, and autoloading.
---

## Requirements

The package requires PHP `^8.5`: PHP 8.5 or a later PHP 8 release. It has no
additional runtime Composer dependencies or database requirements.

## Install with Composer

For a published release, run:

```sh
composer require dirthara/collection
```

Composer installs the package and registers the `Dirthara\Collection` namespace
with its autoloader. In a standalone application, load that autoloader before
using the package. Framework applications commonly load it during bootstrap.

```php
require 'vendor/autoload.php';

use Dirthara\Collection\MutableCollection;

$names = new MutableCollection(['first' => 'Ada']);
```

:::note
The Composer command requires a release to be available in your configured
repositories. To work from a local checkout before publication, configure a
Composer path repository in the consuming application.
:::

Continue with [getting started](getting-started.md).
