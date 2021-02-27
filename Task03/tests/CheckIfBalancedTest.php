<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use function App\checkIfBalanced;

class CheckIfBalancedTest extends TestCase
{
    public function testCheckIfBalanced()
    {
        $this -> assertSame(true, checkIfBalanced("(ab[cd{}])"));
        $this -> assertSame(false, checkIfBalanced("(ab[cd{})"));
        $this -> assertSame(false, checkIfBalanced("(ab[cd{]})"));
    }
}
