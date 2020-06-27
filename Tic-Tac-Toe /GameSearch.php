<?php
/**
 * Abstract class to perform alpha beta search in two-player zero-sum games
 * 
 * @abstract
 * @author Amin Saeedi, <amin.w3dev@gmail.com>
 * @version 1.0
 */
abstract class GameSearch
{
	const DEBUG = false;
	
	const HUMAN = true;
	const COM = false;
	
	protected abstract function isDrawn(Board $board);
	protected abstract function isWon(Board $board, $player);
	protected abstract function boardEvaluation(Board $board, $player);
	protected abstract function possibleMoves(Board $board, $player);
	protected abstract function isMaxDepth(Board $board);
	
	protected function debug($msg)
	{
		if (self::DEBUG) {
			echo $msg . "\n\r";
		}
	}
	
	/**
	 * Starts searching with initial alpha and beta to create minimax tree 
	 * 
	 * @param Board $board : current board position
	 * @param boolean $player
	 */
	public function alphaBeta(Board $board, $player)
	{
		return $this->alphaBetaHelper($board, $player, 1000000, -1000000);
	}
	
	/**
	 * Alpha-Beta Search
	 * 
	 * @param Board $board
	 * @param boolean $player
	 * @param int $alpha
	 * @param int $beta
	 */
	protected function alphaBetaHelper(Board $board, $player, $alpha, $beta)
	{
		if ($this->isMaxDepth($board)) {
			$val = $this->boardEvaluation($board, $player);
			$this->debug("alphaBetaHelper mx depth -> alpha : $alpha | beta : $beta | player : $player | board : $board");
			return array($val, null);
		}
		$best = array();
		$moves = $this->possibleMoves($board, $player);
		for ($i = 0; $i < count($moves); $i++) {
			$aValues = $this->alphaBetaHelper($moves[$i], !$player, -$beta, -$alpha);
			$value = - floatval($aValues[0]);
			if ($value > $beta) {
				$beta = $value;
	            $best = array();
	            array_push($best, $moves[$i]);
	            for ($j = 1; $j < count($aValues); $j++) {
	            		if ( $aValues[$j] != null ) {
	            			$best[] = $aValues[$j];
	            		}
	            }
			}
			if ($beta >= $alpha) {
				break;
			}
		}
		$ret = array();
		$ret[0] = floatval($beta);
	    for ($i=0; $i < count($best); $i++) {
	    	 	 $ret[] = $best[$i];
	    }
	    return $ret;
	}
	
	/**
	 * Starts the game with initial board position
	 * 
	 * @param array $boardPosition
	 */
	public function play(array $boardPosition)
	{
		$board = new Board($boardPosition);
		
		$status = null;
		if ($this->isWon($board, true)) {
			$status = Board::HUMAN;
		}elseif ($this->isWon($board, false)) {
			$status = Board::COM;
		}elseif ($this->isDrawn($board)) {
			$status = 0;
		}
		if (!is_null($status)) {
			return array('board'=>$boardPosition, 'status'=>$status);
		}
		
		$value = $this->alphaBeta($board, self::COM);
		$boardPosition = $value[1]->get();
		$board = new Board($boardPosition);
		
		if ($this->isWon($board, true)) {
			$status = Board::HUMAN;
		}elseif ($this->isWon($board, false)) {
			$status = Board::COM;
		}elseif ($this->isDrawn($board)) {
			$status = 0;
		}
		return array('board'=>$boardPosition, 'status'=>$status);
	}
	 
}