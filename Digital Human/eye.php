<?php
/**
 * 
 * Body element: eye(s)
 * @author JLukasiewicz
 * @package Digital-Human
 */
class Eye extends Element  implements IObserver {

	/**
	 * is open 
	 * @var boolean
	 */
	private $open = true;
	
	/**
	 * (non-PHPdoc)
	 * @see IObserver::catchEnviroment()
	 */
	public function catchEnviroment(EnviromentElement $element) {
		if($element instanceof Light) {
			if($this->open == true) {
				echo "<br />EYE: LIGHT received:  Volume: ".$element->volume;
			}
		}
	}
	/**
	 * open eye 
	 */	
	public function open() {
		$this->open = true;
		return true;	
	}
	
	/**
	 * close eye 
	 */
	public function close() {
		$this->open = false;
		return true;
	}
	
	/**
	 * return open eye status
	 */
	public function isOpen() {
		return $this->open;
	}
}