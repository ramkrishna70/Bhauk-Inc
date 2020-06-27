<?PHP
/**
* @desc as_snipercaptcha.php :
* Another CAPTCHA engine for anti-BOT site protecting.
* It doesn't use any graphic libraries (like GDI) for generating image "on the fly".
* Instead it uses Javascript and DHTML to show "aiming" grid, where user must click on some cells.
* @Author Alexander Selifonov <as-works [at] narod.ru> <alex [at] selifan.ru>
* @link http://www.selifan.ru/phpfiles.php
* @Copyright Alexander Selifonov 2008 , under GPL license
* @Version 1.03.007
* Last modified: 10.12.2008
*/
if(!defined('IMGPATH')) define('IMGPATH','');
class CSniperCaptcha {
  var $serverscript = '';
  var $codelen = 4; # how many user clicks in test sequence
  var $idlist = ''; # string with all random generated id's for sniper grid
  var $x_len = 0;   # grid dimensions
  var $y_len = 0;
  var $cellsize;
  var $var_curpos = 0; # entered 'numbers' counter
  var $prevcode = -1;
  var $startx = 0;
  var $starty = 0;
  var $accumulated = '';
  var $useranswer = '';
  var $submitbutton_id = '';
  var $jscodepassed = '';
  var $jscodefailed = '';
  var $b_buffered = false;
  var $reinit_action = false;
  /**
  * @desc CSniperCaptcha constructor
  * @param int/string - pass code length (how many times user should point & click on the grid
  * @param string - ID of 'submit' form button. It'll become enabled whrn all code "entered"
  * @param string - javascript function name, that will be fired instantly on last user click,
  *        if code correct (CAPTCHA test passed)
  * @param string - this function will call if test failed
  * @param  mixed $reinit activates captcha re-init feature
  */
  function CSniperCaptcha($codelen=4, $submitid='', $jscode_success='',$jscode_fail='',$reinit=false) {
    $this->codelen = $codelen;
    $this->submitbutton_id = $submitid;
    $this->serverscript = $_SERVER['PHP_SELF'];
    $this->jscodepassed = $jscode_success;
    $this->jscodefailed = $jscode_fail;
    $this->reinit_action = $reinit;
    if(!isset($_SESSION)) @session_start();
  }
  /**
  * @desc all output will be redirected to returned var, no immediate echo
  */
  function SetBufferedOutput($buffered=true) { $this->b_buffered = $buffered; }
  /**
  * @desc echoes STYLE ref to a CSS file with styles needed by CAPTCHA, and ref to the common func. js file.
  */
  function DrawRefs($csspath='',$jspath='') {
    $ret = '';
    if($csspath!==false) $ret .="<link rel=\"stylesheet\" type=\"text/css\" href=\"{$csspath}captcha_styles.css\" >\n";
    if($jspath!==false) $ret .="<SCRIPT SRC=\"{$jspath}as_jsfunclib.js\" type=\"text/javascript\"></SCRIPT>\n";
    if(isset($this) && $this->b_buffered) return $ret;
    echo $ret;
  }
  # SetServerScript: sets backend server script URI
  function SetServerScript($uri) { $this->serverscript = $uri; }

  function Draw($width=0, $height=0, $cellsize=0, $tovar=0) {
    global $sitestrings;
    $gen = array();
    $jsdigvar = '0';
    $ret = '';
    $x_len = ($width)? $width: intval(mt_rand(4,10));
    $y_len = ($height)? $height : intval(mt_rand(3,6));
    $this->cellsize = array(0,0);
    if(empty($cellsize) || $cellsize<=8) {
      $this->cellsize[1] = round(mt_rand(10,20));
      $this->cellsize[0] = round(mt_rand($this->cellsize[1],$this->cellsize[1]+10));
    }
    else $this->cellsize = array($cellsize,$cellsize);
    $ret.="<table border='0' cellspacing='1' cellpadding='0' id='captchaTable'>";
    $this->idlist = 'xxx';
    $init_prompt = isset($sitestrings['captcha_init'])? $sitestrings['captcha_init'] : '';
    for($y=1;$y<=$y_len;$y++) {
      $ret.="<tr>";
      for($x=1;$x<=$x_len;$x++) {
        $cellid = chr(intval(mt_rand(97,122))).chr(intval(mt_rand(97,122))).chr(intval(mt_rand(97,122))).$y.$x;
        $this->idlist .= '|'.$cellid;
        $ret.= "<td id='$cellid' onClick=\"return CaptchOneShot(this)\" class=\"captcha_cell\" style=\"cursor:hand\"
        onMouseOver='this.className=\"captcha_cellmo\"' onMouseOut='this.className=\"captcha_cell\"'><img src='".IMGPATH."empty.gif' width={$this->cellsize[0]} height={$this->cellsize[1]}/></td>";
      }
      # if needed, draw 'initialize' button, to start captcha from very beginning
      if($y==1 && ($this->reinit_action)) $ret .="<td rowspan=\"$y_len\" valign=top><a href=\"javascript://\" onclick=\"CaptchaReinit(true)\"><img src=\"".IMGPATH."captcha_init.gif\" width=16 height=16 border=0 />$init_prompt</a></td>";
      $ret.= "</tr>";
    }
    $ret .= "<tr><td colspan='$x_len' class='captcha_cell' style='text-align:left'><img id='caprogress' src='".IMGPATH."cap_progress.png' width='1' height='8' /></td></tr>";
    $ret.= "</table><img id='cap_sight' src='".IMGPATH."captchasight.png' width=7 height=7 style='z-index:10; position:absolute; display:none;' />";
    $_SESSION['captcha_idlist'] = $this->idlist;
    $_SESSION['captcha_lenx'] = $x_len;
    $_SESSION['captcha_leny'] = $y_len;
    $_SESSION['captcha_cellsize'] = $this->cellsize;
    $_SESSION['captcha_codelen'] = $this->codelen;
    $_SESSION['captcha_accumulated'] = $_SESSION['captcha_useranswer'] = 'xxx';
    $this->var_curpos = $_SESSION['captcha_curpos'] = 0;
    $this->prevcode = $_SESSION['captcha_prevcode'] =  -1;
    if(($tovar) || ($this->b_buffered)) return $ret;
    echo $ret;
  } # Draw() end
  /**
  * @desc DrawJsCode() prints all needed javascript code for CAPTCHA processing
  * @param integer/boolean if non-empty, CAPTCHA client-server exchange sequence will start immediately
  */
  function DrawJsCode($autostart=1) {
    $ret ="
<script language=\"JavaScript\" type=\"text/javascript\">
<!--
var busyxml = false;
var handlerurl = \"{$this->serverscript}\";
var captable = false;
var finished = 0;
var as_resize_handled=false;
var as_captcha_tmer = null;
function CaptchaReinit(evt) {
  cap_imgobj=0;".(!empty($this->submitbutton_id)?"asGetObj('{$this->submitbutton_id}').disabled=true;":'')."
  SendCaptchaChecking(0);
  ".(is_string($this->reinit_action)? "if(evt){ {$this->reinit_action} }":'')."
}
function CaptchaResizing() {
  if(finished>=500) return;
  if(as_captcha_tmer!=null) try { clearTimeout(as_captcha_tmer); as_captcha_tmer=null;} catch(e){};
  as_captcha_tmer=setTimeout(\"CaptchaResized()\",300);
}
var rz_cnt = 0;
function CaptchaResized() {
  as_captcha_tmer=null;
  CaptchaReinit();
}
function SendCaptchaChecking(obj) {
//  alert(typeof(obj));
  if(busyxml) return false;
  if(typeof(obj)!=\"string\") {
    captable = asGetObj('captchaTable');
    txy = getPosition(captable);
    params = \"sniper_capaction=coord&tx=\"+txy[0]+\"&ty=\"+txy[1];
    if(!as_resize_handled) {
      window.onresize = CaptchaResizing;
      as_resize_handled=true;
    }
  }
  else {
    params = \"sniper_capaction=donext&oid=\"+obj;
  }
  var xmlreq = NewXMLHttpRequest();
  if(!xmlreq) { return false; }
  xmlbusy=true;
  xmlreq.onreadystatechange= function() {
    if (xmlreq.readyState == 4) {
      var sresponse= xmlreq.responseText;
      delete xmlreq;
      xmlbusy = false;
      var spl = sresponse.split('|');
      if(spl.length<2) { alert(\"undefined server response: \"+sresponse); return false; }
      finished = spl[0]-0;
      if(spl[0]=='err') {
        alert('Captcha error!'); finished=200; return false;
      }
      var imgprogress= asGetObj('caprogress');
      imgprogress.style.width = spl[0]+'%';
      if(spl[0]=='100') {
        finished = (spl[1]==\"yes\")?500:0;
        MoveSight(-1,-1,0);\n";

  if($this->submitbutton_id !='') $ret.="        if(spl[1]==\"yes\") asGetObj(\"{$this->submitbutton_id}\").disabled=false;\n";
  if(!empty($this->jscodepassed)) $ret.="        if(spl[1]==\"yes\") { {$this->jscodepassed}  }\n";
  if(!empty($this->jscodefailed)) $ret.="        if(spl[1]==\"no\")  { {$this->jscodefailed}  }\n";
   $ret .="
      }
      else {
         nextx = spl[1]-0;
         nexty = spl[2]-0;
         MoveSight(nextx,nexty,1);
      }
    }
  }
  xmlreq.open(\"POST\",handlerurl,true);
  xmlreq.setRequestHeader(\"Content-Type\",postcont);
  xmlreq.send(params);
  return false;
}
function getPosition(oElement) {
  var ret = [0,0];
  while( oElement != null ) {
    ret[0] +=oElement.offsetLeft;
    ret[1] +=oElement.offsetTop;
    oElement = oElement.offsetParent;
  }
  return ret;
}
var cap_imgobj = 0;
var htimer = false;
var imgOpacity = -1;
var capimg_nextpos = [];
function MoveSight(to_x,to_y, slowly) {
  if(cap_imgobj==0) {
    cap_imgobj = asGetObj('cap_sight');
    cap_imgobj.style.display='';
  }
  if(to_x<0) cap_imgobj.style.display='none'; // clicking finish
  else {
    if(finished>0) {
      capimg_nextpos = [to_x,to_y];
      cap_imgobj.capacity = imgOpacity = 100;
      htimer = setInterval(\"CapFadeOutEvt()\",50);
    }
    else {
      cap_imgobj.style.top = to_y+\"px\";
      cap_imgobj.style.left = to_x+\"px\";
    }
  }
}
function CaptchOneShot(obj) {
  if(finished>=100) return false;
  var tagid = obj.getAttribute(\"id\");
  SendCaptchaChecking(tagid);
}

function CapFadeOutEvt() {
  cap_imgobj.opacity = imgOpacity = imgOpacity-20;
  if (document.all) cap_imgobj.style.filter = 'alpha(opacity=' + imgOpacity+')';
  if(imgOpacity<=10) {
    clearTimeout(htimer);
    cap_imgobj.style.left = capimg_nextpos[0]+'px';
    cap_imgobj.style.top = capimg_nextpos[1]+'px';
//    alert(cap_imgobj+' style.left= '+typeof(cap_imgobj.style.left)+' '+cap_imgobj.style.left+' '+cap_imgobj.style.top);
    htimer = setInterval(\"CapFadeInEvt()\",50);
  }
}

function CapFadeInEvt() {
  cap_imgobj.opacity = imgOpacity = imgOpacity+20;
  if (document.all) cap_imgobj.style.filter = 'alpha(opacity=' + imgOpacity+')';
  if(imgOpacity>=100) {
    clearTimeout(htimer);
  }
}
function setOpacity (imgid, step, delay) {
  if (document.all) img.style.filter = 'alpha(opacity = ' + img.opacity + ')';
}";
    if($autostart) $ret .="\nSendCaptchaChecking();\n";

    $ret .="//-->\n</script>\n";
    if($this->b_buffered) return $ret;
    echo $ret;
  } # DrawJsCode end

  /**
  * @desc handle AJAX call from client. Generates and sends next sight position
  */
  function HandleClientRequest() {
    $ret = '100';
    $this->idlist = isset($_SESSION['captcha_idlist'])? $_SESSION['captcha_idlist']: '';
    $this->x_len = isset($_SESSION['captcha_lenx'])? $_SESSION['captcha_lenx']: 0;
    $this->y_len = isset($_SESSION['captcha_leny'])? $_SESSION['captcha_leny']: 0;
    $this->codelen = isset($_SESSION['captcha_codelen'])? $_SESSION['captcha_codelen']: 0;
    $this->var_curpos = isset($_SESSION['captcha_curpos'])? $_SESSION['captcha_curpos']: 0;
    $this->prevcode = isset($_SESSION['captcha_prevcode'])? $_SESSION['captcha_prevcode']: -1;
    $this->startx = isset($_SESSION['captcha_startx'])?$_SESSION['captcha_startx']:0;
    $this->starty = isset($_SESSION['captcha_starty'])?$_SESSION['captcha_starty']:0;
    $this->cellsize = isset($_SESSION['captcha_cellsize'])?$_SESSION['captcha_cellsize']: array(10,10);
    $this->accumulated = isset($_SESSION['captcha_accumulated'])?$_SESSION['captcha_accumulated']:'xxx';
    $this->useranswer = isset($_SESSION['captcha_useranswer'])?$_SESSION['captcha_useranswer']:'xxx';

    if(empty($this->idlist) || empty($this->x_len) || empty($this->y_len)) { echo 'err'; exit; } # Wrong call. May be no session support

    if($_POST['sniper_capaction']==='coord') {# initial coords passed   tx=NNN ty=MMM
      $_SESSION['captcha_startx'] = $this->startx = isset($_POST['tx'])?$_POST['tx']:0;
      $_SESSION['captcha_starty'] = $this->starty = isset($_POST['ty'])?$_POST['ty']:0;
      $this->var_curpos = $_SESSION['captcha_curpos'] = 0;
      $this->accumulated = $_SESSION['captcha_accumulated']=$this->useranswer = $_SESSION['captcha_useranswer']='xxx';
    }
    if(!empty($_POST['oid'])) {
      $_SESSION['captcha_useranswer'] = $this->useranswer = $this->useranswer.'|'.$_POST['oid'];
    }
    $percent = intval(100*$this->var_curpos/$this->codelen); # progress bar width, NN%
    $ret = '';
    $this->var_curpos++;
    if($percent<100) {
      $nextcode = $this->prevcode;
      while($nextcode == $this->prevcode) $nextcode = intval(mt_rand(0,($this->x_len*$this->y_len)-1));
      $this->prevcode = $nextcode;
      $idarr = explode('|',$this->idlist);
      $this->accumulated .= '|'.$idarr[$nextcode+1];
      $row_y = floor($nextcode/$this->x_len);
      $row_x = fmod($nextcode,$this->x_len);
      $next_posx = 2 + $this->startx+($row_x*($this->cellsize[0]+3));
      $next_posy = 2 + $this->starty+($row_y*($this->cellsize[1]+3));
      $_SESSION['captcha_curpos'] = $this->var_curpos;
      $_SESSION['captcha_prevcode'] = $this->prevcode;
      $_SESSION['captcha_accumulated'] = $this->accumulated;
      $ret = "$percent|$next_posx|$next_posy";
    }
    else $ret = "$percent|".($this->useranswer===$this->accumulated ? 'yes':'no');
    echo $ret; #AJAX server response
    exit;

  } # HandleClientRequest() end

  /**
  * @desc CheckPassed compares code passed from client POST with SESSION saved.
  * @Return true if codes match.
  */
  function CheckPassed() {
    $this->accumulated = isset($_SESSION['captcha_accumulated'])?$_SESSION['captcha_accumulated']:'';
    $this->useranswer = isset($_SESSION['captcha_useranswer'])?$_SESSION['captcha_useranswer']:'';
    if(empty($this->accumulated)) return false;
    return ($this->accumulated===$this->useranswer);
  }
} # class definition End

if(!empty($_POST['sniper_capaction'])) {
  $captcha_handler = new CSniperCaptcha();
  $captcha_handler->HandleClientRequest();
}
?>