<?php

namespace App\Test;

use App\Fraction;

function runTest()
{
    $m1 = new Fraction(10, 100);
    echo $m1 . "\n"; // 1/10

    $m2 = new Fraction(107, 10);
    echo $m2 . "\n"; // 10'7/10

    $m3 = $m1 -> add($m2);
    echo $m3 . "\n"; // 10'4/5

    $m4 = new Fraction(111, 555);
    $m5 = $m1 -> sub($m4);
    echo $m5 . "\n"; // -1/10

    $m6 = new Fraction(77, 177);
    echo $m6 . "\n"; // 77/177
    echo $m6 -> getNumer() . "\n"; // 77
    echo $m6 -> getDenom() . "\n"; // 177

    $m7 = $m1 -> sub($m2);
    echo $m7 . "\n"; // -10'3/5
}
