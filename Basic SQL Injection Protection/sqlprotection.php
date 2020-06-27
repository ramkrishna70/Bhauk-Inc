<?
// Copyright 2020 Ram Krishna
class sqlinj{
	private $gerivalue;
	private $islet;
	public $liste=array('declare','char','set','cast','convert','drop','exec','meta','script','select','truncate','insert','delete','union','update','create','where','join','information_schema','table_schema','into');
	private $specialfind=array('\'','"','-','*','=');
	private $specialreplace=array('&#39;','&#34;','&#45;','&#42;','&#61;');
	public function clean($find){
		return str_replace($this->specialfind,$this->specialreplace,$find);
	}
	public function Start($data,$there='normal'){
		if($tur=='normal'){
			return self::normal(self::clean($data));
		}elseif($there=='all'){
			return self::allqueries($data);
		}else{
			return self::req($there,$data);
		}
	}
	private function normal($value){
		foreach($this->liste as $find){
			$value=str_replace($find,'\\'.$find.'\\',$value);
			
		}
		return $value;
	}
	//allqueries tumsorgular | yapilacak > todo
	private function allqueries($todo){
			switch ($yapilacak){
			case 'post':
			$this->islet=array('POST');
			break;
			case 'get':
			$this->islet=array('GET');
			break;
			case 'request':
			$this->islet=array('REQUEST');
			break;
			case 'aio':
			$this->islet=array('POST','GET','REQUEST');
			break;
		}	
		foreach($this->islet as $operation){
		eval('foreach($_'.$operation.' as $ad=>$value){
			$_'.$operation.'[$ad]=self::clean($value);
			foreach($this->liste as $find){
			$_'.$operation.'[$ad]=str_replace($find,"\\\".$find."\\\",$_'.$operation.'[$ad]);
			}
		}
		
			
return $_'.$operation.';
');
		}
	}
	private function req($value,$method){
		switch ($method){
			case 'post':
			$this->islet=self::clean($_POST[$value]);
			break;
			case 'get':
			$this->islet=self::clean($_GET[$value]);
			break;
			case 'request':
			$this->islet=self::clean($_REQUEST[$value]);
			break;
		}	
		foreach($this->liste as $find){
			$this->islet=str_replace($find,'\\'.$find.'\\',$this->islet);
			
		}
		return $this->islet;	
	}
	public function adding($added){
		$this->liste[]=$added;
	}
}

?>
