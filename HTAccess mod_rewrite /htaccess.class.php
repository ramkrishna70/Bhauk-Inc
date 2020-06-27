<?php
/*
#    This program is free software; you can redistribute it and/or
#    modify it under the terms of the GNU General Public License
#    as published by the Free Software Foundation; either version 2
#    of the License, or (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#    http://www.gnu.org/licenses/gpl.txt
#
*/

 /******
 *
 * class HTAccess - class for emulation .htaccess of Apache (module mod rewrite)
 * 
 * @author	Vladimir S. Bredihin
 * @access	public
 * @version	1.0
 * @package	htaccess_emulation
 * @link	http://mycms.info
 *
 ******/

class HTAccess {
    var $parent;
    var $RewriteBase;
    var $htaccess;
    var $result;
   
    function HTAccess(&$result)
    {
    	if (isset ($result))
	    	$this->result = &$result;
    }
    
    function execute ($vdir, $type='lines')
    {
    	global $_SERVER;
        if (!$this->RewriteBase) $this->RewriteBase = $_SERVER['REDIRECT_URL'];
        if ($type == 'lines')
        {
            if ($htaccess = $this->_parse($type))
            {
                $vdirTmp = preg_replace("'^{$this->RewriteBase}[/]{0,1}'", '', $vdir);
                reset ($htaccess);
                foreach ($htaccess as $line)
                {
                    if ($line['switch'] == 'RewriteBase')
                    {
                        $this->RewriteBase = preg_replace(array('|^/|', '|/$|'), null, $line['regex']);
                        $vdirTmp = preg_replace("|^{$this->RewriteBase}[/]{0,1}|", '', $vdir);
                    }
                    elseif (ereg("{$line['regex']}", $vdirTmp))
                    {
                        $setVar = preg_replace("'{$line['regex']}'", $line['action'], $vdirTmp);
                        $line['action'] = preg_replace ("'^([^?]*)|'", '', $setVar);
                        switch ($line['switch'])
                        {
                            case 'RewriteRule': $vdir = $this->_RewriteRule($line['action']);
                            break;
                        }
                        break;
                    }
                }
            }
        }
        elseif ($type=='block')
        {
		$this->executeBlock ($vdir);
        }       
    }

    function executeBlock ($vdir)
    {
    	/**/
    }
    function _parse ($type)
    {
        if ($type == 'lines')
        {            
            reset ($this->htaccess[$type]);
            foreach ($this->htaccess[$type] as $line)
            {
                preg_match("'^[ ]*([^ ]+)[ ]+([^ ]+)[ ]*([^ ]*)[ ]*([^ ]*)$'", $line, $parseLine);
                $result[] = array ('switch' => $parseLine[1], 'regex'=>$parseLine[2], 
                                   'action' => $parseLine[3], 'parms'=>$parseLine[4]);
            }
        }
        
        return $result;
    }
    function _RewriteRule($action)
    {
    	preg_match("'^([^\?]*)[?]{0,1}(.*)$'", $action, $url);
    	$vdir = $this->_vdirMove ($url[1]);
    	$this->_setResult ($url[2]);
    	return $vdir;    	
    }
    function _vdirMove($moveto)
    {
    	$result = null;
    	if ($moveto)
    	{
        	$vdir = split('/', $this->RewriteBase);
        	$move = split('/', $moveto);
        	foreach ($move as $step)
        	{
        	    switch ($step)
        	    {
        	        case '.':break;
        	        case '..':unset ($vdir[end($vdir)]);break;
        	        default: $vdir [] = $step;
        	    }
        	}
        	$result=implode ('/', $vdir);
    	}
    	return $result;
    }
    function _setResult ($get)
    {
        if ($get)
        {
            $get = split ('&', $get);
            foreach ($get as $value)
            {
                $var = split ('=', $value, 2);
                $this->result[$var[0]] = $var[1];
            }
        }
    }
    function setLine($line)
    {
        $this->htaccess ['lines'][] = $line;
    }
    function setBlock($htaccess)
    {
        $this->htaccess ['block'] = $htaccess;
    }
}
?>