<?php
/**
 * enviroment element class: light  
 * @author JLukasiewicz
 * @package Digital-Human
 */
class Light extends EnviromentElement {

	public $volume = 5;

	/**
	 * 
	 * emit light to enviroment
	 * @param int $volume
	 */
	public function emitLight($volume = 5) {
		$this->volume = $volume;
		$this->notifyEnviroment();
	}
	
}