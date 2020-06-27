<?php
/**
 * 
 * Body elements observer methods signatures
 * @author JLukasiewicz
 * @package Digital-Human
 */
interface IObserver {
	/**
	 * React on enviroment changes 
	 * @param EnviromentElement $element
	 */
	public function catchEnviroment(EnviromentElement $element);
	
}