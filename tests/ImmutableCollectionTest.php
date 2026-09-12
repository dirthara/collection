<?php

declare(strict_types=1);

namespace Dirthara\Collection\Tests;

use stdClass;
use Dirthara\Collection\Contract\Collection;
use Dirthara\Collection\ImmutableCollection;
use Dirthara\Collection\Contract\MutableCollection as MutableCollectionContract;
use Dirthara\Collection\Contract\ImmutableCollection as ImmutableCollectionContract;

final class ImmutableCollectionTest extends CollectionTestCase
{
    /**
     * @param iterable<array-key, mixed> $items
     * @return Collection<array-key, mixed>
     */
    protected function create(iterable $items = []): Collection
    {
        return new ImmutableCollection($items);
    }

    public function testWithReturnsIndependentEntriesAndPreservesOrder(): void
    {
        $original = $this->create(['a' => 1, 7 => null]);
        self::assertInstanceOf(ImmutableCollectionContract::class, $original);
        $replaced = $original->with('a', 2);
        $added = $replaced->with('b', 3);
        self::assertNotSame($original, $replaced);
        self::assertSame(['a' => 1, 7 => null], $original->toArray());
        self::assertSame(['a' => 2, 7 => null], $replaced->toArray());
        self::assertSame(['a' => 2, 7 => null, 'b' => 3], $added->toArray());
    }

    public function testWithoutHandlesNullAndMissingKeysWithoutChangingOriginal(): void
    {
        $original = new ImmutableCollection(['a' => 1, 7 => null]);
        $removed = $original->without(7);
        $missing = $original->without('missing');
        self::assertSame(['a' => 1, 7 => null], $original->toArray());
        self::assertSame(['a' => 1], $removed->toArray());
        self::assertSame($original->toArray(), $missing->toArray());
        self::assertTrue($removed->without('a')->isEmpty());
        self::assertSame([7 => null, 'a' => 2], $original->without('a')->with('a', 2)->toArray());
    }

    public function testConversionSharesObjectsButNotEntries(): void
    {
        $entity = new stdClass();
        $original = new ImmutableCollection(['entity' => $entity]);
        $mutable = $original->toMutable();
        self::assertInstanceOf(MutableCollectionContract::class, $mutable);
        self::assertSame($entity, $mutable->get('entity'));
        $mutable->clear();
        self::assertSame($entity, $original->get('entity'));
        self::assertSame($entity, $original->with('other', $entity)->get('entity'));
    }
}
