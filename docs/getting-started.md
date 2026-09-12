---
id: getting-started
title: Getting started
sidebar_position: 3
description: Create collections, read entries, and choose how updates behave.
---

## Build a mutable collection

Pass an array or another iterable to the constructor. An omitted argument
creates an empty collection.

```php
use Dirthara\Collection\MutableCollection;

$names = new MutableCollection(['first' => 'Ada']);
$names->set('second', 'Lin');
$names->set('first', 'Grace');

$first = $names->get('first');
$keys = $names->keys();
```

`$first` is `'Grace'`, and `$keys` is `['first', 'second']`. Replacing an entry
does not move it. [Mutable operations](operations/mutable.md) change the same
collection object.

## Keep the original with an immutable collection

```php
use Dirthara\Collection\ImmutableCollection;

$original = new ImmutableCollection(['first' => 'Ada']);
$extended = $original->with('second', 'Lin');
$remaining = $extended->without('first');
```

`$original` still contains only `'first'`. `$extended` contains both entries,
and `$remaining` contains only `'second'`. Always use the returned value from
[immutable operations](operations/immutable.md).

## Read and iterate

```php
use Dirthara\Collection\ImmutableCollection;

$names = new ImmutableCollection(['first' => 'Ada', 'second' => 'Lin']);

foreach ($names as $key => $name) {
    echo $key . ': ' . $name . PHP_EOL;
}
```

Iteration preserves keys and insertion order. Both variants support `count()`,
`isEmpty()`, `has()`, `get()`, `contains()`, `keys()`, `values()`, and
`toArray()`. See the [shared API](collections.md).

## Handle absence explicitly

```php
use Dirthara\Collection\MutableCollection;

$settings = new MutableCollection(['label' => null]);

$present = $settings->has('label');
$value = $settings->get('label');
$missing = $settings->has('other');
```

`$present` is `true`, `$value` is `null`, and `$missing` is `false`.
Calling `get('other')` throws a
[missing-key exception](error-handling.md).

## Choose a contract for consumers

Use the [read-only contract](contracts.md) when a function only needs to read.
Choose the mutable or immutable child contract when it needs update operations.
A read-only typehint limits the exposed API; it does not turn a mutable object
into an immutable one.
