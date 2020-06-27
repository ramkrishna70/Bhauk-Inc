<?php
/**
 * 
 * abstract class to extend by enviroment elements classes i.e. light, sound ...
 * @author JLukasiewicz
 * @package Digital-Human
 *
 */
abstract class EnviromentElement {

	/**
	 * Observers array
	 * @var array
	 */	
	protected $observers = array();
	
	/**
	 * Add observer
	 * @param $enviroment
	 */
	protected function addObserver(Enviroment $enviroment) {
		$this->observers[] = $enviroment;	
	}
	
	/**
	 * Notify enviroment
	 */
	protected function notifyEnviroment() {
		foreach ($this->observers as $observer) {
			$observer->catchEnvElementChange($this);
		}		
	}

	/**
	 * Constructor
	 * @param Enviroment $enviroment
	 */
	public function __construct(Enviroment $enviroment) {
		$this->addObserver($enviroment);	
	}
	
}
?>