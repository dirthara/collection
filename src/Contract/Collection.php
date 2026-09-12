<?php

declare(strict_types=1);

namespace Dirthara\Collection\Contract;

use Countable;
use Traversable;
use IteratorAggregate;
use Dirthara\Collection\Exception\KeyNotFoundException;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @extends IteratorAggregate<TKey, TValue>
 */
interface Collection extends Countable, IteratorAggregate
{
    public function count(): int;

    public function isEmpty(): bool;

    /**
     * @param TKey $key
     */
    public function has(int|string $key): bool;

    /**
     * @param TKey $key
     *
     * @return TValue
     *
     * @throws KeyNotFoundException
     */
    public function get(int|string $key): mixed;

    public function contains(mixed $value): bool;

    /**
     * @return list<TKey>
     */
    public function keys(): array;

    /**
     * @return list<TValue>
     */
    public function values(): array;

    /**
     * @return array<TKey, TValue>
     */
    public function toArray(): array;

    /**
     * @return Traversable<TKey, TValue>
     */
    public function getIterator(): Traversable;
}
