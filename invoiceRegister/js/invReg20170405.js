// JavaScript Document
function enabledBtn(idButton)
{
	$('#'+idButton).attr("disabled","");
	$('#'+idButton).removeClass("btnStandarDis");
    $('#'+idButton).addClass("btnStandar");
}

function disabledBtn(idButton)
{
	$('#'+idButton).attr("disabled","disabled");
	$('#'+idButton).removeClass("btnStandar");
    $('#'+idButton).addClass("btnStandarDis");
}

function enabledElement(idElement)
{
	$('#'+idElement).attr("disabled","");
}

function disabledElement(idElement)
{
	$('#'+idElement).attr("disabled","disabled");
}

function pleaseWait()
{
	document.getElementById('loaderImg').style.visibility = "visible";
}

function doneWait()
{
	document.getElementById('loaderImg').style.visibility = "hidden";
}

function disableLeftClick()
{
	if (event.button==1) 
	{
		alert('Please Wait...')
		return false;
	}
}

function enableLeftClick()
{
	if (event.button==1) 
	{
		return true;
	}
}

function panggilEnableLeftClick()
{
	document.onmousedown=enableLeftClick;
}

function loadIframe(iframeName, url) {
    var $iframe = $('#' + iframeName);
    if ( $iframe.length ) {
        $iframe.attr('src',url);   
        return false;
    }
    return true;
}

function changeToUpperCase(event,obj) {
    charValue = (document.all) ? event.keyCode : event.which;
    if (charValue!="8" && charValue!="0" && charValue != "27"){
        obj.value += String.fromCharCode(charValue).toUpperCase();
        return false;
    }else{
        return true;
    }
}

function textCounter(field, countfield, maxlimit) {
if (field.value.length > maxlimit) // if too long...trim it!
field.value = field.value.substring(0, maxlimit);
// otherwise, update 'characters left' counter
else 
countfield.value = maxlimit - field.value.length;
}

function maxChar(field, maxLimit)
{
	if(field.value.length > maxLimit)
	{
		field.value = field.value.substring(0, maxLimit);
	}
}

function textCounter1(field, countfield, maxlimit) {
if (field.length > maxlimit) // if too long...trim it!
field = field.substring(0, maxlimit);
// otherwise, update 'characters left' counter
else 
countfield = maxlimit - field.length;
}

function maxChar(field, maxLimit)
{
	if(field.length > maxLimit)
	{
		field = field.substring(0, maxLimit);
	}
}

function isIE () {
  var myNav = navigator.userAgent.toLowerCase();
  return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
}

function ieVersion()
{
	var versi = "Unknown";
	
	if (window.ActiveXObject === undefined)
		versi = "null";
	else if (!document.querySelector) 
		versi = "7";
	else if (!document.addEventListener) 
		versi = "8";
	else if (!window.atob) 
		versi = "9";
	else if (!document.__proto__) 
		versi = "10"
	else 
		versi = "11";
	return versi;
}

function jenisSistemOperasi()
{
	var OSName = "Unknown";
	if (window.navigator.userAgent.indexOf("Windows NT 10.0") != -1) OSName="Windows 10";
	if (window.navigator.userAgent.indexOf("Windows NT 6.2") != -1) OSName="Windows 8";
	if (window.navigator.userAgent.indexOf("Windows NT 6.1") != -1) OSName="Windows 7";
	if (window.navigator.userAgent.indexOf("Windows NT 6.0") != -1) OSName="Windows Vista";
	if (window.navigator.userAgent.indexOf("Windows NT 5.1") != -1) OSName="Windows XP";
	if (window.navigator.userAgent.indexOf("Windows NT 5.0") != -1) OSName="Windows 2000";
	if (window.navigator.userAgent.indexOf("Mac")!=-1) OSName="Mac/iOS";
	if (window.navigator.userAgent.indexOf("X11")!=-1) OSName="UNIX";
	if (window.navigator.userAgent.indexOf("Linux")!=-1) OSName="Linux";

	return OSName;
}