<?php

declare(strict_types=1);

namespace Dirthara\Collection\Contract;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @extends Collection<TKey, TValue>
 */
interface MutableCollection extends Collection
{
    /**
     * @param TKey $key
     * @param TValue $value
     */
    public function set(int|string $key, mixed $value): void;

    /**
     * @param TKey $key
     */
    public function remove(int|string $key): bool;

    public function clear(): void;

    /**
     * @return self<TKey, TValue>
     */
    public function copy(): self;

    /**
     * @return ImmutableCollection<TKey, TValue>
     */
    public function toImmutable(): ImmutableCollection;
}
