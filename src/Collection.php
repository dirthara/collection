<?php

declare(strict_types=1);

namespace Dirthara\Collection;

use Traversable;
use Dirthara\Collection\Exception\CollectionException;
use Dirthara\Collection\Exception\KeyNotFoundException;
use Dirthara\Collection\Contract\Collection as CollectionContract;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements CollectionContract<TKey, TValue>
 */
abstract class Collection implements CollectionContract
{
    /**
     * @param iterable<TKey, TValue> $items
     */
    public function __construct(iterable $items = [])
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }

    public function count(): int
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }

    public function isEmpty(): bool
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }

    /**
     * @param TKey $key
     */
    public function has(int|string $key): bool
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }

    /**
     * @param TKey $key
     *
     * @return TValue
     *
     * @throws KeyNotFoundException
     */
    public function get(int|string $key): mixed
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }

    public function contains(mixed $value): bool
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }

    /**
     * @return list<TKey>
     */
    public function keys(): array
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }

    /**
     * @return list<TValue>
     */
    public function values(): array
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }

    /**
     * @return array<TKey, TValue>
     */
    public function toArray(): array
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }

    /**
     * @return Traversable<TKey, TValue>
     */
    public function getIterator(): Traversable
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }
}
