<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Queue;

class QueueTest extends TestCase
{
    public function testEnqueueAndEmpty()
    {
        $queue = new Queue();
        $this -> assertSame(true, $queue -> isEmpty());
        $queue -> enqueue("10000", 1212);
        $this -> assertSame(false, $queue -> isEmpty());
    }

    public function testPeek()
    {
        $queue = new Queue(12, 3, "343", "3434", 45);
        $this -> assertSame(12, $queue -> peek());
    }

    public function testDequeue()
    {
        $queue1 = new Queue(1, 5, 7, 3, "24453", 22);
        $queue2 = new Queue();
        $this -> assertSame(1, $queue1 -> dequeue());
        $this -> assertSame(5, $queue1 -> peek());
        $this -> assertSame(null, $queue2 -> dequeue());
    }

    public function testTextRepresentation()
    {
        $queue = new Queue(5, 4, 3, 2, 1, "abra", 111111);
        $this -> assertSame("[5<-4<-3<-2<-1<-abra<-111111]", $queue -> __toString());
    }

    public function testCopy()
    {
        $queue = new Queue(5, 1, 62, 46, "345fdfdfd", 43);
        $newQueue = $queue -> copy();
        $this -> assertInstanceOf(Queue::class, $newQueue);
        $this -> assertSame(false, $newQueue -> isEmpty());
        $this -> assertSame(5, $newQueue -> peek());
    }
}
