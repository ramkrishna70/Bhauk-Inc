<?php
/**
 * Tic Tac Toe
 * 
 * @author Amin Saeedi, <amin.w3dev@gmail.com>
 * @version 1.0
 */
require 'GameSearch.php';
class TicTacToe extends GameSearch 
{
	/**
	 * Indicates if the game is being drawn or not
	 * @param Board $board
	 */
	protected function isDrawn(Board $board)
	{
		$ret = true;
		for ($i=0; $i < count($board); $i++) {
			if ($board->get($i) == Board::BLANK) {
				$ret = false;
				break;
			}
		}
		return $ret;
	}
	
	/**
	 * Checks all the win states for player
	 * 
	 * @param Board $board
	 * @param boolean $player
	 */
	protected function isWon(Board $board, $player)
	{
		switch (true) {
			case ($this->winChecker(0,1,2, $player, $board)):
			case ($this->winChecker(3,4,5, $player, $board)):
			case ($this->winChecker(6,7,8, $player, $board)):
			case ($this->winChecker(0,3,6, $player, $board)):
			case ($this->winChecker(1,4,7, $player, $board)):
			case ($this->winChecker(2,5,8, $player, $board)):
			case ($this->winChecker(0,4,8, $player, $board)):
			case ($this->winChecker(2,4,6, $player, $board)):
				$rt = true;
				break;
		
			default:
				$rt = false;
				break;
		}
	    return $rt;
	}
	
	/**
	 * Checks if the user is going to be winner with this board situation
	 * 
	 * @param int $c1
	 * @param int $c2
	 * @param int $c3
	 * @param boolean $player
	 * @param Board $board
	 */
	protected function winChecker($c1, $c2, $c3, $player, Board $board)
	{
		 $b = Board::BLANK;
	     if ($player) {
	    	 	$b = Board::HUMAN;
	     }else{
	    	 	$b = Board::COM;
	     }
	     if ($board->get($c1) == $b && $board->get($c2) == $b && $board->get($c3) == $b) {
	    	 	return true;
	     }
	     return false;
	}
	
	/**
	 * Evaluetes the board position
	 * 
	 * @param Board $position
	 * @param boolean $player
	 * @return float
	 */
	protected function boardEvaluation(Board $position, $player)
	{
		 $count = 0;
	     for ($i = 0; $i < 9; $i++) {
	         if ($position->get($i) == Board::BLANK) $count++;
	     }
	     $count = 10 - $count;
	     
	     //prefer the center cell
	     $base = 1;
	     if ($position->get(4) == Board::HUMAN && $player) {
	         $base += 0.4;
	     }
	     if ($position->get(4) == Board::COM && !$player) {
	        $base -= 0.4;
	     }
	     $ret = ($base - 1);
	     if ($this->isWon($position, $player))  {
	         return $base + (1 / $count);
	     }
	     if ($this->isWon($position, !$player))  {
	         return -($base + (1 / $count));
	     }
	     $this->debug("Board Eval: $ret");
	     return $ret;
	}
	
	/**
	 * Returns all of the possible moves from current position
	 * 
	 * @param Board $position
	 * @param boolean $player
	 * @return array
	 */
	protected function possibleMoves(Board $position, $player)
	{
		 $count = 0;
	     for ($i=0; $i<9; $i++) {
	    	 	if ($position->get($i) == Board::BLANK) $count++;
	     }
	     if ($count == 0) return null;
	     $ret = array();
	     $count = 0;
	     for ($i=0; $i<9; $i++) {
	         if ($position->get($i) == Board::BLANK) {
	             $pos = new Board();
	             for ($j=0; $j<9; $j++) {
	            	 	$pos->set($j, $position->get($j));
	             }
	             if ($player) {
	            	 	$pos->set($i, Board::HUMAN);
	             }else {
	            	 	$pos->set($i, Board::COM);
	             }
	             $ret[$count++] = $pos;
	         }
	     }
	     return $ret;
	}
	
	/**
	 * Indicates if the search has reached the maximum depth or not
	 * 
	 * @param Board $board
	 * @return boolean
	 */
	protected function isMaxDepth(Board $board)
	{
		$ret = false;
	    if ($this->isWon($board, false)) $ret = true;
	    else if ($this->isWon($board, true))  $ret = true;
	    else if ($this->isDrawn($board)) $ret = true;
	    return $ret;
	}
}