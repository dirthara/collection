---
id: error-handling
title: Error handling
sidebar_position: 7
description: Missing keys, the exception hierarchy, diagnostic context, and logging.
---

## Missing keys

`get()` throws `Dirthara\Collection\Exception\KeyNotFoundException` when a key
does not exist. This exception extends
`Dirthara\Collection\Exception\CollectionException`, the package base exception.

```php
use Dirthara\Collection\ImmutableCollection;
use Dirthara\Collection\Exception\KeyNotFoundException;

$names = new ImmutableCollection(['first' => 'Ada']);

try {
    $name = $names->get('second');
} catch (KeyNotFoundException $exception) {
    $context = $exception->getContext();
}
```

Here, `$context` contains `['operation' => 'get', 'key' => 'second']`.
The exception message is `Collection key does not exist.`

Use `has()` when a missing entry is expected. A present entry containing `null`
returns `true` from `has()` and `null` from `get()`. Neither `remove()` nor
`without()` throws for an absent key: `remove()` returns `false`, and
`without()` returns a collection with unchanged entries.

## Exception constructor

The base exception extends PHP's `Exception`. Specialized exceptions inherit
its constructor and context methods.

| Parameter | Type | Default | Meaning |
| --- | --- | --- | --- |
| `message` | `string` | `''` | Exception message. |
| `code` | `int` | `0` | Application-defined exception code. |
| `previous` | `?Throwable` | `null` | Original cause, when wrapping another exception. |
| `context` | `array<string, mixed>` | `[]` | Diagnostic metadata, empty when none is supplied. |

| Method | Return type | Behavior |
| --- | --- | --- |
| `getContext()` | `array<string, mixed>` | Return the diagnostic context. |
| `addContext(array $context)` | `static` | Merge context into this exception and return it. New values replace matching string keys. |

Catch the base exception when a handler should cover every exception type
provided by this package. Catch `KeyNotFoundException` when absence needs
specific handling.

## Logging context

Exceptions do not log themselves or require a logging dependency. Application
handlers can pass their context to a PSR-3 logger. Assign the caught exception
to the `exception` context key after reading the other context, so a pre-existing
value under that key cannot replace the caught exception.

```php
use Dirthara\Collection\ImmutableCollection;
use Dirthara\Collection\Exception\CollectionException;

$names = new ImmutableCollection();

try {
    $names->get('first');
} catch (CollectionException $exception) {
    $context = $exception->getContext();
    $context['exception'] = $exception;
}
```

The application can now supply `$context` to its logger's error method.

:::caution
Missing-key errors include the requested key, but do not include stored values
in their message or context. Choose keys accordingly if diagnostic logs must
not contain sensitive identifiers. Do not add credentials or sensitive entity
values when enriching context in the application.
:::

## Invalid input and producers

The collection contract requires integer or string keys. Generic value types
are checked by static analysis, not by runtime entity validation. Invalid PHP
operations can still raise PHP errors, and exceptions raised by an input
iterator during construction propagate to the caller. The collection does not
convert arbitrary producer failures into missing-key exceptions.
