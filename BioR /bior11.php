<?php

/* 

BioR 1.1

Author: Iztok Strzinar / master@pomarancha.com
Version: 1.1; Feb 2005
Compatibility: For full functionality (diagram creation), this calss needs gdlibrary 
(http://www.boutell.com/gd/, http://si2.php.net/gd) installed!


************* WHAT'S NEW *************

Version 1.1 contains a custom gregoriantojd() function so there is no need for calendar extensions (http://si.php.net/calendar) to be installed or enabled in php. That’s all. 

************* DESCRIPTION *************

This is a simple class for calculating a personal biorhythm values. It provides data for three sin curves (physical, emotional, and intellectual) in range from -100 to 100. With gdLibrary installed it also provides means to build a simple yet neat diagram.

The (most common) theory of biorhythm states, that our daily lives are significantly affected by simple rhythmic cycles. When we are born, three cycles begin from zero point and then oscillate between -100 and 100 till we die. The physical period is 23 days long, emotional 28, and intellectual has period of 33 days. Beware of days when any curve crosses zero especially if two or three do this simultaneously. More on: http://skepdic.com/biorhyth.html.


************* USAGE *************

$test = new BioR ('1971-05-15', '2004-08-06'); // For new object/instance.  Takes two arguments; date of birth and date for which you want biorhythm properties to be calculated. Dates must be in YYY-MM-DD format. If no second parameter is defined, the date for biorhythm is server's current date.

$test->DrawBior('image_png'); // Method builds diagram with sin curves and some additional data on it. Then puts it on disk in specified file. Use relative path. If no argument is passed, the default path is as defined in FILE_FOR_DIAG constant.

$test->GetDiagramImageTag(); // Get html image tag for diagram.
$test->GetDiagramFileHandler(); // Get file handler (relative path)

$array = $test->GetAllPercentages(); Get percentages and trends for given day. Returns an array. Use print_r($array) to see it's structure. Trend values are 0 - for descending and 1 for ascending. 

************* EXAMPLE *************

include ("bior11.php"); // file with class
$my_bior = new BioR ('1971-05-15');// new instance
$my_bior->DrawBior('my_bior.png'); // build diagram image and put it to disk
echo $my_bior->GetDiagramImageTag(); // show diagram in browser


*/

// customization

define('PHY_PERIOD', 23);
define('EMO_PERIOD', 28);
define('INT_PERIOD', 33);

define('DAYS_TO_SHOW', 30); // number of days on diagram
define('DIAGRAM_WIDTH', 400);
define('DIAGRAM_HEIGHT',250);

define('FILE_FOR_DIAG', 'slika2.png'); // file for diagram graphyc; must be .png file


// define text for diagram
define('CURVE_TEXT_PHY', 'FIZ');
define('CURVE_TEXT_EMO', 'EMO');
define('CURVE_TEXT_INT', 'INT');

// define colors for diargam / RGB format
define('BACK_C_R', 219);
define('BACK_C_G', 219);
define('BACK_C_B', 219);

define('GRID_C_R', 0);
define('GRID_C_G', 0);
define('GRID_C_B', 0);

define('PHY_C_R', 207);
define('PHY_C_G', 0);
define('PHY_C_B', 0);

define('EMO_C_R', 0);
define('EMO_C_G', 107);
define('EMO_C_B', 0);

define('INT_C_R', 0);
define('INT_C_G', 0);
define('INT_C_B', 112);

// end customization; do not edit bellow this line




// BEGIN CLASS
class BioR {

	
	// constructor
	function BioR ($birth_date, $date_for_bior = -1) {
		
		if ($date_for_bior == -1) 
			$date_for_bior = date('Y-m-d', time());
		
		// date for bior - timestamp
		$this->date_for_bior_epoch = strtotime($date_for_bior);
		
		// dateparts for birth_date
		$birth_date_parts = explode('-', $birth_date);
		list ($this->birth_date_y, $this->birth_date_m, $this->birth_date_d) = $birth_date_parts;
		
		// dateparts for date_for_bior
		$date_for_bior_parts = explode('-', $date_for_bior);
		list ($this->date_for_bior_y, $this->date_for_bior_m, $this->date_for_bior_d) = $date_for_bior_parts;
		
	}
	
	// LOCAL
	// gregorian to julian days conversion
	function grgtojd ($month,$day,$year) {
		if ($month > 2) {
			$month = $month - 3;
		} else {
			$month = $month + 9;
			$year = $year - 1;
		}
		$c = floor($year / 100);
		$ya = $year - (100 * $c);
		$j = floor((146097 * $c) / 4);
		$j += floor((1461 * $ya)/4);
		$j += floor(((153 * $month) + 2) / 5);
		$j += $day + 1721119;
		return $j;
	}

	// LOCAL
	// get days from birth
	function GetDaysFromBirth (){
		
		// transform date to number of Julian days and substract two dates to get "num of days alive"
		$this->days_from_birth = abs($this->grgtojd($this->birth_date_m, $this->birth_date_d, $this->birth_date_y) 
		- $this->grgtojd($this->date_for_bior_m, $this->date_for_bior_d, $this->date_for_bior_y));	
		
		return $this->days_from_birth; 
	}
	
	// PUBLIC
	// build diagram and put it to disk as png file
	function DrawBior ($file_for_diag = FILE_FOR_DIAG) {
		
		// set handler
		$this->file_for_diag = $file_for_diag;
		
		// create image
		$this->image = imagecreate (DIAGRAM_WIDTH ,DIAGRAM_HEIGHT);
		
		// allocate colors for gdlib
		$color_grid = imagecolorallocate ($this->image, GRID_C_R, GRID_C_G, GRID_C_B);
		$color_phy = imagecolorallocate ($this->image, PHY_C_R, PHY_C_G, PHY_C_B);
		$color_emo = imagecolorallocate ($this->image, EMO_C_R, EMO_C_G, EMO_C_B);
		$color_int = imagecolorallocate ($this->image, INT_C_R, INT_C_G, INT_C_B);
		$color_back = imagecolorallocate ($this->image, BACK_C_R, BACK_C_G, BACK_C_B);
		
		// draw background
		imagefilledrectangle ($this->image, 0, 0, DIAGRAM_WIDTH - 1, DIAGRAM_HEIGHT - 1, $color_back);
		
		
		$nrSecondsPerDay = 60 * 60 * 24;
		$diagramDate = $this->date_for_bior_epoch - (DAYS_TO_SHOW / 2 * $nrSecondsPerDay) + $nrSecondsPerDay; 
		
		
		// draw days and separators
		for ($i = 1; $i < DAYS_TO_SHOW; $i++) {
			$thisDate = getdate($diagramDate);
			$xCoord = (DIAGRAM_WIDTH / DAYS_TO_SHOW) * $i;
			
			 imageline($this->image, $xCoord, DIAGRAM_HEIGHT - 25, $xCoord, DIAGRAM_HEIGHT - 20, $color_grid);
			 imagestring($this->image, 1, $xCoord - 5, DIAGRAM_HEIGHT - 16, $thisDate[ "mday"], $color_grid);
			 
			 $diagramDate += $nrSecondsPerDay;
		}
		
		// draw some diagram elements
		imageline($this->image, 0, (DIAGRAM_HEIGHT - 20) / 2, DIAGRAM_WIDTH, (DIAGRAM_HEIGHT - 20) / 2, $color_grid);
		imageline($this->image, DIAGRAM_WIDTH / 2, 0, DIAGRAM_WIDTH / 2, DIAGRAM_HEIGHT - 20, $color_grid);
		imagestring($this->image, 2, DIAGRAM_WIDTH - 30, 5, '100%' , $color_grid); 
		imagestring($this->image, 2, DIAGRAM_WIDTH - 35, DIAGRAM_HEIGHT - 45, '-100%' , $color_grid); 
		imagestring($this->image, 2, 5, DIAGRAM_HEIGHT - 45, date('m-Y', $this->date_for_bior_epoch) , $color_grid); 
		imagestring($this->image, 2, 10, 10, '<-- '.$this->birth_date_d.'/'.$this->birth_date_m.'/'.$this->birth_date_y, $color_grid);
		imagestring($this->image, 4, 10, 30, CURVE_TEXT_PHY , $color_phy); 
		imagestring($this->image, 4, 10, 45, CURVE_TEXT_EMO , $color_emo);
		imagestring($this->image, 4, 10, 60, CURVE_TEXT_INT , $color_int);
		
		// call functions for curve drawing 
		
		$this->DrawCurve ($this->GetDaysFromBirth(), PHY_PERIOD, $color_phy);
		$this->DrawCurve ($this->GetDaysFromBirth(), EMO_PERIOD, $color_emo);
		$this->DrawCurve ($this->GetDaysFromBirth(), INT_PERIOD, $color_int);
		
		// write diagram image to disk
		imagepng ($this->image, $this->file_for_diag);
		
		
	}
	
	// LOCAL
	// draw sin curve 
	function DrawCurve ($days_from_birth, $period, $color) {
		
		$centerDay = $this->GetDaysFromBirth() - (DAYS_TO_SHOW / 2);
		$plotScale = (DIAGRAM_HEIGHT - 25) / 2;
		$plotCenter = (DIAGRAM_HEIGHT - 25) / 2;
		
		for($x = 0; $x <= DAYS_TO_SHOW; $x++) {
			$phase = (($centerDay + $x) % $period) / $period * 2 * pi();
			$y = 1 - sin($phase) * (float)$plotScale + (float)$plotCenter;
		
			if($x > 0) {
				imageline($this->image, $oldX, $oldY, $x * DIAGRAM_WIDTH / DAYS_TO_SHOW, $y, $color);
			}
			$oldX = $x * DIAGRAM_WIDTH / DAYS_TO_SHOW;
			$oldY = $y; 
		}
	}
	
	//LOCAL
	// Get pecentage for given day for given curve
	function GetPercentageForToday ($period) {
		
		// get y value in degrees for given day for given period	
		$y = ceil(sin(deg2rad($this->GetDaysFromBirth() * (360 / $period))) * 100);
		$y2 = ceil(sin(deg2rad(($this->GetDaysFromBirth() + 1) * (360 / $period))) * 100);
		
		// find out trend
		if ($y > $y2) 
			$this->trend = 0;
		else
			$this->trend = 1;
		
		return array('percent' => $y, 'trend' => $this->trend);
	}
	
	// LOCAL
	// Get average percentage and global trend
	function GetAverage () {
		
		$phy = $this->GetPercentageForToday(PHY_PERIOD);
		$emo = $this->GetPercentageForToday(EMO_PERIOD);
		$int = $this->GetPercentageForToday(INT_PERIOD);
		
		if ($phy['trend'] + $emo['trend'] + $int['trend'] >= 2)
			$avg_trend = 1;
		else
			$avg_trend = 0;
		
		return array ('percent' => ceil(($phy['percent'] + $emo['percent'] + $int['percent']) / 3),
		'trend' => $avg_trend);
		
	}
	
	// PUBLIC
	// get all percentages and trends for today in array
	function GetAllPercentages () {
		
		return array("phy"=>$this->GetPercentageForToday(PHY_PERIOD),
		"emo"=>$this->GetPercentageForToday(EMO_PERIOD), 
		"int"=>$this->GetPercentageForToday(INT_PERIOD),
		"avg"=>$this->GetAverage()
		);
		
	}
	
	// PUBLIC
	//get relative file handler for diagram file
	function GetDiagramFileHandler () {
		return $this->file_for_diag;
	}
	
	// PUBLIC
	//get html image tag for diagram file
	function GetDiagramImageTag () {
		
		return "<img src=\"$this->file_for_diag\">";
	}
	
}
// END CLASS


?>


