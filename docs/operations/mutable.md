---
id: mutable
title: Mutable collections
sidebar_position: 1
description: Insert, replace, remove, and clear entries in an existing collection.
---

`Dirthara\Collection\MutableCollection` implements the mutable
[child contract](../contracts.md) and all [shared read operations](../collections.md).
Updating it changes the object seen by every caller holding that same instance.

## Methods

| Method | Return type | Behavior |
| --- | --- | --- |
| `set(int\|string $key, mixed $value)` | `void` | Insert or replace an entry. |
| `remove(int\|string $key)` | `bool` | Remove the entry and report whether it existed. |
| `clear()` | `void` | Remove all entries. |
| `copy()` | `MutableCollection<TKey, TValue>` | Return an independent mutable container. |
| `toImmutable()` | `Contract\ImmutableCollection<TKey, TValue>` | Return an independent immutable container. |

## Insert and replace

New keys go at the end. Replacing a value preserves the key's position.
`set()` returns nothing, so it is not a chaining method.

```php
use Dirthara\Collection\MutableCollection;

$names = new MutableCollection(['first' => 'Ada', 'second' => 'Lin']);
$names->set('first', 'Grace');
$names->set('third', 'Sam');

$result = $names->toArray();
```

`$result` is `['first' => 'Grace', 'second' => 'Lin', 'third' => 'Sam']`.

## Remove and clear

`remove()` returns `true` for an existing key, including when its value is
`null`. Removing an absent key returns `false` and does not throw.
Removing and reinserting a key moves it to the end; remaining numeric keys are
never reindexed.

```php
use Dirthara\Collection\MutableCollection;

$values = new MutableCollection([4 => null, 9 => 'kept']);
$removed = $values->remove(4);
$removedAgain = $values->remove(4);
$remaining = $values->toArray();
$values->clear();
```

`$removed` is `true`, `$removedAgain` is `false`, and `$remaining` is
`[9 => 'kept']`. After `clear()`, the collection is empty and can be used again.
Calling `clear()` on an empty collection is valid.

See [copying and conversion](copying.md) when callers need independent entries.
