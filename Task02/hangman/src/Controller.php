<?php
	namespace AlekseyKudashkin\hangman\Controller;
	use function AlekseyKudashkin\hangman\View\viewGame;
	
	function startGame(){
		echo "Start\n";
		viewGame();
	}

?>
