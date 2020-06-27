// as_jsfunclib.js: useful javascript function set,
// written / assembled by Alexander Selifonov <as-works@narod.ru>
// V 1.006.114  last change: 28.02.2008
var noajax = 'No XmlHttpRequest in Your browser !';
var postcont = 'application/x-www-form-urlencoded; charset=UTF-8';
var HttpRequestExist = true;

function NewXMLHttpRequest()
{
  if(!HttpRequestExist) return false;
  var xmlhttplocal;
  try {
    xmlhttplocal= new ActiveXObject("Msxml2.XMLHTTP")
  } catch (e)
  {
    try {
      xmlhttplocal= new ActiveXObject("Microsoft.XMLHTTP")
    }
    catch (E) {
        xmlhttplocal=false;
    }
  }

  if (!xmlhttplocal && typeof XMLHttpRequest!='undefined') {
    try { var xmlhttplocal = new XMLHttpRequest(); }
    catch (e) { xmlhttplocal = HttpRequestExist = false; }
  }
  return(xmlhttplocal);
}

function SetFormValue(felem,newval) {
  var itmp;
  if(typeof(felem)!='object') return;
  switch(felem.type) {
  case 'select-one':
    for(itmp=0;itmp<felem.options.length;itmp++) {
      if(felem.options[itmp].value==newval) {felem.selectedIndex=itmp; break; }
    }
    break;
  case 'checkbox':
    felem.checked = (newval>0);
    break;
  default:
    felem.value = newval;
    break;
  }
}

function GetFormValue(felem) {
  ret = '';
  var itmp;
  if(typeof(felem)!='object') return ret;
  switch(felem.type) {
  case 'select-one':
    itmp=felem.selectedIndex;
    if(itmp>=0) ret = felem.options[itmp].value;
    break;
  case 'checkbox':
    ret = felem.checked;
    break;
  default:
    ret = felem.value;
    break;
  }
  return ret;
}
function ComputeParamString(frmname, skipempty, fldlist)
{ // compute param string with all "frmname" form values
    var theForm;
    if(isNaN(skipempty)) skipempty=false;
    if(typeof(frmname)=='object') theForm=frmname;
    else theForm=eval("window.document."+frmname);
    var els = theForm.elements;
    var retval = "";
    for(i=0; i<els.length; i++)
    { //<2>
        vname = els[i].name;
        if(vname=='') continue;
        if(typeof(fldlist)=='object' && !IsInArray(fldlist,vname)) continue;
        oneval = "";
        t = els[i].type;
        switch(t)
        { //<3>
           case "select-one" :
               var selobj =els[i];
               var nIndex = selobj.selectedIndex;
               if (nIndex >=0) oneval = selobj.options[nIndex].value;
               break;
           case "text": case "button": case "textarea": case 'password': case "hidden": case 'submit':
               if(els[i].value != "")
                 oneval = els[i].value;
               break;
           case "checkbox":
/*               if(vname =='dago_limit') {
               alert(vname+ ' type: '+ typeof(els[i]) );
               alert('value: '+els[i].value + ' length: '+ els[i].length);
               }
*/
               oneval = (els[i].checked ? "1":"0");
               break;
           case "radio":
               if(els[i].value != "" && els[i].checked)
                 oneval = els[i].value;
               break;
           default:
//              alert(vname+': not supported control: '+els[i].type);
               oneval = els[i].value;
              break;
        } //<3>
        if((oneval!='' && oneval!=0) || (!skipempty)) {
           if (retval != "") retval = retval + "&";
           retval = retval+vname+'='+encodeURIComponent(oneval);
        }
    } //<2>
    return retval;
} // ComputeParamString()

function SelectBoxSet(frm,objname, val){
  var obj;
  elems = '';
  if(typeof frm=='string') eval("obj=document."+frm+"."+objname);
  else eval('obj=frm.'+objname);
  if(!isNaN(obj.selectedIndex)) //alert('SelectBoxSet error: '+frm+'.'+objname+" - not a selectbox !");
  {
    for(kk=0; kk<obj.options.length; kk++) {
      if (obj.options[kk].value==val) { obj.selectedIndex = kk; return; }
    }
  }
}
function FindInSelectBox(frm,objname,val){
  var obj;
  if(typeof frm=='string') eval("obj=document."+frm+"."+objname);
  else eval('obj=frm.'+objname);
  if(isNaN(obj.selectedIndex)) return -1;
  var kk; var vlow = val.toLowerCase();
  for(kk=0; kk<obj.options.length; kk++) {
    if (obj.options[kk].text.toLowerCase()==vlow) return kk;
  }
  return -1;
}
function DateRepair(sparam)
{ // make input date well formatted: dd.mm.yyyy
  var sdate = (typeof sparam =='string' ? sparam : sparam.value);

  if(sdate.length<1) return '';
  var d_date = new Date();
  if(sdate.length<3) sdate +='.'+d_date.getMonth()+'.'+d_date.getDay();
  var syy='', smm='', sdd='';
  var dp = 1;

  if(sdate.charAt(dp)>=0 && sdate.charAt(dp)<='9' && sdate.length>2 )
    dp=2;
  sdd = sdate.substring(0,dp);
  if(sdate.charAt(dp)>=0 && sdate.charAt(dp)<='9')
     sdate = sdate.substring(dp);
  else  sdate = sdate.substring(dp+1);

  dp = 1;
  if(sdate.charAt(dp)>=0 && sdate.charAt(dp)<='9' && sdate.length>=2 )  dp=2;

  smm = sdate.substring(0,dp);
  if(sdate.charAt(dp)>=0 && sdate.charAt(dp)<='9')
       sdate = sdate.substring(dp);
  else sdate = sdate.substring(dp+1);

  if(sdate == '') {
    sdate = d_date.getFullYear();
  }
  syy = sdate;


  if(syy.length<4) {
     if(syy>20 && syy<=99) syy = '19'+syy;
     else syy = (syy.length==2 ? '20' : 200) + syy;
  }
  if(sdd<=0 || smm<=0) return '';
  if(sdd.length<2) sdd = '0'+sdd;
  if(smm.length<2) smm = '0'+smm;

  ret = sdd+'.'+smm+'.'+syy;
  if(typeof(sparam)=='object') { sparam.value = ret; return false; }
  delete d_date;
  return ret;
}

function NumberRepair(sparam) {
  var sdata = (typeof(sparam)=='string' ? sparam : sparam.value);
  var ret = sdata.replace(',','.');
  ret = parseFloat(ret);
  if(isNaN(ret)) ret = '0';
  if(typeof(sparam)=='object') { sparam.value = ret; return; }
  return ret;
}
function AddToDate(sdate, years, months, days)
{ // sdate must be in 'dd.mm.yyyy' format !!!
   var mlen = [0,31,28,31,30,31,30,31,31,30,31,30,31];
   var delem = sdate.split('.');
   if(delem.length<3) delem = sdate.split('/');
   if(delem.length<3) return '';
   var nday = (delem[0]-0);
   var nmon = (delem[1]-0);
   var nyear = (delem[2]-0);
   if(months>12 || months<-12) {
      years += Math.floor(months/12);
      months = months % 12;
   }
   if(years!=0) nyear +=years;
   if(months>0) {
       nmon += months;
       while(nmon >12) {
         nyear++;
         nmon -= 12;
       }
   }
   else if(months<0) {
       nmon += months;
       while(nmon <-12) {
         nyear--;
         nmon += 12;
       }
   }
   var nmlen = ((nyear % 4==0) && nmon==2) ? 29 : mlen[nmon];
   if(days!=0) {
          nday+=days;
          if(nday > nmlen) {
            nday -= nmlen;
            nmon++;
            if(nmon>12) { nmon=1; nyear++;};
          }
          else if(nday<=0) {
            nmon--;
            if(nmon<=0) { nmon=12; nyear--; }
          }
          nmlen = ((nyear % 4==0) && nmon==2) ? 29 : mlen[nmon];
          if(nday<=0) nday += nmlen;
   }
   if(nday>nmlen) nday = nmlen;

   if(nday<10) nday = '0'+nday;
   if(nmon<10) nmon = '0'+nmon;
   return (nday+'.'+nmon+'.'+nyear);
}

function IsInArray(arr, val)
{
   for(lp=0; lp<arr.length;lp++){ if(arr[lp]==val) return true; }
   return false;
}

function FormatInteger( integer )
{ // author:
  var result = '';
  var pattern = '###,###,###,###';
  if(typeof(integer)!='string') integer = integer+'';
  integerIndex = integer.length - 1;
  patternIndex = pattern.length - 1;
  while ( (integerIndex >= 0) && (patternIndex >= 0) )
  {
    var digit = integer.charAt( integerIndex );
    integerIndex--;
    // Skip non-digits from the source integer (eradicate current formatting).
    if ( (digit < '0') || (digit > '9') )  continue;

    // Got a digit from the integer, now plug it into the pattern.
    while ( patternIndex >= 0 )
    {
      var patternChar = pattern.charAt( patternIndex );
      patternIndex--;
      // Substitute digits for '#' chars, treat other chars literally.
      if ( patternChar == '#' )
      {
        result = digit + result;
        break;
      }
      else { result = patternChar + result; }
    }
  }
  return result;
}

// compares two 'dd.mm.yyyy' strings as dates
function CompareDates(dt1,dt2) {
  var cd1=dt1.substring(6,10)+dt1.substring(3,5)+dt1.substring(0,2);
  var cd2=dt2.substring(6,10)+dt2.substring(3,5)+dt2.substring(0,2);
  if(cd1>cd2) return 1;
  if(cd1<cd2) retun -1;
  return 0;
}
/* useful functions from Dustin Diaz, http://www.dustindiaz.com/top-ten-javascript/
*/

/* grab Elements from the DOM by className */
function getElementsByClass(searchClass,node,tag) {
	var classElements = new Array();
	if ( node == null )
		node = document;
	if ( tag == null )
		tag = '*';
	var els = node.getElementsByTagName(tag);
	var elsLen = els.length;
	var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
	for (i = 0, j = 0; i < elsLen; i++) {
		if ( pattern.test(els[i].className) ) {
			classElements[j] = els[i];
			j++;
		}
	}
	return classElements;
}
/* get, set, and delete cookies */
function getCookie( name ) {
	var start = document.cookie.indexOf( name + "=" );
	var len = start + name.length + 1;
	if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) ) {
		return null;
	}
	if ( start == -1 ) return null;
	var end = document.cookie.indexOf( ";", len );
	if ( end == -1 ) end = document.cookie.length;
	return unescape( document.cookie.substring( len, end ) );
}

function setCookie( name, value, expires, path, domain, secure ) {
	var today = new Date();
	today.setTime( today.getTime() );
	if ( expires ) {
		expires = expires * 1000 * 60 * 60 * 24;
	}
	var expires_date = new Date( today.getTime() + (expires) );
	document.cookie = name+"="+escape( value ) +
		( ( expires ) ? ";expires="+expires_date.toGMTString() : "" ) + //expires.toGMTString()
		( ( path ) ? ";path=" + path : "" ) +
		( ( domain ) ? ";domain=" + domain : "" ) +
		( ( secure ) ? ";secure" : "" );
}

function deleteCookie( name, path, domain ) {
	if ( getCookie( name ) ) document.cookie = name + "=" +
			( ( path ) ? ";path=" + path : "") +
			( ( domain ) ? ";domain=" + domain : "" ) +
			";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}

/* quick getElement reference */
function $() {
	var elements = new Array();
	for (var i = 0; i < arguments.length; i++) {
		var element = arguments[i];
		if (typeof element == 'string')
			element = document.getElementById(element);
		if (arguments.length == 1)
			return element;
		elements.push(element);
	}
	return elements;
}

function asGetObj(name)
{
 if (document.getElementById) {
   return document.getElementById(name);
 }
 else if (document.all) {
   return document.all[name];
 }
 else if (document.layers)
 {
   if (document.layers[name]) {
     return document.layers[name];
     this.style = document.layers[name];
   }
   else { return document.layers.testP.layers[name]; }
 }
 return null;
}
