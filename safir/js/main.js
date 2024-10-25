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

function getOffset( el ) {
    var _x = 0;
    var _y = 0;
    while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
        _x += el.offsetLeft - el.scrollLeft;
        _y += el.offsetTop - el.scrollTop;
        // chrome/safari
        if ($.browser.webkit) {
            el = el.parentNode;
        } else {
            // firefox/IE
            el = el.offsetParent;
        }
    }
    return { top: _y, left: _x };
}

function textCounter(field, countfield, maxlimit) 
{
	if (field.value.length > maxlimit) // if too long...trim it!
		field.value = field.value.substring(0, maxlimit);
		// otherwise, update 'characters left' counter
	else 
		countfield.value = maxlimit - field.value.length;
}

function findAndReplace(searchText, replacement, searchNode) 
{
    if (!searchText || typeof replacement === 'undefined') 
	{
        // Throw error here if you want...
        return;
    }
    var regex = typeof searchText === 'string' ?
                new RegExp(searchText, 'g') : searchText,
        childNodes = (searchNode || document.body).childNodes,
        cnLength = childNodes.length,
        excludes = 'html,head,style,title,link,meta,script,object,iframe';
    while (cnLength--) {
        var currentNode = childNodes[cnLength];
        if (currentNode.nodeType === 1 &&
            (excludes + ',').indexOf(currentNode.nodeName.toLowerCase() + ',') === -1) {
            arguments.callee(searchText, replacement, currentNode);
        }
        if (currentNode.nodeType !== 3 || !regex.test(currentNode.data) ) {
            continue;
        }
        var parent = currentNode.parentNode,
            frag = (function(){
                var html = currentNode.data.replace(regex, replacement),
                    wrap = document.createElement('div'),
                    frag = document.createDocumentFragment();
                wrap.innerHTML = html;
                while (wrap.firstChild) {
                    frag.appendChild(wrap.firstChild);
                }
                return frag;
            })();
        parent.insertBefore(frag, currentNode);
        parent.removeChild(currentNode);
    }
}
