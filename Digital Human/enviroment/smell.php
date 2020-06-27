<?php
/**
 * enviroment element class: smell  
 * @author JLukasiewicz
 * @package Digital-Human
 */
class Smell extends EnviromentElement {

	public $smellKind = '';
	public $volume = 10;

	/**
	 * Emit smell and send notification to enviroment
	 * @param string $smellKind
	 * @param int $volume
	 */	
	public function emitSmell($smellKind, $volume = 10) {
		$this->smellKind = $smellKind;
		$this->volume = $volume;
		$this->notifyEnviroment();
	}
}