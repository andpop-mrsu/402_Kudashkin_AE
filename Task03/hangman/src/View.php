<?php

    namespace AlekseyKudashkin\hangman\View;

function viewGame($faultCount, $progress)
{
      //псведографика
    $graphic = array (
        " +---+\n     |\n     |\n     |\n    ===\n ",
        " +---+\n 0   |\n     |\n     |\n    ===\n ",
        " +---+\n 0   |\n |   |\n     |\n    ===\n ",
        " +---+\n 0   |\n/|   |\n     |\n    ===\n ",
        " +---+\n 0   |\n/|\  |\n     |\n    ===\n ",
        " +---+\n 0   |\n/|\  |\n/    |\n    ===\n ",
        " +---+\n 0   |\n/|\  |\n/ \  |\n    ===\n "
    );

    \cli\line($graphic[$faultCount]);
    \cli\line($progress);

    echo "\n";
}
