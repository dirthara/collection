<?php

declare(strict_types=1);

namespace Dirthara\Collection\Contract;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @extends Collection<TKey, TValue>
 */
interface ImmutableCollection extends Collection
{
    /**
     * @param TKey $key
     * @param TValue $value
     *
     * @return self<TKey, TValue>
     */
    public function with(int|string $key, mixed $value): self;

    /**
     * @param TKey $key
     *
     * @return self<TKey, TValue>
     */
    public function without(int|string $key): self;

    /**
     * @return MutableCollection<TKey, TValue>
     */
    public function toMutable(): MutableCollection;
}
