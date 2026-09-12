---
id: collections
title: Collections
sidebar_position: 3
description: Construct, read, mutate, and copy keyed collections.
---

## Construction and keys

Both `MutableCollection` and `ImmutableCollection` accept an iterable of values
keyed by integers or strings. The default is an empty collection. Generators
are consumed once during construction; later reads do not revisit them.

| Parameter | Type | Default | Meaning |
| --- | --- | --- | --- |
| `items` | `iterable<TKey, TValue>` | `[]` | Initial entries, copied in iteration order. |

The last value for a duplicate key wins, preserving that key's original
position. Keys follow PHP array rules: `'42'` and `42` identify the same entry,
while `'042'` remains a distinct string key. Numeric keys are not reindexed.

```php
use Dirthara\Collection\MutableCollection;

$collection = new MutableCollection(['first' => 'Ada', 10 => 'Lin']);
$value = $collection->get('first');
```

## Reading

Both variants implement `Dirthara\Collection\Contract\Collection`.

| Method | Result |
| --- | --- |
| `count(): int` | Number of entries; also supports `count($collection)`. |
| `isEmpty(): bool` | Whether there are no entries. |
| `has(int\|string $key): bool` | Whether the key exists, even when its value is `null`. |
| `get(int\|string $key): mixed` | Value at the key; throws `KeyNotFoundException` if absent. |
| `contains(mixed $value): bool` | Strict value comparison; objects must be the same instance. |
| `keys(): array` | List of keys in insertion order. |
| `values(): array` | List of values in insertion order. |
| `toArray(): array` | Array preserving keys and insertion order. |
| `getIterator(): Traversable` | Iterator over a snapshot of the entries. |

Use `foreach` for traversal. Each call to `getIterator()` captures the current
entries. Changes to a mutable collection after that call do not change the
existing iterator. Exported arrays and iterators cannot replace or remove the
collection's entries.

## Mutable collections

`MutableCollection` implements `Contract\MutableCollection`. Its mutation
methods change the existing collection.

| Method | Result |
| --- | --- |
| `set(int\|string $key, mixed $value): void` | Insert or replace an entry. Replacement keeps its position; insertion appends. |
| `remove(int\|string $key): bool` | Remove an entry; return whether it existed, including entries containing `null`. |
| `clear(): void` | Remove all entries. |
| `copy(): self` | Create an independent mutable container. |
| `toImmutable(): ImmutableCollection` | Create an independent immutable container. |

Removing a key and inserting it again moves it to the end.

```php
use Dirthara\Collection\MutableCollection;

$names = new MutableCollection(['first' => 'Ada']);
$names->set('second', 'Lin');
$snapshot = $names->toImmutable();
$names->remove('first');
```

The snapshot still contains both entries.

## Immutable collections

`ImmutableCollection` implements `Contract\ImmutableCollection`. Replacement
methods return a new collection and leave the receiver unchanged.

| Method | Result |
| --- | --- |
| `with(int\|string $key, mixed $value): self` | Return a collection with the entry inserted or replaced. |
| `without(int\|string $key): self` | Return a collection without the key. An absent key leaves the entries unchanged. |
| `toMutable(): MutableCollection` | Create an independent mutable container. |

```php
use Dirthara\Collection\ImmutableCollection;

$original = new ImmutableCollection(['first' => 'Ada']);
$extended = $original->with('second', 'Lin');
$remaining = $extended->without('first');
```

:::caution
Immutability is shallow. Stored objects are shared, including across copies,
conversions, and exported arrays or iterators. Changing an entity's properties
is visible through every collection containing that entity. Nested PHP
references are not deep-copied either. Use immutable values when you need a
snapshot of their state as well as collection membership.
:::

## Typehints

Use the shared contract for reading, and the appropriate child contract when
you need mutable or immutable operations. Import conflicting names with aliases.

```php
use Dirthara\Collection\Contract\MutableCollection as MutableCollectionContract;

function renameFirst(MutableCollectionContract $names, string $name): void
{
    $names->set('first', $name);
}
```

The contracts and concrete classes declare `TKey` and `TValue` for static
analysis. They do not enforce an entity class at runtime; a consuming entity
collection can enforce its own domain rules.

## Missing keys and exceptions

`get()` throws `Exception\KeyNotFoundException`, which extends
`Exception\CollectionException`. Its context contains the operation `get` and
the missing key, without including stored values in the message or context.
Use `has()` when absence is expected; a value of `null` is still present.

The base exception accepts `message`, `code`, `previous`, and an optional
context array. `getContext()` returns the context; `addContext()` merges new
context and returns the same exception, replacing matching string keys.
Exceptions do not log themselves. An application handler can pass their context
to its logger and set the `exception` context key to the caught exception.
