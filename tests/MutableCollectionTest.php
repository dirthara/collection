<?php

declare(strict_types=1);

namespace Dirthara\Collection\Tests;

use stdClass;
use Dirthara\Collection\MutableCollection;
use Dirthara\Collection\Contract\Collection;
use Dirthara\Collection\Contract\MutableCollection as MutableCollectionContract;
use Dirthara\Collection\Contract\ImmutableCollection as ImmutableCollectionContract;

use function iterator_to_array;

final class MutableCollectionTest extends CollectionTestCase
{
    /**
     * @param iterable<array-key, mixed> $items
     * @return Collection<array-key, mixed>
     */
    protected function create(iterable $items = []): Collection
    {
        return new MutableCollection($items);
    }

    public function testMutationsPreserveOrderAndReportRemoval(): void
    {
        $collection = $this->create(['a' => 1, 7 => null]);
        self::assertInstanceOf(MutableCollectionContract::class, $collection);
        $collection->set('a', 2);
        $collection->set('b', 3);
        self::assertSame(['a' => 2, 7 => null, 'b' => 3], $collection->toArray());
        self::assertTrue($collection->remove(7));
        self::assertFalse($collection->remove(7));
        self::assertTrue($collection->remove('a'));
        $collection->set('a', 4);
        self::assertSame(['b' => 3, 'a' => 4], $collection->toArray());
        $collection->clear();
        $collection->clear();
        self::assertTrue($collection->isEmpty());
        $collection->set(5, null);
        self::assertTrue($collection->has(5));
    }

    public function testCopyAndConversionShareObjectsButNotEntries(): void
    {
        $entity = new stdClass();
        $collection = new MutableCollection(['entity' => $entity]);
        $copy = $collection->copy();
        $immutable = $collection->toImmutable();
        self::assertNotSame($collection, $copy);
        self::assertInstanceOf(ImmutableCollectionContract::class, $immutable);
        $copy->remove('entity');
        self::assertSame($entity, $collection->get('entity'));
        $collection->clear();
        self::assertSame($entity, $immutable->get('entity'));
        self::assertTrue($copy->isEmpty());
        self::assertTrue($collection->isEmpty());
    }

    public function testIteratorKeepsSnapshotWhenCollectionChanges(): void
    {
        $collection = new MutableCollection(['a' => 1]);
        $iterator = $collection->getIterator();
        $collection->set('a', 2);
        $collection->set('b', 3);
        self::assertSame(['a' => 1], iterator_to_array($iterator));
    }
}
