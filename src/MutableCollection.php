<?php

declare(strict_types=1);

namespace Dirthara\Collection;

use Dirthara\Collection\Exception\CollectionException;
use Dirthara\Collection\Contract\MutableCollection as MutableCollectionContract;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @extends Collection<TKey, TValue>
 * @implements MutableCollectionContract<TKey, TValue>
 */
final class MutableCollection extends Collection implements MutableCollectionContract
{
    /**
     * @param TKey $key
     * @param TValue $value
     */
    public function set(int|string $key, mixed $value): void
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }

    /**
     * @param TKey $key
     */
    public function remove(int|string $key): bool
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }

    public function clear(): void
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }

    /**
     * @return self<TKey, TValue>
     */
    public function copy(): self
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }

    /**
     * @return ImmutableCollection<TKey, TValue>
     */
    public function toImmutable(): ImmutableCollection
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }
}
