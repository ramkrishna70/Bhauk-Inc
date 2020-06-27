<?php
require 'element.php';
require 'Iobserver.php';
require 'head.php';
require 'eye.php';
require 'ear.php';
require 'mouth.php';

/**
 * 
 * Example body implementation
 * @author Jacek Lukasiewicz
 * @package Digital-Human
 *
 */
class DefaultBody {
	/**
	 * @var Head
	 */
	public $Head;
	/**
	 * @var Eye
	 */
	public $Eye;
	/**
	 * @var Ear
	 */
	public $Ear;
	/**
	 * @var Mouth
	 */
	public $Mouth;	

	/**
	 * store custom body elements objects
	 * @var array
	 */
	private $customElements = array();
	
	/**
	 * 
	 * store enviroment object
	 * @var Enviroment
	 */
	private $enviroment;

	/**
	 * 
	 * set body elements and add enviroment observers
	 * @param Enviroment $enviroment
	 */
	public function __construct(Enviroment $enviroment) {
		
		$this->enviroment = $enviroment;
		
		$this->Head = new Head();
		$this->Eye = new Eye();
		$this->Ear = new Ear();
		$this->Mouth = new Mouth($enviroment);
		
		$enviroment->addObserver($this->Head);
		$enviroment->addObserver($this->Eye);
		$enviroment->addObserver($this->Ear);
		$enviroment->addObserver($this->Mouth);
		
	}
	
	/**
	 * 
	 * Add new body element
	 * @param Element $element new body element object
	 */
	public function addBodyElement(Element $element) {
		$this->customElements[get_class($element)] = $element;
		$this->enviroment->addObserver($element);
	}
	
	public function __get($name) {
		return $this->customElements[$name];
	}
}
?>