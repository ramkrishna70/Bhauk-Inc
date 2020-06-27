<?php
require_once 'PHPUnit/Framework.php';
require_once 'Board.php';

class BoardTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Board
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Board(array(0, -1, 0, 1, 1, 0, 0, -1, 0));
    }

    public function testCount()
    {
        $this->assertEquals(9, count($this->object));
    }

    public function testReset()
    {
        $empty = array(0, 0, 0, 0, 0, 0, 0, 0, 0);
        $this->assertEquals($this->object->reset()->get(), $empty);
    }

    public function testGet()
    {
        $this->assertEquals($this->object->get(1), -1);
    }

    public function testSet()
    {
       $this->object->set(2, 1);
       $this->assertEquals($this->object->get(2), 1);
    }
}