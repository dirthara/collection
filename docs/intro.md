---
id: intro
title: Dirthara Collection
sidebar_position: 1
description: Ordered, keyed collections with explicit mutable and immutable APIs.
---

Dirthara Collection provides ordered collections with integer or string keys.
Use a mutable collection when callers should change its entries, and an
immutable collection when each change should produce a separate collection.

Both variants support the same read operations. Their child contracts expose
the appropriate update methods, so callers can express what they need through
a typehint.

| Collection | Updates | Suitable for |
| --- | --- | --- |
| `MutableCollection` | `set()`, `remove()`, and `clear()` change the receiver. | Building or maintaining a shared set of entries. |
| `ImmutableCollection` | `with()` and `without()` return a new collection. | Passing stable membership to other parts of an application. |

:::caution
Immutable collections do not freeze the objects they contain. Their entries
are immutable, but a stored entity can still change. Read about
[copying and object identity](operations/copying.md) before using them as snapshots.
:::

These are in-memory collections backed by PHP arrays. They eagerly consume
input iterables and preserve insertion order. They do not provide database
queries, grouping, persistence, or entity-specific identity rules.

Start with [installation](installation.md) and the
[getting started guide](getting-started.md). The
[shared API](collections.md) documents construction, keys, lookup, and iteration.
