<?php

declare(strict_types=1);

namespace Dirthara\Collection;

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
     * @param iterable<TKey, TValue> $items
     */
    public function __construct(iterable $items = [])
    {
        parent::__construct($items);
    }

    /**
     * @param TKey $key
     * @param TValue $value
     *
     * @return self<TKey, TValue>
     */
    public function with(int|string $key, mixed $value): self
    {
        $collection = clone $this;
        $collection->items[$key] = $value;

        return $collection;
    }

    /**
     * @param TKey $key
     *
     * @return self<TKey, TValue>
     */
    public function without(int|string $key): self
    {
        $collection = clone $this;
        unset($collection->items[$key]);

        return $collection;
    }

    /**
     * @return MutableCollection<TKey, TValue>
     */
    public function toMutable(): MutableCollection
    {
        return new MutableCollection($this->items);
    }
}
