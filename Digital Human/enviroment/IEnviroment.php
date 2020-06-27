<?php
/**
 * 
 * Enviroment method signatures
 * @author JLukasiewicz
 * @package Digital-Human
 *
 */
interface IEnviroment {
	
	/**
	 * add observer
	 * @param $observer
	 */
	function addObserver(IObserver $observer);
	
	/**
	 * 
	 * notify body
	 * @param EnviromentElement $element
	 */
	public function notifyBody(EnviromentElement $element);
	
	/**
	 * 
	 * catch enviroment element change i.e. sound, light...
	 * @param EnviromentElement $element
	 */
	public function catchEnvElementChange(EnviromentElement $element);

	/**
	 * 
	 * catch enviroment change emitted by human
	 * @param Element $element
	 */
	public function catchEnvHumanChange(Element $element);
}
