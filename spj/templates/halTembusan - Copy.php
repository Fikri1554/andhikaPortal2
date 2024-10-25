<!DOCTYPE HTML>
<?php
require_once('../../config.php');
require_once('../configSpj.php');
$formId = $_GET['formId'];
$tipe = $_GET['tipe'];

$btnSelect = "ajaxCopy($('#jmlCopy').val());";
$jmlCopy = 3;
if($_GET['tipe'] == "edit")
{
	$btnSelect = "ajaxCopyEdit();";
	$jmlCopy = $CSpj->jmlTembusan($formId);
}
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../css/cssSpj.css" rel="stylesheet" type="text/css" />
<link href="../css/loading.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>

<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script src="../../js/JavaScriptUtil.js"></script>
<script src="../../js/Parsers.js"></script>
<script src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<SCRIPT type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../js/loading.js"></script>
<?php
if($aksiPost == "add")
{
	$formIdPost = $_POST['formId'];
	$dest = $CSpj->detilForm($formIdPost, "destination");
	$tipeHist = "";
	if($tipe == "new")
	{
		$tipeHist = "Membuat";
	}
	if($tipe == "edit")
	{
		$tipeHist = "Update";
		$CKoneksiSpj->mysqlQuery("DELETE FROM copy WHERE formid = ".$formIdPost.";");
	}
	$jmlCopy = $_POST['jmlCopy']; // jumlah Copy
	
	if($jmlCopy != 0)
	{
		$i;
		for($i=1;$i<=$jmlCopy;$i++)
		{
			$CKoneksiSpj->mysqlQuery("INSERT INTO copy (formid, copycontent) VALUES ('".$formIdPost."', '".$_POST['copy'.$i]."');");
		}
	}
	
	//insert history
	$CHistory->updateLogSpj($userIdLogin, $tipeHist." Tembusan Form SPJ (formid = <b>".$formIdPost."</b>, owner form = <b>".$CSpj->detilForm($formIdPost, "ownername")."</b>, tujuan dinas = <b>".$dest."</b>)");
}
?>
<script>
$(document).ready(function() {
	doneWait();
	
    var tipe = $('#tipe').val();
	if(tipe == "edit")
	{
		ajaxCopyEdit();
	}
});

function submitCopy()
{
	var aksi = formCopy.aksi.value;
	var jmlCopy = formCopy.jmlCopy.value;
	
	var img = '<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;';
	
	if(jmlCopy != 0)
	{
		for(var i=1; i<=jmlCopy; i++)
		{
			if($('#copy'+i).val().replace(/ /g,"") == "") // Vehicle tidak boleh kosong
			{
				document.getElementById('errorMsg').innerHTML = img+ "Field "+i+" still empty!";
				$('#copy'+i).focus();
				return false;
			}
		}
	}
	
	var answer  = confirm("Are you sure want to save?");
	if(answer)
	{
		pleaseWait();
		formCopy.submit();
	}
	else
	{	return false;	}
}

function setup()
{
	var decimalSeparator = ".";
    var groupSeparator = ",";
		
	var numParser1 = new NumberParser(0, decimalSeparator, groupSeparator, true);
    var numMask1 = new NumberMask(numParser1, "jmlCopy");
}

function ajaxCopy(jml)
{
	$.post( 
		"../halPost.php",
		{	halaman: 'jmlCopy', jml: jml	},
		function(data){
			$('#divCopy').html(data);	
		}
	);
}

function ajaxCopyEdit()
{
	var formId = parent.$('#formId').val();
	var jmlCopy = $('#jmlCopy').val();
		
	$.post( 
		"../halPost.php",
		{	halaman: 'copy', formId: formId, jml: jmlCopy	},
		function(data){
			$('#divCopy').html(data);	
		}
	);
}
</script>
<body bgcolor="#F8F8F8">
<div id="loaderImg" style="visibility:visible;width:500px;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">
    	&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;
    </div>
</div>
<center>
<form action="" name="formCopy" id="formCopy" method="post" enctype="multipart/form-data">
<table cellpadding="0" cellspacing="0" border="0" width="99%" height="99%" align="center">
<tr valign="top" style="width:100%;">
	<td align="left">
    	<!--<span class="teksLvlFolder" style="color:#666;font-size:14px;"><b></b></span>-->
    </td>
	<td align="right">
    	<span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;" title="Tembusan SPJ"><b>:: Copy of SPJ ::</b></span>
    </td>
</tr>

<tr valign="top">
	<td colspan="2" class="spjTdMyFolder" bgcolor="#FFFFFF" valign="top" align="center">
    <div style="width:99%;height:190px;overflow:scroll;overflow-x: hidden;top: expression(offsetParent.scrollTop);">
    
    <table cellpadding="0" cellspacing="5" width="98%" class="spjFormInput" border="0">
                <tr><td height="5" colspan="3"></td></tr>
                <tr valign="top">
                	<td width="17%" height="28px" align="left" title="Rekan yang mendampingi dinas">Copy</td>
                    <td width="83%" align="left">
                    	<input type="text" class="elementDefault" id="jmlCopy" name="jmlCopy" style="width:5%;height:15px;" title="Tentukan jumlah Follower" onFocus="setup();" onKeyUp="setup();" value="<?php echo $jmlCopy;?>"/>
                        <button type="button" class="spjBtnStandar" style="width:50px;height:27px;" title="Create Row" onclick="<?php echo $btnSelect;?>">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                              <tr>
                                <td align="center">Select</td>
                              </tr>
                            </table>
                        </button>
                    </td>
                </tr>
                <tr>
                	<td colspan="2">
                    <div style="width:100%" id="divCopy">
                    <table cellpadding="0" cellspacing="5" width="100%" class="spjFormInput" border="0">
                        <tr valign="top">
                            <td width="16%" height="28px" align="left">&nbsp;</td>
                            <td width="84%" align="left">
                                <input type="text" class="elementDefault" style="width:99%;height:15px;" id="copy1" name="copy1" value=""/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <td height="28px" align="left">&nbsp;</td>
                            <td align="left">
                                <input type="text" class="elementDefault" style="width:99%;height:15px;" id="copy2" name="copy2" value=""/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <td height="28px" align="left">&nbsp;</td>
                            <td align="left">
                                <input type="text" class="elementDefault" style="width:99%;height:15px;" id="copy3" name="copy3" value=""/>
                            </td>
                        </tr>
                        
                    </table>
                    </div>
                    </td>
                <tr>
                	<td>
                    	<input type="hidden" id="formId" name="formId" value="<?php echo $formId;?>"/>
                    	<input type="hidden" id="aksi" name="aksi" value="add"/>
                    	<input type="hidden" id="tipe" name="tipe" value="<?php echo $tipe;?>"/>
                    </td>
                	<td align="left" valign="middle"></td>
                </tr>
		</table>
        
        </div>
    </td>
</tr>
<tr valign="top">
	<td colspan="2" bgcolor="#FFFFFF" height="35" valign="middle" align="center">
       &nbsp;<button type="button" class="spjBtnStandar" id="btnNewFolder" onclick="parent.close();" style="width:65px;height:25px;" title="Close Window">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr>
                    <td align="left"><img src="../picture/control-power.png" height="15"/> </td>
                    <td align="right">CLOSE</td>
                </tr>
                </table>
            </button>
       &nbsp;<button type="submit" class="spjBtnStandar" id="btnNewFolder" onclick="submitCopy(); return false;" style="width:60px;height:25px;" title="Save as draft form SPJ">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr>
                    <td align="left"><img src="../picture/disk.png" height="15"/> </td>
                    <td align="right">SAVE</td>
                </tr>
                </table>
            </button>
       &nbsp;
    </td>
</tr>
<tr valign="top">
	<td colspan="2" valign="middle" align="center">
    <span id="errorMsg" class="errorMsg"></span>
    </td>
</tr>
</table>
</form>
</center>
</body>
<script language="javascript">
<?php
if($aksiPost == "add")
{
	echo "parent.exit();
		  parent.reportTembusan();
		  parent.klikTr(parent.$('#trActive').val());";
}
?>
</script>
</HTML>	