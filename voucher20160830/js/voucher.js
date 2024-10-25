// JavaScript Document

// #########################################################################################
// #########################################################################################
// #########################################################################################
function formatNumber (num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

function setup()
{
	$("#paidTo").keydown(function( ePaidto )
	{
		var paidTo = $("#paidTo");
		if(ePaidto.keyCode == 222 || ePaidto.keyCode == 192)// UNTUK CHAR TANDA KUTIP (")(')
		{
			return false;
		}
	});
	
	$("#invNo").keydown(function( eInvno )
	{
		if(eInvno.keyCode == 222 || ePaidto.keyCode == 192)// UNTUK CHAR TANDA KUTIP (")(')
		{
			return false;
		}
	});
	
	new NumberMask(new NumberParser(0, "", "", true), "voucher", 5);
	new NumberMask(new NumberParser(0, "", "", true), "reference", 5);
	new NumberMask(new NumberParser(0, "", "", true), "jobNo", 0);
	
	var amountMaskk = new NumberParser(2, ".", ",", true);
	var amountMask = new NumberMask(amountMaskk, "amount", 12);
	
	var errorMessageDate = "Invalid date: ${value}. Expected format: ${mask}";
	var datePaid = new DateMask("dd/MM/yyyy", "datePaid");
	datePaid.validationMessage = errorMessageDate;
	
	$(document).ready(function()
	{		
		$(this).mouseover(function(e)
		{
			var idActElem = $(document.activeElement).attr("id");
			var container = $('#divIframeVoucherList');
			
			$('#divIframeVoucherList').css('width', '467');
			$('#divIframeVoucherList').css('z-index', '1');
			window.frames['iframeVoucherList'].$('#judul').css('left', '0px');
				
			if (!container.is(e.target) // if the target of the click isn't the container...
				&& container.has(e.target).length === 0 // ... nor a descendant of the container
				 // APAKAH ELEMENT YANG AKTIF TESVEND APA BUKAN
				) 
			{
				$('#divIframeVoucherList').css('width', '250');
				$('#divIframeVoucherList').css('z-index', '0');
			}
		});	
	});
}

// #########################################################################################
cookieName="page_scroll"
expdays=365

function loadIframe(iframeName, url) {
    var $iframe = $('#' + iframeName);
    if ( $iframe.length ) {
        $iframe.attr('src',url);   
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

function enabledParentBtn(idButton)
{
	$('#'+idButton, parent.document).attr("disabled","");
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

function blinker() {
    $('#idErrorMsg').fadeOut(250);
	$('#idErrorMsg img').fadeOut(250);
		
    $('#idErrorMsg').fadeIn(500);
	$('#idErrorMsg img').fadeIn(500);
}

setInterval(blinker, 1500);
