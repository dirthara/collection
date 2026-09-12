<?php

declare(strict_types=1);

namespace Dirthara\Collection;

use Dirthara\Collection\Contract\MutableCollection as MutableCollectionContract;
use Dirthara\Collection\Contract\ImmutableCollection as ImmutableCollectionContract;

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
     * @param iterable<TKey, TValue> $items
     */
    public function __construct(iterable $items = [])
    {
        parent::__construct($items);
    }

    /**
     * @param TKey $key
     * @param TValue $value
     */
    public function set(int|string $key, mixed $value): void
    {
        $this->items[$key] = $value;
    }

    /**
     * @param TKey $key
     */
    public function remove(int|string $key): bool
    {
        if (!$this->has($key)) {
            return false;
        }

        unset($this->items[$key]);

        return true;
    }

    public function clear(): void
    {
        $this->items = [];
    }

    /**
     * @return self<TKey, TValue>
     */
    public function copy(): self
    {
        return clone $this;
    }

    /**
     * @return ImmutableCollectionContract<TKey, TValue>
     */
    public function toImmutable(): ImmutableCollectionContract
    {
        return new ImmutableCollection($this->items);
    }
}
