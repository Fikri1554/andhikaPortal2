<?php
require_once("../../config.php");
?>

<script type="text/javascript">
function exit()
{
	parent.tb_remove(false);
}

function pilihPageReg(customCari)
{
	document.getElementById("customCari").value = customCari;
}

function klikBtnDoSearching()
{
	var customCari = document.getElementById('customCari').value;
	if(customCari == "standard")
	{
		var paramText = document.getElementById('sta_paramText').value;
		for(var i = 0; i < formSta.sta_folderFile.length; i++)
		{
			if(formSta.sta_folderFile[i].checked == true)
			{
				var cekFolderFile = formSta.sta_folderFile[i].value;
			}
		}
	}
	
	if(customCari == "advanced")
	{
		var paramText = document.getElementById('adv_paramText').value;
	}
	
	alert(cekFolderFile);
}


</script>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css"/>

<script src="../../jquery-1.4.3.js"></script>
<script src="../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>

<link type="text/css" rel="stylesheet" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>
<SCRIPT type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>

<body bgcolor="#F0FFF2">
<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">

<tr valign="top">
	<td align="center" height="20">
    	<table cellpadding="0" cellspacing="0" width="100%">
        <tr>
        	<td align="center"><span class="teksMyFolder">:: SEARCH ::</span></td>
        </tr>
        </table>	
    </td>
</tr>

<tr><td height="5"></td></tr>

<tr valign="top">
    <td class="" style="cursor:none;" height="445" bgcolor="#F0FFF2" align="center">
    
    	<div id="TabbedPanels1" class="TabbedPanels">
            <ul class="TabbedPanelsTabGroup">
                <li class="TabbedPanelsTab" tabindex="0" onClick="pilihPageReg('standard');">Standard search</li>
                <li class="TabbedPanelsTab" tabindex="0" onClick="pilihPageReg('advanced');">Advanced search</li>
            </ul>
			<div class="TabbedPanelsContentGroup">
	
<!-- ############# AWAL ISI CERTIFICATE ############# -->				
				<div class="TabbedPanelsContent" id="idHalCert">
                    <table border="0" cellpadding="0" cellspacing="5" height="410" width="95%" class="formInput">
                    <tr><td height="18">&nbsp;</td></tr>
                    <tr>
                        <td height="28" width="15%">Type text</td>
                        <td><input type="text" class="elementSearch" id="sta_paramText" size="70" style="height:28px;color:#03C;"></td>
                    </tr>
                    <tr>
                        <td height="28">&nbsp;</td>
                        <td>
                        <form id="formSta" name="formSta">
                        <input class="elementSearch" type="radio" id="sta_folderFile1" name="sta_folderFile" value="folder" style="height:28px;color:#03C;" checked>&nbsp;Folder&nbsp;&nbsp;&nbsp;
                        <input class="elementSearch" type="radio" id="sta_folderFile2" name="sta_folderFile" value="file" style="height:28px;color:#03C;">&nbsp;File
                        </form>
                        </td>
                    </tr>
                    
                    <tr><td>&nbsp;</td></tr>
                    </table>
                </div>
<!-- ############# AKHIR ISI CERTIFICATE ############# -->
		
<!-- ############# AWAL ISI CITY ############# -->				
                <div class="TabbedPanelsContent" id="idHalCity">
                    <table cellpadding="0" cellspacing="5" width="95%" height="410" class="formInput">
                    <tr><td height="18">&nbsp;</td></tr>
                    <tr>
                        <td height="28" width="15%">Type text</td>
                        <td><input type="text" class="elementSearch" id="adv_paramText" size="70" style="height:28px;color:#03C;"></td>
                    </tr>
                    <tr>
                    <td height="28">&nbsp;</td>
                        <td>
                        
                         <input class="elementSearch" type="radio" id="adv_folderFile" name="adv_folderFile" style="height:28px;color:#03C;" checked>&nbsp;Folder&nbsp;&nbsp;&nbsp;
                        <input class="elementSearch" type="radio" id="adv_folderFile" name="adv_folderFile" style="height:28px;color:#03C;">&nbsp;File
                        
                        </td>
                    </tr>
                    <tr>
                        <td height="28">Type</td>
                        <td>
                            <select class="elementSearch" id="adv_type" name="adv_type" style="height:28px;color:#03C;">
                                <option value="">All</option>
                                <option value="">Pdf</option>
                                <option value="">Doc</option>
                                <option value="">Xls</option>
                                <option value="">Ppt</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td height="28">Location</td>
                        <td>
                            <select class="elementSearch" id="adv_locations" name="adv_locations" style="height:28px;color:#03C;">
                                <option value="">All</option>
                                <option value="">My Folder</option>
                                <option value="">Own Shared</option>
                                <option value="">Other Shared</option>
                                <option value="">Subordinate</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td height="28">Employee</td>
                        <td>
                            <select class="elementSearch" id="adv_emp" name="adv_emp" style="height:28px;color:#03C;">
                                <option value="">All</option>
                                <option value="">OWN SHARED</option>
                                <option value="">OTHER SHARED</option>
                                <option value="">SUBORDINATE</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td height="28">Date input</td>
                        <td>
                        	Start&nbsp;&nbsp;<input class="elementSearch" type="text" id="adv_startDate" size="10" style="height:28px;color:#03C;"/>
                            &nbsp;<img src="../../picture/calendar.gif" style="cursor: pointer; border: 1px solid red;" title="Select Date" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onClick="displayCalendar(document.getElementById('adv_startDate'),'dd/mm/yyyy',this, '', '', '193', '0')"/>
                            <span style="color:#333333;font-size:10px;">(DD/MM/YYYY)</span>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            End&nbsp;&nbsp;<input class="elementSearch" type="text" id="adv_endDate" size="10" style="height:28px;color:#03C;"/>
                            &nbsp;<img src="../../picture/calendar.gif" style="cursor: pointer; border: 1px solid red;" title="Select Date" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onClick="displayCalendar(document.getElementById('adv_endDate'),'dd/mm/yyyy',this, '', '', '193', '0')"/>
                            <span style="color:#333333;font-size:10px;">(DD/MM/YYYY)</span>
                        </td>
                    </tr>
                    <tr>
                        <td height="28">Size</td>
                        <td>
                          	Start&nbsp;&nbsp;<input class="elementSearch" type="text" id="adv_startSize" size="5" style="height:28px;color:#03C;"/>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            End&nbsp;&nbsp;<input class="elementSearch" type="text" id="adv_endSize" size="5" style="height:28px;color:#03C;"/>  
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    </table>
                </div>
<!-- ############# AKHIR ISI CITY ############# -->
			</div>
		</div>
    </td>
</tr>

<tr>
    <td height="5">
    	<input type="hidden" id="customCari" value="standard">
    </td>
</tr>

<tr valign="top">
	<td>
    	<button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" type="button" style="width:90px;height:55px;" onClick="exit();" title="Close Detail File Window">
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
        <button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" type="button" onClick="klikBtnDoSearching();" style="width:115px;height:55px;">
            <table width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
            <tr>
                <td align="center"><img src="../../picture/Thumbs-Up-32.png" height="25"/> </td>
                
            </tr>
            <tr>
                <td align="center">DO SEARCHING</td>
            </tr>
            </table>
        </button>
        &nbsp;
    </td>
</tr>
</table>
</body>

<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//-->
</script>