<?php
require 'enviroment/IEnviroment.php';
require 'enviroment/EnviromentElement.php';
require 'enviroment/sound.php';
require 'enviroment/light.php';

/**
 * 
 * Class represents enviroment 
 * @author JLukasiewicz
 * @package Digital-Human
 */
class Enviroment implements IEnviroment {
	
	/**
	 * Observers array
	 * @var array
	 */
	private $observers = array();

	/**
	 * add observer
	 * @param IObserver $observer
	 */
	public function addObserver(IObserver $observer) {
		$this->observers[] = $observer;	
	}
	
	/**
	 * notify body elements when enviroment is changed
	 * @param EnviromentElement $element
	 */
	public function notifyBody(EnviromentElement $element) {
		foreach ($this->observers as $observer) {
			$observer->catchEnviroment($element);
		}
	}
	
	/**
	 * grab enviroment elements changes
	 * @param EnviromentElement $element
	 */
	public function catchEnvElementChange(EnviromentElement $element) {
		$this->notifyBody($element);
	}

	/**
	 * grab enviroment elements changes emitted by human
	 * @param EnviromentElement $element
	 */
	public function catchEnvHumanChange(Element $element) {
		if($element instanceof Mouth) {
			$sound = new Sound($this);
			$sound->emitSound($element->sound, $element->volume);			
		}

	}
}
?>