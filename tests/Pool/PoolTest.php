<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Pool;

use PHPUnit\Framework\TestCase;

class PoolTest extends TestCase
{
    public function testBorrow(): void
    {
        $pool                       = new Pool(new SomeFactory(), 3);
        $object                     = $pool->borrow();

        $this->assertInstanceOf(DecoratorInterface::class, $object);
        $this->assertInstanceOf(SomeObject::class, $object->getOriginalObject());
        $this->assertEquals('object1', $object->getOriginalObject()->name);
    }

    public function testReturn(): void
    {
        $pool                       = new Pool(new SomeFactory(), 3);
        $object                     = $pool->borrow();

        $this->assertInstanceOf(DecoratorInterface::class, $object);
        $this->assertInstanceOf(SomeObject::class, $object->getOriginalObject());
        $this->assertEquals('object1', $object->getOriginalObject()->name);

        $pool->return($object);

        $this->assertNull($object->getOriginalObject());

        $this->assertEquals(0, $pool->getUsed(), 'Pool should have no used objects');
    }

    public function testBorrowAll(): void
    {
        $pool                       = new Pool(new SomeFactory(), 3);

        $object1                    = $pool->borrow();
        $object2                    = $pool->borrow();
        $object3                    = $pool->borrow();

        $this->assertInstanceOf(DecoratorInterface::class, $object1);
        $this->assertInstanceOf(DecoratorInterface::class, $object2);
        $this->assertInstanceOf(DecoratorInterface::class, $object3);

        $this->assertInstanceOf(SomeObject::class, $object1->getOriginalObject());
        $this->assertInstanceOf(SomeObject::class, $object2->getOriginalObject());
        $this->assertInstanceOf(SomeObject::class, $object3->getOriginalObject());

        $this->assertEquals('object1', $object1->getOriginalObject()->name);
        $this->assertEquals('object2', $object2->getOriginalObject()->name);
        $this->assertEquals('object3', $object3->getOriginalObject()->name);

        $pool->return($object1);
        $pool->return($object2);
        $pool->return($object3);

        $this->assertNull($object1->getOriginalObject());
        $this->assertNull($object2->getOriginalObject());
        $this->assertNull($object3->getOriginalObject());

        $this->assertEquals(0, $pool->getUsed(), 'Pool should have no used objects');
    }

    public function testRebuild(): void
    {
        $pool = new Pool(new SomeFactory(), 3);

        $object1 = $pool->borrow();
        $object2 = $pool->borrow();
        $object3 = $pool->borrow();

        $this->assertInstanceOf(DecoratorInterface::class, $object1);
        $this->assertInstanceOf(DecoratorInterface::class, $object2);
        $this->assertInstanceOf(DecoratorInterface::class, $object3);

        $this->assertInstanceOf(SomeObject::class, $object1->getOriginalObject());
        $this->assertInstanceOf(SomeObject::class, $object2->getOriginalObject());
        $this->assertInstanceOf(SomeObject::class, $object3->getOriginalObject());

        $this->assertEquals('object1', $object1->getOriginalObject()->name);
        $this->assertEquals('object2', $object2->getOriginalObject()->name);
        $this->assertEquals('object3', $object3->getOriginalObject()->name);

        $pool->return($object1);
        $pool->return($object2);
        $pool->return($object3);

        $this->assertNull($object1->getOriginalObject());
        $this->assertNull($object2->getOriginalObject());
        $this->assertNull($object3->getOriginalObject());

        $this->assertEquals(0, $pool->getUsed(), 'Pool should have no used objects');

        $pool->rebuild();

        $object1 = $pool->borrow();
        $object2 = $pool->borrow();
        $object3 = $pool->borrow();

        $this->assertInstanceOf(DecoratorInterface::class, $object1);
        $this->assertInstanceOf(DecoratorInterface::class, $object2);
        $this->assertInstanceOf(DecoratorInterface::class, $object3);

        $this->assertInstanceOf(SomeObject::class, $object1->getOriginalObject());
        $this->assertInstanceOf(SomeObject::class, $object2->getOriginalObject());
        $this->assertInstanceOf(SomeObject::class, $object3->getOriginalObject());

        $this->assertEquals('object4', $object1->getOriginalObject()->name);
        $this->assertEquals('object5', $object2->getOriginalObject()->name);
    }
}
