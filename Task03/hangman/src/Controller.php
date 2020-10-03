<?php
namespace AlekseyKudashkin\hangman\Controller; 
use function AlekseyKudashkin\hangman\View\viewGame;

//игра
function startGame() 
{		
    $word = "string";
    $remainingLetters = substr($word, 1, -1);

    $progress = "______";
    $progress[0] = $word[0];
    $progress[-1] = $word[-1];

    $faultCount = 0;
    $answersCount = 0;

    do {
        viewGame($faultCount, $progress);
        $letter = mb_strtolower(readline("Буква: "));
        $tempCount = 0;

        for ($i = 0; $i < strlen($remainingLetters); $i++) {
            if ($remainingLetters[$i] == $letter ) {
                $progress[$i + 1] = $letter;
                $remainingLetters[$i] = " ";
                $answersCount++;
                $tempCount++;
            }
        }

        if ($tempCount == 0) {
            $faultCount++;
        }


		} while ($faultCount != 6 && $answersCount != 4);

		viewGame($faultCount, $progress);
		showResult($answersCount, $word);
}

//результат игры
function showResult( $answersCount, $word ) 
{           
    if ($answersCount == 4)
        echo "\nВы победили!";
    else
        echo "\nВы проиграли!";
     echo "\nИгровое слово было: $word\n";
}