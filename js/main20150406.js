// JavaScript Document
function ajaxRequest()
{
	var activexmodes=["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"] //activeX versions to check for in IE
 	if (window.ActiveXObject)
	{
		 //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
  		for (var i=0; i<activexmodes.length; i++)
		{
			try
			{
				return new ActiveXObject(activexmodes[i])
   			}
   			catch(e)
			{
				//suppress error
   			}
  		}
 	}
 	else if (window.XMLHttpRequest) // if Mozilla, Safari etc
  	return new XMLHttpRequest()
 	else
  	return false
}

navigator.sayswho= (function()
{
	var N= navigator.appName, ua= navigator.userAgent, tem;
	var M= ua.match(/(opera|chrome|safari|firefox|msie)\/?\s*(\.?\d+(\.\d+)*)/i);
 	if(M && (tem= ua.match(/version\/([\.\d]+)/i))!= null) M[2]= tem[1];
  	//M= M? [M[1], M[2]]: [N, navigator.appVersion,'-?'];
	M= M? [M[1]]: [N, navigator.appVersion,'-?'];
	
  	return M;
})();

function detectBrowser()
{
	return navigator.sayswho;
}

cookieName="page_scroll"
expdays=365

// An adaptation of Dorcht's cookie functions.

function setCookie(name, value, expires, path, domain, secure)
{
	if (!expires){expires = new Date()}
	document.cookie = name + "=" + escape(value) + 
	((expires == null) ? "" : "; expires=" + expires.toGMTString()) +
	((path == null) ? "" : "; path=" + path) +
	((domain == null) ? "" : "; domain=" + domain) +
	((secure == null) ? "" : "; secure")
}

function getCookie(name)
{
	var arg = name + "="
	var alen = arg.length
	var clen = document.cookie.length
	var i = 0
	while (i < clen) {
	var j = i + alen
	if (document.cookie.substring(i, j) == arg){
	return getCookieVal(j)
	}
	i = document.cookie.indexOf(" ", i) + 1
	if (i == 0) break
	}
	return null
}

function getCookieVal(offset)
{
	var endstr = document.cookie.indexOf (";", offset)
	if (endstr == -1)
	endstr = document.cookie.length
	return unescape(document.cookie.substring(offset, endstr))
}

function deleteCookie(name,path,domain)
{
	document.cookie = name + "=" +
	((path == null) ? "" : "; path=" + path) +
	((domain == null) ? "" : "; domain=" + domain) +
	"; expires=Thu, 01-Jan-00 00:00:01 GMT"
}

function saveScroll(Cookienameplus)
{ // added function
	var expdate = new Date ()
	expdate.setTime (expdate.getTime() + (expdays*24*60*60*1000)); // expiry date
	
	var x = (document.pageXOffset?document.pageXOffset:document.body.scrollLeft)
	var y = (document.pageYOffset?document.pageYOffset:document.body.scrollTop)
	Data=x + "_" + y
	setCookie(cookieName+Cookienameplus,Data,expdate)
}

function loadScroll(Cookienameplus)
{ // added function
	inf=getCookie(cookieName+Cookienameplus)
	if(!inf){return}
	var ar = inf.split("_")
	if(ar.length == 2)
	{
		window.scrollTo(parseInt(ar[0]), parseInt(ar[1]))
	}
}

function input_onchange(me)
{ 
	if (me.value.length != me.maxlength)
	{
		return;
	}
	var i;
	var elements = me.form.elements;
	for (i=0, numElements=elements.length; i<numElements; i++) 
	{
		if (elements[i]==me)
		{
			break;
		}
	}
	elements[i+1].focus();
}

function zeroFill( number, width )
{
  width -= number.toString().length;
  if ( width > 0 )
  {
    return new Array( width + (/\./.test( number ) ? 2 : 1) ).join( '0' ) + number;
  }
  return number + ""; // always return a string
}

function daysBetween( date1, date2 ) 
{
  //Get 1 day in milliseconds
  var one_day=1000*60*60*24;

  // Convert both dates to milliseconds
  var date1_ms = date1.getTime();
  var date2_ms = date2.getTime();

  // Calculate the difference in milliseconds
  var difference_ms = date2_ms - date1_ms;
    
  // Convert back to days and return
  return Math.round(difference_ms/one_day); 
}

function dayAfter(date1, sumDay)
{
	var one_day=1000*60*60*24;

	  // Convert both dates to milliseconds
	  var date1_ms = date1.getTime();
	  var tambahHari = sumDay * one_day;
	  
	  var hasil = date1_ms + tambahHari;
	  var tahun = new Date(hasil).getFullYear()
	  var bulan = zeroFill( new Date(hasil).getMonth(), 2 );
	  var tanggal = zeroFill( new Date(hasil).getDate(), 2 );
	  
	  // Calculate the difference in milliseconds
	  //var difference_ms = date2_ms - date1_ms;
		
	  // Convert back to days and return
	  return tanggal+"/"+bulan+"/"+tahun; 
}
/*
function autoTab(nowFocus, nextFocus)
{
	if (document.getElementById(nowFocus).value.length == document.getElementById(nowFocus).maxLength)
	{
    	document.getElementById(nextFocus).focus();
	}
}*/