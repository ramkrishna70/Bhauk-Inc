<?php
/**
 * 
 * Body element: Head
 * @author JLukasiewicz
 * @package Digital-Human
 */
class Head extends Element implements IObserver {

	/**
	 * 
	 * current head position
	 * @var unknown_type
	 */
	private $position = array('H' => 0, 'V' => 0);
	
	/**
	 * Move head vertically
	 * @param int $upDown
	 */
	public function moveVertical($upDown) {
		$upDown = (int)$upDown;
		if($this->position['V'] + $upDown > 1 || $this->position['V'] + $upDown < -1) {
			return false;	
		} 
		$this->position['V'] += $upDown;
		return true;
	}
	
	/**
	 * Move head horizontally
	 * @param int $upDown
	 */	
	public function moveHorizontal($leftRight) {
		$leftRight = (int)$leftRight;
	
		if($this->position['H'] + $leftRight > 1 || $this->position['H'] + $leftRight < -1) {
			return false;	
		} 
		$this->position['H'] += $leftRight;
		return true;
	}
	
	/**
	 * 
	 * get head position
	 */
	public function getPosition() {
		return $this->position;	
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IObserver::catchEnviroment()
	 */
	public function catchEnviroment(EnviromentElement $element) {
		if($element instanceof Sound) {
			echo "<br />HEAD: Sound received: ".$element->sound.". Volume: ".$element->volume;
			if($element->volume > 6) {
				echo "<br />HEAD: is to lound. PAIN";
			}
		}
	}

}
?>