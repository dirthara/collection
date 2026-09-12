---
id: copying
title: Copying and object identity
sidebar_position: 3
description: Independent collection entries, conversions, and shallow object sharing.
---

## Separate containers

| Operation | Result |
| --- | --- |
| Assign a collection to another variable | Both variables refer to the same collection object. |
| `MutableCollection::copy()` | A separate mutable collection with the same entries. |
| `MutableCollection::toImmutable()` | A separate immutable collection with the same entries. |
| `ImmutableCollection::toMutable()` | A separate mutable collection with the same entries. |
| `ImmutableCollection::with()` or `without()` | A separate immutable collection with updated entries. |

```php
use Dirthara\Collection\MutableCollection;

$original = new MutableCollection(['first' => 'Ada']);
$copy = $original->copy();
$snapshot = $original->toImmutable();

$original->set('first', 'Grace');
$copy->set('second', 'Lin');
```

The original contains `['first' => 'Grace']`, the copy contains
`['first' => 'Ada', 'second' => 'Lin']`, and the snapshot contains
`['first' => 'Ada']`.

## Objects keep their identity

Copies and conversions do not clone stored objects. This is useful for entity
collections: the same entity remains the same object across multiple containers.

```php
use Dirthara\Collection\MutableCollection;

$entity = (object) ['id' => 7, 'name' => 'Ada'];
$entities = new MutableCollection([7 => $entity]);
$snapshot = $entities->toImmutable();

$entity->name = 'Grace';
$entities->remove(7);

$stored = $snapshot->get(7);
$name = $stored->name;
```

`$name` is `'Grace'`, and `$stored` is the same object as `$entity`. Removing
it from the mutable collection does not remove it from the snapshot.
`contains()` also compares objects by identity rather than by their properties.

:::caution
An immutable collection is a snapshot of membership and stored values, not a
deep snapshot of an object graph. Objects and nested PHP references remain
shared. Use immutable entities or explicitly copy values in the consuming
application when their state must be independent too.
:::

## Exported arrays and iterators

`toArray()`, `keys()`, and `values()` return arrays whose entries can be changed
without replacing entries in the collection. Iterators capture the current
entries when created. Objects within exported values retain the same identity.

The constructor copies entries from its input iterable. Later changes to the
input container do not insert, replace, or remove collection entries. This does
not deep-copy objects or nested references within those entries.

See [array exports and iteration](../collections.md#arrays-and-iteration).
