<?php

namespace AlekseyKudashkin\hangman\Model;

use RedBeanPHP\R as R;

use function AlekseyKudashkin\hangman\View\viewGame;

function startGameDB($words, $gameData, $gameTime, $playerName, $playWord)
{
    openDatabase();

    R::exec("INSERT INTO gamesInfo (
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

    return R::getCell("SELECT idGame FROM gamesInfo ORDER BY idGame DESC LIMIT 1");
}



function addGameStep($idGame, $attempts, $letter, $result)
{
    R::exec("INSERT INTO stepsInfo (
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
    R::exec("UPDATE gamesInfo
        SET result = '$result'
        WHERE idGame = '$idGame'");
}


function listGames()
{
    openDatabase();
    $result = R::getAll("SELECT * FROM 'gamesInfo'");
    foreach ($result as $row) {
        \cli\line("ID $row[idGame])");
        \cli\line("    Дата:$row[gameData] $row[gameTime]");
        \cli\line("    Имя:$row[playerName]");
        \cli\line("    Слово:$row[playWord]");
        \cli\line("    Результат:$row[result]");
    }
}


function replayGame($id)
{
    openDatabase();
    $idGame = R::getCell("SELECT EXISTS(SELECT 1 FROM gamesInfo WHERE idGame='$id')");

    if ($idGame == 1) {
        $query = R::getAll("SELECT letter, result from stepsInfo where idGame = '$id'");
        $playWord = R::getCell("SELECT playWord from gamesInfo where idGame = '$id'");

        $progress = "______";
        $progress[0] = $playWord[0];
        $progress[-1] = $playWord[-1];
        $remainingLetters = substr($playWord, 1, -1);
        $faultCount = 0;

        foreach ($query as $row) {
            viewGame($faultCount, $progress);
            $letter = $row['letter'];
            $result = $row['result'];
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

        \cli\line(R::getCell("SELECT result from gamesInfo where idGame = '$id'"));
    } else {
        \cli\line("Такой игры не обнаружено!");
    }
}


function openDatabase()
{
    if (!file_exists("gamedb.db")) {
        createDatabase();
    } else {
        R::setup("sqlite:gamedb.db");
    }
}


function createDatabase()
{
    R::setup("sqlite:gamedb.db");

    $gamesInfoTable = "CREATE TABLE gamesInfo(
        idGame INTEGER PRIMARY KEY,
        gameData DATE,
        gameTime TIME,
        playerName TEXT,
        playWord TEXT,
        result TEXT
    )";
    R::exec($gamesInfoTable);


    $stepsInfoTable = "CREATE TABLE stepsInfo(
        idGame INTEGER,
        attempts INTEGER,
        letter TEXT,
        result INTEGER
    )";
    R::exec($stepsInfoTable);
}
