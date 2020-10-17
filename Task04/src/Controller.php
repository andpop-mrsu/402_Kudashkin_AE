<?php

namespace AlekseyKudashkin\hangman\Controller;

use function AlekseyKudashkin\hangman\View\viewGame;

function mainMenu()
{
    while (true) {
        $command = \cli\prompt("Введите ключ");
        if ($command == "--new") {
            startGame();
        } elseif ($command == "--list") {
            listGames();
        } elseif (preg_match('/(^--replay [0-9]+$)/', $command) != 0) {
            replayGame(explode(' ', $command)[1]);
        } else {
            \cli\line("Неверный ключ");
        }
    }
}


function startGame()
{
    $db = openDatabase();

    $words = array("string", "letter", "artist", "arrive");
    date_default_timezone_set("Europe/Moscow");
    $gameData = date("d") . "." . date("m") . "." . date("Y");
    $gameTime = date("H") . ":" . date("i") . ":" . date("s");
    $playerName = getenv("username");
    $playWord = $words[array_rand($words)];

    $db->exec("INSERT INTO gamesInfo (
        gameData, 
        gameTime, 
        playerName, 
        playWord, 
        result
        ) VALUES (
        '$gameData', 
        '$gameTime', 
        '$playerName', 
        '$playWord', 
        'НЕ ЗАКОНЧЕНО')");

    $idGame = $db->querySingle("SELECT idGame FROM gamesInfo ORDER BY idGame DESC LIMIT 1");

    $remainingLetters = substr($playWord, 1, -1);
    $maxAnswers = strlen($remainingLetters);
    $maxFaults = 6;
    $progress = "______";
    $progress[0] = $playWord[0];
    $progress[-1] = $playWord[-1];

    $faultCount = 0;
    $answersCount = 0;
    $attempts = 0;

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
            $result = 0;
        } else {
            $result = 1;
        }

        $attempts++;

        $db->exec("INSERT INTO stepsInfo (
            idGame, 
            attempts, 
            letter, 
            result
            ) VALUES (
            '$idGame', 
            '$attempts', 
            '$letter', 
            '$result')");
    } while ($faultCount < $maxFaults && $answersCount < $maxAnswers);

    if ($faultCount < $maxFaults) {
        $result = "ПОБЕДА";
    } else {
        $result = "ПОРАЖЕНИЕ";
    }

    viewGame($faultCount, $progress);
    showResult($answersCount, $playWord);
    updateDB($idGame, $result);
}

function listGames()
{
    $db = openDatabase();
    $query = $db->query('SELECT * FROM gamesInfo');
    while ($row = $query->fetchArray()) {
        \cli\line("ID $row[0])\n    Дата:$row[1] $row[2]\n    Имя:$row[3]\n    Слово:$row[4]\n    Результат:$row[5]");
    }
}

function replayGame($id)
{
    $db = openDatabase();
    $idGame = $db->querySingle("SELECT EXISTS(SELECT 1 FROM gamesInfo WHERE idGame='$id')");

    if ($idGame == 1) {
        $query = $db->query("SELECT letter, result from stepsInfo where idGame = '$id'");
        $playWord = $db->querySingle("SELECT playWord from gamesInfo where idGame = '$id'");

        $progress = "______";
        $progress[0] = $playWord[0];
        $progress[-1] = $playWord[-1];
        $remainingLetters = substr($playWord, 1, -1);
        $faultCount = 0;

        while ($row = $query->fetchArray()) {
            viewGame($faultCount, $progress);
            $letter = $row[0];
            $result = $row[1];
            \cli\line("Буква: " . $letter);
            for ($i = 0; $i < strlen($remainingLetters); $i++) {
                if ($remainingLetters[$i] == $letter) {
                    $progress[$i + 1] = $letter;
                    $remainingLetters[$i] = " ";
                }
            }

            if ($result == 0) {
                $faultCount++;
            }
        }
        viewGame($faultCount, $progress);

        \cli\line($db->querySingle("SELECT result from gamesInfo where idGame = '$id'"));
    } else {
        \cli\line("Такой игры не обнаружено!");
    }
}


function updateDB($idGame, $result)
{
    $db = openDatabase();
    $db->exec("UPDATE gamesInfo
        SET result = '$result'
        WHERE idGame = '$idGame'");
}


function openDatabase()
{
    if (!file_exists("gamedb.db")) {
        $db = createDatabase();
    } else {
        $db = new \SQLite3('gamedb.db');
    }
    return $db;
}

function createDatabase()
{
    $db = new \SQLite3('gamedb.db');

    $gamesInfoTable = "CREATE TABLE gamesInfo(
        idGame INTEGER PRIMARY KEY,
        gameData DATE,
        gameTime TIME,
        playerName TEXT,
        playWord TEXT,
        result TEXT
    )";
    $db->exec($gamesInfoTable);


    $stepsInfoTable = "CREATE TABLE stepsInfo(
        idGame INTEGER,
        attempts INTEGER,
        letter TEXT,
        result INTEGER
    )";
    $db->exec($stepsInfoTable);

    return $db;
}


function showResult($answersCount, $playWord)
{
    if ($answersCount == 4) {
        \cli\line("Вы победили!");
    } else {
        \cli\line("\nВы проиграли!");
    }
    \cli\line("\nИгровое слово было: $playWord\n");
}
