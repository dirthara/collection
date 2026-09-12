<?php

declare(strict_types=1);

namespace Dirthara\Collection;

use Traversable;
use ArrayIterator;
use Dirthara\Collection\Exception\KeyNotFoundException;
use Dirthara\Collection\Contract\Collection as CollectionContract;

use function count;
use function in_array;
use function array_keys;
use function array_values;
use function array_key_exists;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements CollectionContract<TKey, TValue>
 */
abstract class Collection implements CollectionContract
{
    /**
     * @var array<TKey, TValue>
     */
    protected array $items = [];

    /**
     * @param iterable<TKey, TValue> $items
     */
    public function __construct(iterable $items = [])
    {
        foreach ($items as $key => $value) {
            $this->items[$key] = $value;
        }
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    /**
     * @param TKey $key
     */
    public function has(int|string $key): bool
    {
        return array_key_exists($key, $this->items);
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
        if (!$this->has($key)) {
            throw new KeyNotFoundException('Collection key does not exist.', context: [
                'operation' => 'get',
                'key' => $key,
            ]);
        }

        return $this->items[$key];
    }

    public function contains(mixed $value): bool
    {
        return in_array($value, $this->items, strict: true);
    }

    /**
     * @return list<TKey>
     */
    public function keys(): array
    {
        return array_keys($this->items);
    }

    /**
     * @return list<TValue>
     */
    public function values(): array
    {
        return array_values($this->items);
    }

    /**
     * @return array<TKey, TValue>
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @return Traversable<TKey, TValue>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
