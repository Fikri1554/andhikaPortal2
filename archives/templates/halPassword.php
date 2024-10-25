<?php
require_once("../../config.php");
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../../js/md5.js"></script>

<script type="text/javascript">
function exit()
{
	parent.tb_remove(false);
}

function ajaxGetChangePass(aksi, divId)
{
	var mypostrequest=new ajaxRequest()
	mypostrequest.onreadystatechange=function()
	{
		if (mypostrequest.readyState==4)
		{
			if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1)
			{
				document.getElementById(divId).innerHTML=mypostrequest.responseText
			}
			else
			{
				alert("An error has occured making the request")
			}
		}
	}
	
	if(aksi == 'saveChangePass')
	{			
		var newpass = document.getElementById("newpass").value;	
		var newpass2 = document.getElementById("newpass2").value;		
		
		if(newpass.replace(/ /g,"") == "")
		{
			document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" />&nbsp;New Password is still empty";
			document.getElementById('newpass').focus();
			return false;
		}
		if(newpass2.replace(/ /g,"") == "")
		{
			document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" />&nbsp;New Password 2 is still empty";
			document.getElementById('newpass2').focus();
			return false;
		}
		if(newpass.replace(/ /g,"") != "" && newpass != newpass2)
		{
			document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" />&nbsp;New password is not the same";
			document.getElementById('newpass2').focus();
			return false;
		}
		
		var parameters="halaman="+aksi+"&newpass="+MD5(newpass);		
	}
	
	mypostrequest.open("POST", "../halPostFold.php", true)
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	mypostrequest.send(parameters);
	
	exit();
}

function kosonginErrorMsg()
{
	document.getElementById('errorMsg').innerHTML = "&nbsp;";
}
</script>

<body bgcolor="#F8F8F8">
<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">

<tr><td height="5"><span id="idChangePass"></span></td></tr>

<tr valign="top">
    <td class="tdMyFolder" height="440" bgcolor="#FFFFFF" align="center">
    
    	<table border="0" cellpadding="0" cellspacing="5" height="100%" width="60%" class="formInput">
        <tr valign="top">
            <td align="center" height="20" colspan="2">
                <span class="teksMyFolder">:: CHANGE PASSWORD ::</span>	
            </td>
        </tr>
        <tr><td height="18">&nbsp;</td></tr>
        <tr>
            <td height="28" width="30%">New Password</td>
            <td><input type="password" class="elementSearch" id="newpass" style="width:95%; height:28px;color:#03C;" onBlur="kosonginErrorMsg();"></td>
        </tr>
        <tr>
            <td height="28">New Password 2</td>
            <td><input type="password" class="elementSearch" id="newpass2" style="width:95%;height:28px;color:#03C;" onBlur="kosonginErrorMsg();"></td>  
        </tr>
        <tr><td colspan="2" height="20" align="center" valign="middle">&nbsp; <span id="errorMsg" class="errorMsg">&nbsp;</span></td></tr>      
        
        <tr>
        	<td colspan="2" align="center" height="60">&nbsp;
                <button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" type="button" onClick="ajaxGetChangePass('saveChangePass','idChangePass');" style="width:90px;height:55px;">
                    <table width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
                    <tr>
                        <td align="center"><img src="../../picture/Floppy-Disk-blue-32.png" height="25"/> </td>
                        
                    </tr>
                    <tr>
                        <td align="center">SAVE</td>
                    </tr>
                    </table>
                </button>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        </table>
        
    </td>
</tr>

<tr><td height="5"></td></tr>

<tr valign="top">
	<td class="tdMyFolder" bgcolor="#FFFFFF" height="65" valign="middle">
    &nbsp;
    	<button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" type="button" style="width:90px;height:55px;" onClick="exit();" title="Close Window">
            <table width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
            <tr>
                <td align="center"><img src="../../picture/Metro-Shut-Down-Blue-32.png" height="25"/> </td>
            </tr>
            <tr>
                <td align="center">CLOSE</td>
            </tr>
            </table>
        </button>
        &nbsp;
    </td>
</tr>

</table>
</body>