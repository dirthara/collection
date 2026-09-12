---
id: immutable
title: Immutable collections
sidebar_position: 2
description: Create updated collections while preserving the original entries.
---

`Dirthara\Collection\ImmutableCollection` implements the immutable
[child contract](../contracts.md) and all [shared read operations](../collections.md).
Its update methods return a new collection; they do not change the receiver.

## Methods

| Method | Return type | Behavior |
| --- | --- | --- |
| `with(int\|string $key, mixed $value)` | `ImmutableCollection<TKey, TValue>` | Return a collection with the entry inserted or replaced. |
| `without(int\|string $key)` | `ImmutableCollection<TKey, TValue>` | Return a collection without the entry. |
| `toMutable()` | `Contract\MutableCollection<TKey, TValue>` | Return an independent mutable container. |

## Use the returned collection

```php
use Dirthara\Collection\ImmutableCollection;

$original = new ImmutableCollection(['first' => 'Ada']);
$updated = $original->with('second', 'Lin')->without('first');

$before = $original->toArray();
$after = $updated->toArray();
```

`$before` is `['first' => 'Ada']`; `$after` is `['second' => 'Lin']`.
Discarding the result of `with()` or `without()` leaves no updated collection
for the caller to use.

Inserting a new key appends it. Replacing an existing key preserves its
position. Removing an absent key does not throw and leaves the resulting
entries unchanged. Removing a key and inserting it again moves it to the end.

The current implementation returns a separate object even if `with()` supplies
the existing value or `without()` supplies an absent key.

:::caution
Immutability applies to collection entries, not to the internal state of stored
objects. An entity can still change through another reference. See
[copying and object identity](copying.md).
:::

## Build once, then expose stable membership

When assembling a collection through many updates, a mutable collection can
make that intent explicit. Convert it when the receiving code needs an immutable
collection. Both variants store all entries in memory; the immutable variant
is not a lazy sequence or a persistent tree with shared branches.

See [conversion examples](copying.md).
