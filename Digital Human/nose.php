<?php
/**
 * 
 * Body element: Nose
 * @author JLukasiewicz
 * @package Digital-Human
 */
class Nose extends Element implements IObserver {

	/**
	 * (non-PHPdoc)
	 * @see IObserver::catchEnviroment()
	 */
	public function catchEnviroment(EnviromentElement $element) {
		if($element instanceof Smell) {
			echo '<br />NOSE: Smell received: "'.$element->smellKind.'"'.'Volume: '.$element->volume;
		}
	}
}
?>