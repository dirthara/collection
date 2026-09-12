<?php

declare(strict_types=1);

namespace Dirthara\Collection;

use Dirthara\Collection\Exception\CollectionException;
use Dirthara\Collection\Contract\ImmutableCollection as ImmutableCollectionContract;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @extends Collection<TKey, TValue>
 * @implements ImmutableCollectionContract<TKey, TValue>
 */
final class ImmutableCollection extends Collection implements ImmutableCollectionContract
{
    /**
     * @param TKey $key
     * @param TValue $value
     *
     * @return self<TKey, TValue>
     */
    public function with(int|string $key, mixed $value): self
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }

    /**
     * @param TKey $key
     *
     * @return self<TKey, TValue>
     */
    public function without(int|string $key): self
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }

    /**
     * @return MutableCollection<TKey, TValue>
     */
    public function toMutable(): MutableCollection
    {
        throw new CollectionException('Collection API is not implemented yet.');
    }
}
