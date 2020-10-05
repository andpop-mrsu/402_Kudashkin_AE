<?php
	namespace AlekseyKudashkin\hangman\View;

	function viewGame($faultCount, $progress){		//псведографика
		$graphic = array (
		    " +---+\n     |\n     |\n     |\n    ===\n ",
		    " +---+\n 0   |\n     |\n     |\n    ===\n ",
		    " +---+\n 0   |\n |   |\n     |\n    ===\n ",
		    " +---+\n 0   |\n/|   |\n     |\n    ===\n ",
		    " +---+\n 0   |\n/|\  |\n     |\n    ===\n ",
		    " +---+\n 0   |\n/|\  |\n/    |\n    ===\n ",
		    " +---+\n 0   |\n/|\  |\n/ \  |\n    ===\n "
		);

		echo $graphic[$faultCount];

		for($i = 0; $i < strlen($progress); $i++)
			echo $progress[$i];

		echo "\n";
	}
