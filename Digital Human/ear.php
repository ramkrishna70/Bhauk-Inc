<?php
/**
 * 
 * Body element: Ear
 * @author JLukasiewicz
 * @package Digital-Human
 */
class Ear extends Element implements IObserver {

	/**
	 * (non-PHPdoc)
	 * @see IObserver::catchEnviroment()
	 */
	public function catchEnviroment(EnviromentElement $element) {
		if($element instanceof Sound) {
			echo "<br />EAR: Sound received: ".$element->sound.". Volume: ".$element->volume;
		}
	}
}
?>