<?php
set_time_limit(0);

require 'Board.php';
require 'TicTacToe.php';

$board = explode(',', $_POST['board']);
foreach ($board as $key=>$b){
	$board[$key] = intval($b);
}

$ttt = new TicTacToe();
$response = $ttt->play($board);
echo json_encode($response);
