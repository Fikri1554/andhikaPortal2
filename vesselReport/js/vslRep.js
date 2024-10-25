// JavaScript Document

function formatNumber (num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

function formatNumber11 (num) {
    return num.toString().replace(".", "");
}

function setup()
{	
	var errorDateMessage = "Format tanggal salah";
	
	new NumberMask(new NumberParser(0, "", "", true), "voucher", 5);
	new NumberMask(new NumberParser(0, "", "", true), "reference", 5);
	new NumberMask(new NumberParser(0, "", "", true), "jobNo", 0);
	
	var datePaid = new DateMask("dd/MM/yyyy", "datePaid");
    datePaid.validationMessage = errorDateMessage;
}

// #########################################################################################
cookieName="page_scroll"
expdays=365

function loadIframe(iframeName, url) {
    var $iframe = $('#' + iframeName);
    if ( $iframe.length ) {
        $iframe.prop('src',url);   
        return false;
    }
    return true;
}

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
	//alert("saveScroll "+Cookienameplus);
	var expdate = new Date ()
	expdate.setTime (expdate.getTime() + (expdays*24*60*60*1000)); // expiry date
	
	/*var x = (document.pageXOffset?document.pageXOffset:document.body.scrollLeft)
	var y = (document.pageYOffset?document.pageYOffset:document.body.scrollTop)*/
	var x = document.documentElement.scrollLeft;
	var y = document.documentElement.scrollTop;
	Data=x + "_" + y
	setCookie(cookieName+Cookienameplus,Data,expdate)
	//alert(Cookienameplus+"= "+Data);
}

function loadScroll(Cookienameplus)
{ // added function
	//alert("loadScroll "+Cookienameplus);
	inf=getCookie(cookieName+Cookienameplus)
	if(!inf){return}
	var ar = inf.split("_")
	if(ar.length == 2)
	{
		window.scrollTo(parseInt(ar[0]), parseInt(ar[1]))
		//document.documentElement.scrollTop = document.body.scrollTop = 1000;
	}
}
// #########################################################################################

function enabledBtn(idButton)
{
	$('#'+idButton).prop("disabled","");
	$('#'+idButton).removeClass("btnStandarDis");
    $('#'+idButton).addClass("btnStandar");
}

function disabledBtn(idButton)
{
	$('#'+idButton).prop("disabled","disabled");
	$('#'+idButton).removeClass("btnStandar");
    $('#'+idButton).addClass("btnStandarDis");
}

function enabledParentBtn(idButton)
{
	$('#'+idButton, parent.document).prop("disabled","");
	$('#'+idButton, parent.document).removeClass("btnStandarDis");
    $('#'+idButton, parent.document).addClass("btnStandar");
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

function pesanError(pesan, itemFokus)
{
	document.getElementById(itemFokus).focus(); 
	$('#idErrorMsg').css('visibility','visible'); 
	$("#idErrorMsg").html("<img src=\"../picture/exclamation-red.png\"/>&nbsp;<span>"+pesan+"</span>&nbsp;");
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

function blinker() {
    $('#idErrorMsg').fadeOut(250);
	$('#idErrorMsg img').fadeOut(300);
		
    $('#idErrorMsg').fadeIn(500);
	$('#idErrorMsg img').fadeIn(550);
	
	$('#idErrorMsg2').fadeOut(250);
	$('#idErrorMsg2 img').fadeOut(250);
		
    $('#idErrorMsg2').fadeIn(500);
	$('#idErrorMsg2 img').fadeIn(500);
}

setInterval(blinker, 1500);
