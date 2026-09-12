---
id: collections
title: Shared collection API
sidebar_label: Shared API
sidebar_position: 4
description: Construction, key semantics, lookup, membership, iteration, and array exports.
---

Both concrete collections implement `Dirthara\Collection\Contract\Collection`.
The shared abstract class `Dirthara\Collection\Collection` supplies their
array-backed read behavior; it cannot be instantiated directly.

## Construction and keys

| Parameter | Type | Default | Meaning |
| --- | --- | --- | --- |
| `items` | `iterable<TKey, TValue>` | `[]` | Initial entries, copied in iteration order. |

`TKey` is an integer or string; `TValue` can be any PHP value. The empty default
lets callers start building a collection without supplying an array.

Construction eagerly consumes an iterable once. The collection stores its own
entries and does not revisit the producer when read or iterated later.
Passing a generator therefore does not make the collection lazy.

When an iterable produces a duplicate key, its last value wins and the key keeps
its original position. Numeric keys are preserved rather than reindexed.

```php
use Dirthara\Collection\ImmutableCollection;

function entries(): Generator
{
    yield 'first' => 'Ada';
    yield 10 => 'Lin';
    yield 'first' => 'Grace';
}

$names = new ImmutableCollection(entries());
$result = $names->toArray();
```

`$result` is `['first' => 'Grace', 10 => 'Lin']`.

:::caution
Keys follow PHP array semantics. The string `'42'` and the integer `42` address
the same entry, while `'042'` remains a string key. Use consistent identifiers
when keying collections by entity ID. Supply only integer or string keys;
other iterable key types are outside the collection contract.
:::

## Reading and membership

| Method | Return type | Behavior |
| --- | --- | --- |
| `count()` | `int` | Number of entries. Also available as `count($collection)`. |
| `isEmpty()` | `bool` | Whether there are no entries. |
| `has(int\|string $key)` | `bool` | Whether the key exists, including an entry containing `null`. |
| `get(int\|string $key)` | `TValue` | Value at the key; throws `KeyNotFoundException` if absent. |
| `contains(mixed $value)` | `bool` | Whether any stored value is strictly equal to the argument. |

`has()` checks a key; `contains()` checks values. With `contains()`, `1` differs
from `'1'` and `true`. Two distinct objects do not match even if their properties
are equal. Arrays use PHP's strict array comparison, including value types and
key order. Membership checking scans the stored values.

Missing keys are described in [error handling](error-handling.md).

## Arrays and iteration

| Method | Return type | Behavior |
| --- | --- | --- |
| `keys()` | `list<TKey>` | Keys in insertion order. |
| `values()` | `list<TValue>` | Values in insertion order, indexed from zero. |
| `toArray()` | `array<TKey, TValue>` | Entries with their original keys and order. |
| `getIterator()` | `Traversable<TKey, TValue>` | Snapshot of the current entries. |

Use `foreach` to iterate without explicitly requesting an iterator. Each new
iteration sees the entries present when its iterator was created. An existing
iterator is unaffected by later insertions, removals, or replacements in a
mutable collection.

```php
use Dirthara\Collection\MutableCollection;

$names = new MutableCollection(['first' => 'Ada']);
$iterator = $names->getIterator();
$names->set('second', 'Lin');

$before = iterator_to_array($iterator);
$after = $names->toArray();
```

`$before` contains only `'first'`; `$after` contains both entries.
Changing an exported array's entries does not change the collection.
[Stored objects and nested references](operations/copying.md) remain shared.

Collections do not implement `ArrayAccess`: use `get()` and the appropriate
[mutable](operations/mutable.md) or [immutable](operations/immutable.md) method
instead of array-offset syntax.
