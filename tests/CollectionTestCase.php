<?php

declare(strict_types=1);

namespace Dirthara\Collection\Tests;

use stdClass;
use Generator;
use ArrayIterator;
use PHPUnit\Framework\TestCase;
use Dirthara\Collection\Contract\Collection;
use Dirthara\Collection\Exception\KeyNotFoundException;

use function count;
use function iterator_to_array;

abstract class CollectionTestCase extends TestCase
{
    /**
     * @param iterable<array-key, mixed> $items
     * @return Collection<array-key, mixed>
     */
    abstract protected function create(iterable $items = []): Collection;

    public function testEmptyCollection(): void
    {
        $collection = $this->create();

        self::assertTrue($collection->isEmpty());
        self::assertSame(0, $collection->count());
        self::assertSame(0, count($collection));
        self::assertSame([], $collection->keys());
        self::assertSame([], $collection->values());
        self::assertSame([], $collection->toArray());
        self::assertSame([], iterator_to_array($collection));
        self::assertFalse($collection->has('missing'));
        self::assertFalse($collection->contains(null));
    }

    public function testPreservesKeysAndInsertionOrder(): void
    {
        $items = ['third' => 3, 9 => 'nine', -2 => false, '' => null];
        $collection = $this->create($items);

        self::assertFalse($collection->isEmpty());
        self::assertSame(4, count($collection));
        self::assertSame($items, $collection->toArray());
        self::assertSame($items, iterator_to_array($collection));
        self::assertSame(['third', 9, -2, ''], $collection->keys());
        self::assertSame([3, 'nine', false, null], $collection->values());
        self::assertSame('nine', $collection->get(9));
        self::assertTrue($collection->has(''));
        self::assertNull($collection->get(''));
    }

    public function testConsumesGeneratorsAndKeepsLastDuplicateValueAtOriginalPosition(): void
    {
        $collection = $this->create(self::duplicateItems());

        self::assertSame(['a' => 3, 7 => 2, 1 => 4], $collection->toArray());
        self::assertSame($collection->toArray(), iterator_to_array($collection));
        self::assertSame($collection->toArray(), iterator_to_array($collection));
    }

    public function testNumericStringKeysFollowPhpArraySemantics(): void
    {
        $collection = $this->create(['42' => 'value', '042' => 'padded']);

        self::assertSame([42, '042'], $collection->keys());
        self::assertSame('value', $collection->get('42'));
        self::assertSame('value', $collection->get(42));
        self::assertSame('padded', $collection->get('042'));
    }

    public function testMissingKeyHasDiagnosticContextWithoutCollectionValues(): void
    {
        $collection = $this->create(['payload' => 'private-value']);

        try {
            $collection->get('missing');
            self::fail('Expected a missing-key exception.');
        } catch (KeyNotFoundException $exception) {
            self::assertSame('Collection key does not exist.', $exception->getMessage());
            self::assertSame(['operation' => 'get', 'key' => 'missing'], $exception->getContext());
        }
    }

    public function testContainsUsesStrictComparisonAndObjectIdentity(): void
    {
        $entity = new stdClass();
        $collection = $this->create([1, null, $entity, ['id' => 1]]);

        self::assertTrue($collection->contains(1));
        self::assertTrue($collection->contains(null));
        self::assertTrue($collection->contains($entity));
        self::assertTrue($collection->contains(['id' => 1]));
        self::assertFalse($collection->contains('1'));
        self::assertFalse($collection->contains(true));
        self::assertFalse($collection->contains(new stdClass()));
        self::assertFalse($collection->contains(['id' => '1']));
    }

    public function testInputAndExportedContainersAreIndependent(): void
    {
        $value = 'original';
        $input = ['key' => &$value];
        $collection = $this->create($input);
        $value = 'changed';
        $input['new'] = 'new';
        $output = $collection->toArray();
        $output['key'] = 'exported';

        self::assertSame(['key' => 'original'], $collection->toArray());
        self::assertSame(['key' => 'exported'], $output);
    }

    public function testInputIteratorAndExportedIteratorCannotChangeEntries(): void
    {
        $input = new ArrayIterator(['key' => 'original']);
        $collection = $this->create($input);
        $input['key'] = 'changed';
        $iterator = $collection->getIterator();
        self::assertInstanceOf(ArrayIterator::class, $iterator);
        $iterator['key'] = 'changed';
        $iterator['new'] = 'new';

        self::assertSame(['key' => 'original'], $collection->toArray());
    }

    public function testStoredObjectsRemainMutable(): void
    {
        $entity = new stdClass();
        $entity->name = 'before';
        $collection = $this->create(['entity' => $entity]);
        $entity->name = 'after';

        self::assertSame($entity, $collection->get('entity'));
        /** @var stdClass $stored */
        $stored = $collection->get('entity');
        self::assertInstanceOf(stdClass::class, $stored);
        self::assertSame('after', $stored->name);
    }

    /**
     * @return Generator<array-key, int, void, void>
     */
    private static function duplicateItems(): Generator
    {
        yield 'a' => 1;
        yield 7 => 2;
        yield 'a' => 3;
        yield 1 => 4;
    }
}
