<?php

namespace AlekseyKudashkin\hangman\Controller;

use function AlekseyKudashkin\hangman\View\viewGame;

//игра
function startGame()
{
    $words = array("string", "letter", "artist", "arrive");
    $playWord = $words[array_rand($words)];
    $remainingLetters = substr($playWord, 1, -1);
    $maxAnswers = strlen($remainingLetters);
    $maxFaults = 6;

    $progress = "______";
    $progress[0] = $playWord[0];
    $progress[-1] = $playWord[-1];

    $faultCount = 0;
    $answersCount = 0;

    do {
        viewGame($faultCount, $progress);
        $letter = mb_strtolower(\cli\prompt("Буква"));
        $tempCount = 0;

        for ($i = 0; $i < strlen($remainingLetters); $i++) {
            if ($remainingLetters[$i] == $letter) {
                $progress[$i + 1] = $letter;
                $remainingLetters[$i] = " ";
                $answersCount++;
                $tempCount++;
            }
        }

        if ($tempCount == 0) {
            $faultCount++;
        }
    } while ($faultCount < $maxFaults && $answersCount < $maxAnswers);

        viewGame($faultCount, $progress);
        showResult($answersCount, $playWord);
}

//результат игры
function showResult($answersCount, $playWord)
{
    if ($answersCount == 4) {
        \cli\line("Вы победили!");
    } else {
        \cli\line("\nВы проиграли!");
    }
    \cli\line("\nИгровое слово было: $playWord\n");
}
