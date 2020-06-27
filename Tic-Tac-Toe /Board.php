<?php
/**
 * Represents the board position of the game (Tic Tac Toe)
 * 
 * @author Amin Saeedi, <amin.w3dev@gmail.com>
 * @version 1.0
 */
class Board implements Countable
{
	const HUMAN = 1;
	const BLANK = 0;
	const COM = -1;
	
	/**
	 * 
	 * @var array
	 */
	private $board;
	
	public function __construct(array $position = array())
	{
		$this->board = $position;
		if (empty($position)) {
			$this->reset();
		}
	}
	
	public function count()
	{
		return count($this->board);
	}
	
	/**
	 * Sets all of the board cells to 0
	 */
	public function reset()
	{
		for($i=0; $i < 9; $i++) {
			$this->board[$i] = self::BLANK;
		}
		return $this;
	}
	
	/**
	 * Gets the board array or just a value by its index
	 * 
	 * @param int|null : board index to be retrieved or null to retrieve the board array
	 */
	public function get()
	{
		if (func_num_args() == 1) {
			return $this->board[func_get_arg(0)];
		}
		return $this->board;
	}
	
	/**
	 * Sets the board index to a value
	 * 
	 * @param int $index
	 * @param int $value : -1, 0, 1
	 */
	public function set($index, $value)
	{
		$this->board[$index] = $value;
	}
	
	
	public function __toString()
	{
		$rt = '[';
		foreach ($this->board as $b){
			$rt .=  "$b, ";
		}
		$rt .= '] ';
		return $rt;
	}
}