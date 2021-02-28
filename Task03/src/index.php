<?php

namespace App;

use App\Stack;

function checkIfBalanced(string $expression)
{
    $openingSymbols = array("<", "[", "{", "(");
    $closingSymbols = array(">", "]", "}", ")");
    $stack = new Stack();
    for ($i = 0; $i < strlen($expression); $i++) {
        if (in_array($expression[$i], $openingSymbols)) {
            $stack -> push($expression[$i]);
        } elseif (in_array($expression[$i], $closingSymbols)) {
            if ($stack -> isEmpty()) {
                return false;
            } elseif (preg_match("/\{\}|\[\]|\<\>|\(\)/", $stack -> top() . $expression[$i])) {
                $stack -> pop();
            } else {
                return false;
            }
        }
    }
    return $stack -> isEmpty();
}
