<?
session_start( );
/**
 @package		AnimatedCaptcha
 @author		J Watkins 		<krakjoe@krakjoe.info>
 @desc			OOP Interface to AnimCaptcha ( PHP4 )
 @static
 
**/
class AnimCaptcha
{
	var $frames ;
	var $times ;
	var $num ;
	var $pause ;
	var $ops ;
	var $gifs ;
	var $rand ;
	var $math ;
	
	function AnimCaptcha( $gifs, $pause )
	{
		if( !class_exists( 'GIFEncoder' ) and !include('GIFEncoder.class.php') )
			die( 'I require GIFEncoder to be loaded before operation' );	

		$this->pause = (int)$pause ;
		$this->gifs = $gifs ;
		$this->ops = array
		(
			'minus',
			'plus',
			'times'
		);
		$this->math = array
		(
			'-',
			'+',
			'*'
		);
		
		$this->num['rand1'] = rand( 1, 9 );
		$this->num['rand2'] = rand( 1, 9 );
		$this->num['op'] = rand( 0, count( $this->math ) - 1 );
		
		$this->BuildImage( );
	}
	function BuildImage( )
	{
		$this->frames[ ] = sprintf( '%s/solve.gif', $this->gifs ) ;
    	$this->times[ ]	 = 260;  
    	$this->frames[ ] = sprintf( '%s/%d.gif', $this->gifs, $this->num['rand1'] );
    	$this->times[ ]	 = $this->pause;
		$this->frames[ ] = sprintf( '%s/%s.gif', $this->gifs, $this->ops[ $this->num['op'] ] );
    	$this->times[ ]	 = $this->pause;
    	$this->frames[ ] = sprintf( '%s/%d.gif', $this->gifs, $this->num['rand2'] );
    	$this->times[ ]	 = $this->pause;
    	$this->frames[ ] = "frames/equals.gif" ;
    	$this->times [ ]  = 280;
    	foreach( $this->num as $index => $value ) $_SESSION[ $index ] = $this->num[ $value ];
	}
	function GetImage( )
	{	
		eval( sprintf( '$_SESSION["answer"] = (%d %s %d);', 
										$this->num['rand1'], 
										$this->math[ $this->num['op'] ], 
										$this->num['rand2']
		) );
		
		if( $_SESSION['answer'] < 0 ) 
			$this->AnimCaptcha( $this->gifs, $this->pause );
		
		$gif = new GIFEncoder( $this->frames, $this->times, 0, 2, 0, 0, 0, "url" );
		
		if( !headers_sent( ) )
		{
			header ( 'Content-type:image/gif' );
			echo $gif->GetAnimation ( );
		}
	}
}
$captcha = new AnimCaptcha( 'frames', 140 );
$captcha->GetImage( );
?>
