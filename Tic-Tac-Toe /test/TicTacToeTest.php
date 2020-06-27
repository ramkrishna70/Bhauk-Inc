<?php
require_once 'PHPUnit/Framework.php';
require_once 'Board.php';
require_once 'TicTacToe.php';

class TicTacToeTest extends PHPUnit_Framework_TestCase {
	
	protected $ttt;
	
	protected function setUp() {
		$this->ttt = new TicTacToe();
	}
	
	public function testAlphaBeta()
	{
		$value = $this->ttt->alphaBeta(new Board(), GameSearch::COM);
		$boardPosition = $value[1]->get();
		$this->assertEquals(-1, $boardPosition[4]);
	}
	
}

