<?php

declare(strict_types=1);

namespace Dirthara\Collection\Tests;

use RuntimeException;
use PHPUnit\Framework\TestCase;
use Dirthara\Collection\Exception\CollectionException;
use Dirthara\Collection\Exception\KeyNotFoundException;

final class CollectionExceptionTest extends TestCase
{
    public function testDefaults(): void
    {
        $exception = new CollectionException();
        self::assertSame('', $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertNull($exception->getPrevious());
        self::assertSame([], $exception->getContext());
    }

    public function testSpecializedExceptionPreservesCauseAndMergesContext(): void
    {
        $previous = new RuntimeException('cause');
        $exception = new KeyNotFoundException('missing', 12, $previous, ['key' => 'a', 'operation' => 'get']);
        self::assertSame('missing', $exception->getMessage());
        self::assertSame(12, $exception->getCode());
        self::assertSame($previous, $exception->getPrevious());
        self::assertSame($exception, $exception->addContext(['key' => 'b', 'caller' => 'entity']));
        self::assertSame(['key' => 'b', 'operation' => 'get', 'caller' => 'entity'], $exception->getContext());
    }
}
