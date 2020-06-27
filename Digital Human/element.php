<?php
/**
 * Body elements base class 
 * @author JLukasiewicz
 * @package Digital-Human
 */
abstract class Element {
	/**
	 * observers array
	 * @var array
	 */
	protected $observers = array();
	
	/**
	 * Adds an observer object
	 * @param Enviroment $enviroment
	 */
	protected function addObserver(Enviroment $enviroment) {
		$this->observers[] = $enviroment;	
	}
	
	/**
	 * On change Notify enviroment 
	 */
	protected function notifyEnviroment() {
		foreach ($this->observers as $observer) {
			$observer->catchEnvHumanChange($this);
		}		
	}
	
	/**
	 * Constructor
	 * @param Enviroment $enviroment
	 */
	public function __construct(Enviroment $enviroment = null) {
		if(!empty($enviroment)) {
			$this->addObserver($enviroment);
		}	
	}
	
}