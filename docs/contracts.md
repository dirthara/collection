---
id: contracts
title: Contracts and entity collections
sidebar_label: Contracts
sidebar_position: 6
description: Typehint read, mutable, and immutable capabilities and integrate entity-specific rules.
---

## Choose the capability a caller needs

All contracts live under `Dirthara\Collection\Contract`.

| Contract | Capabilities |
| --- | --- |
| `Collection<TKey, TValue>` | [Read operations](collections.md), `Countable`, and `IteratorAggregate`. |
| `MutableCollection<TKey, TValue>` | Shared reads plus `set()`, `remove()`, `clear()`, `copy()`, and `toImmutable()`. |
| `ImmutableCollection<TKey, TValue>` | Shared reads plus `with()`, `without()`, and `toMutable()`. |

Both child interfaces extend the shared interface. Conversions on the contracts
return the corresponding child contract. The provided implementations return
concrete instances that satisfy those contracts.

```php
use Dirthara\Collection\MutableCollection;
use Dirthara\Collection\Contract\Collection as CollectionContract;

/**
 * @param CollectionContract<string, string> $names
 */
function firstName(CollectionContract $names): string
{
    return $names->get('first');
}

$names = new MutableCollection(['first' => 'Ada']);
$first = firstName($names);
```

Import types using `use` statements. If a concrete class and a contract have
conflicting names, alias the contract with a `Contract` suffix.

:::note
The shared interface is read-only access, not an immutability guarantee. Another
caller may still hold the same mutable instance and change its entries. Require
the immutable child contract when stable membership is part of your API.
:::

## Static types

`TKey` describes the integer or string keys. `TValue` describes the values.
PHPDoc generics let a static analyzer check those types, but PHP does not enforce
them at runtime. The native return type of `get()` is `mixed`; its PHPDoc return
type is `TValue`.

When a function changes entries, declare the corresponding child contract:

```php
use Dirthara\Collection\MutableCollection;
use Dirthara\Collection\Contract\MutableCollection as MutableCollectionContract;

/**
 * @param MutableCollectionContract<string, string> $names
 */
function renameFirst(MutableCollectionContract $names, string $name): void
{
    $names->set('first', $name);
}

$names = new MutableCollection(['first' => 'Ada']);
renameFirst($names, 'Grace');
```

## Integrating entities

A consuming entity package can wrap a collection and enforce its own entity type
and identifier rules. The concrete collection classes are final, so use
composition to add domain-specific operations.

Keep entity-ID extraction, duplicate-entity policy, persistence, and relationship
rules in the entity package. The underlying collection only knows keys and
values. In particular, duplicate keys replace values; it does not reject a
second entity with the same identifier or automatically rekey an entity whose
ID changes.

If an API exposes immutable membership, convert the internal mutable collection
with `toImmutable()`. Stored entities still share their identity, as described
in [copying and object identity](operations/copying.md).

## Shared implementation

The abstract `Dirthara\Collection\Collection` implements the shared contract and
holds the array-backed construction and read methods. `MutableCollection` and
`ImmutableCollection` extend it and supply their update methods.

Consumers should generally typehint a contract rather than the abstract class.
A different storage implementation can implement the contracts without inheriting
an array-backed base, provided it preserves their documented behavior.
