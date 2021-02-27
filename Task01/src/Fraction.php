<?php

namespace App;

class Fraction
{
    private $numerator;
    private $denominator;
    public function __construct($numerator, $denominator)
    {
        if ($denominator <= 0) {
            echo "Введите знаменатель больше 0";
            exit();
        }

        $this -> reduceFraction($numerator, $denominator);
    }

    private function reduceFraction($numerator, $denominator)
    {
        $greatestCommonFactor = $this -> gcd($numerator, $denominator);
        if ($greatestCommonFactor == 1) {
            $this -> numerator = $numerator;
            $this -> denominator = $denominator;
        } else {
            $this -> numerator = $numerator / $greatestCommonFactor;
            $this -> denominator = $denominator / $greatestCommonFactor;
        }
    }

    public function getNumer(): int
    {
        return $this -> numerator;
    }

    public function getDenom(): int
    {
        return $this -> denominator;
    }

    private function gcd($a, $b): int
    {
        return ($a % $b) ? $this -> gcd($b, $a % $b) : abs($b);
    }

    public function add(Fraction $frac): Fraction
    {
        $numerator = $frac -> denominator * $this -> numerator + $this -> denominator * $frac -> numerator;
        $denominator = $this -> denominator * $frac -> denominator;
        return new Fraction($numerator, $denominator);
    }

    public function sub(Fraction $frac): Fraction
    {
        $numerator = $frac -> denominator * $this -> numerator - $this -> denominator * $frac -> numerator;
        $denominator = $this -> denominator * $frac -> denominator;
        return new Fraction($numerator, $denominator);
    }

    private function convert(): string
    {
        $integerPart = intval($this -> numerator / $this -> denominator);
        $numerator = abs($this -> numerator % $this -> denominator);
        return $integerPart . "'" . $numerator . "/" . $this -> denominator;
    }

    public function __toString(): string
    {
        if (abs($this -> numerator) > $this -> denominator) {
            return $this -> convert();
        }

        return $this -> numerator . "/" . $this -> denominator;
    }
}
