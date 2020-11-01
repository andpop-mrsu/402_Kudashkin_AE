<?php

namespace AlekseyKudashkin\hangman\Model;
use function AlekseyKudashkin\hangman\View\viewGame;

function startGameDB($words, $gameData, $gameTime, $playerName, $playWord)
{
    $db = openDatabase();

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

    return $db->querySingle("SELECT idGame FROM gamesInfo ORDER BY idGame DESC LIMIT 1");
}



function addGameStep($idGame, $attempts, $letter, $result)
{
    $db = openDatabase();

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
}


function updateResult($idGame, $result)
{
    $db = openDatabase();
    $db->exec("UPDATE gamesInfo
        SET result = '$result'
        WHERE idGame = '$idGame'");
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