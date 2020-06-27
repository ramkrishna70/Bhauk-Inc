<?php
/**
 * enviroment element class: sound  
 * @author JLukasiewicz
 * @package Digital-Human
 */
class Sound extends EnviromentElement {

	public $sound = '';
	public $volume = 10;

	/**
	 * Emit sound and send notification to enviroment
	 * @param $sound
	 * @param $volume
	 */	
	public function emitSound($sound, $volume = 10) {
		$this->sound = $sound;
		$this->volume = $volume;
		$this->notifyEnviroment();
	}
}