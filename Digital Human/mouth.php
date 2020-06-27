<?php
/**
 * 
 * Body element: Mouth
 * @author JLukasiewicz
 * @package Digital-Human
 */
class Mouth extends Element  implements IObserver {
	
	public $sound = '';
	public $volume = 10;

	/**
	 * (non-PHPdoc)
	 * @see IObserver::catchEnviroment()
	 */
	public function catchEnviroment(EnviromentElement $element) {
		return;
	}
	
	/**
	 * Say something and notify enviroment
	 * @param string $what
	 * @param int $volume
	 */
	public function say($what = '', $volume=10) {
		$this->sound = $what;
		$this->volume = $volume;
		$this->notifyEnviroment();
	}
}
?>