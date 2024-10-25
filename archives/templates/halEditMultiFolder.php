<?php
require_once("../../config.php");

$foldSubGet = $_GET['foldSub'];
$idFoldRefGet = $_GET['idFoldRef'];
$halamanGet = $_GET['halaman'];

$allTrCheckedGet = $_GET['allTrChecked'];
$jmlTrCheckedGet = $_GET['jmlTrChecked'];

?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script language="JavaScript" src="../../js/masks.js"></script> 

<script type="text/javascript">
function pilihBtnSave()
{
	document.getElementById('errorMsg').innerHTML = "";
	
	var jmlFolder = document.getElementById('jmlFolder').value;
	for(var i = 1; i <= jmlFolder; i++)
	{
		if(document.getElementById('nmFold_'+i).value.replace(/ /g,"") == "")
		{
			document.getElementById('errorMsg').innerHTML = "Folder name Sequence "+i+" still empty!";
			return false;
		}
	}
	
	var answer  = confirm("Are you sure want to save?");
	if(answer)
	{
		formMultiFolder.submit();
	}
	else
	{	return false;	}
}

function klikBtnCreate(aksi, idHalaman)
{
	//ajaxGet
	document.getElementById('errorMsg').innerHTML = "";
	
	var mypostrequest=new ajaxRequest()
	mypostrequest.onreadystatechange=function()
	{
		if (mypostrequest.readyState==4)
		{
			if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1)
			{
				document.getElementById(idHalaman).innerHTML=mypostrequest.responseText;
			}
		}
	}
	
	if(aksi == "createRow")
	{
		var jmlFolder = document.getElementById('jmlFolder').value;
		var parameters="halaman="+aksi+"&jmlFolder="+jmlFolder;
	}
	
	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}

function onlyNumber(jmlFolderValue)
{
	var jmlFolder = document.getElementById('jmlFolder');
	if(jmlFolderValue > 10)
	{
		alert('Value must less than 10');
		jmlFolder.value = 3;
	}
	else
	{
		hpMask = new Mask("##", "number");
		hpMask.attach(jmlFolder);
	}
}

</script>

<?php

if($halamanPost == "simpanMultiFolder")
{
	//FUNGSI SIMPAN EDIT BEBERAPA FOLDER SEKALIGUS TIDAK JADI DILAKUKAN KARENA SARAN DARI PAK HENDRA MEMANG SEHARUSNYA TIDAK BISA DAN NAMA FOLDER TIDAK BOLEH SAMA;
	echo $halamanPost;
}
?>

<body bgcolor="#FFFFFF">
<center>

<table cellpadding="0" cellspacing="0" height="100%" width="55%" border="0">
<tr>
	<td align="center" height="40">
        <table cellpadding="0" cellspacing="0" width="60%">
        <tr>
            <td align="center"><span class="teksMyFolder">:: Edit Multiple Folder ::</span></td>
        </tr>
        </table>	
    </td>
</tr>
<tr><td height="20">&nbsp;</td></tr>
<tr>
	<td height="40" class="tabelBorderBottomJust" style="border-bottom-style:groove;border-bottom-width:5;">
    	<table cellpadding="0" cellspacing="5" width="100%" height="100%" class="formInput" border="0">
        <tr>
            <td valign="top">
                <input type="text" class="elementSearch" id="jmlFolder" maxlength="2" style=" width:45px; height:28px;text-align:center;" value="<?php echo $jmlTrCheckedGet; ?>" disabled>&nbsp;
                <button type="button" class="btnBrowse" style="height:28px;width:90px;" disabled>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" class="fontBtnBrowse" onMouseOver="this.className='fontBtnBrowseHover'" onMouseOut="this.className='fontBtnBrowse'">
                    <tr>
                        <td align="center" valign="middle">Create Row</td>
                    </tr>
                    </table>
                </button>
                &nbsp;
            </td>
        </tr>
        </table>
    </td>
</tr>
<form method="post" id="formMultiFolder" name="formMultiFolder" action="halNewMultiFolder.php?foldSub=<?php echo $foldSubGet; ?>&idFoldRef=<?php echo $idFoldRefGet; ?>" enctype="multipart/form-data">
<tr>
	<td valign="top" id="idHalRow">
    	<table cellpadding="0" cellspacing="0" width="100%">
        <?php
		$expAllTrCheckedGet = explode("-",$allTrCheckedGet);
		
		$html = "";
		for($i=1; $i<=$jmlTrCheckedGet; $i++)
		{
			$expIdCek = explode("_", $expAllTrCheckedGet[($i-1)]);
			$ideFold = $expIdCek[1];
			
			$nameFold = $CFolder->detilFold($ideFold, "namefold");
			$tipeKonten = $CFolder->detilFold($ideFold, "tipekonten");
			
			$cekFolder = "";
			$cekFile = "";
			if($tipeKonten == "folder")
			{
				$cekFolder = "checked";
			}
			if($tipeKonten == "file")
			{
				$cekFile = "checked";
			}
			
			$html.="<!-- URUTAN 1 ############### -->
					<tr>
						<td class=\"tabelBorderBottomJust\" style=\"border-style:dotted;\">
							<table cellpadding=\"0\" cellspacing=\"5\" width=\"100%\" class=\"formInput\" border=\"0\">
							<tr>
								<td class=\"tabelBorderAll\" width=\"10%\" rowspan=\"3\" align=\"center\" style=\"font-size:14px;color:#000080;font-weight:bold;font-family:Tahoma;\">".$i."</td>
								
							</tr>
							<tr valign=\"top\">
								<td rowspan=\"2\">&nbsp;</td>
								<td width=\"25%\">Name</td>
								<td width=\"65%\"><input type=\"text\" class=\"elementSearch\" id=\"nmFold_".$i."\" name=\"nmFold_".$i."\" size=\"40\" style=\"height:28px;\" value=\"".$nameFold."\"></td>
							</tr>
							<tr>
								<td>Content Type</td>
								<td height=\"20\">
									<input type=\"radio\" name=\"contentType_".$i."\" id=\"contentType\" value=\"folder\" ".$cekFolder."/>&nbsp;Folder&nbsp;&nbsp;
									<input type=\"radio\" name=\"contentType_".$i."\" id=\"contentType\" value=\"file\" ".$cekFile."/>&nbsp;File
								</td>
							</tr>
							</table>
						</td>
					</tr>
					<input type=\"hidden\" id=\"ideFold\" name=\"ideFold\" value=\"".$ideFold."\">
					";
		}
		echo $html;
		?>
        
        <input type="hidden" id="jmlFolder" name="jmlFolder" value="<?php echo $jmlTrCheckedGet; ?>">
        <input type="hidden" name="halaman" id="halaman" value="simpanMultiFolder"> 
        <input type="hidden" name="idFoldRef" id="idFoldRef"> 
        </table>   
    </td>
</tr>
</form>
<tr><td height="20" align="center" valign="middle" class="tabelBorderTopJust" style="border-top-style:groove;border-top-width:5;">&nbsp; <span id="errorMsg" class="errorMsg">&nbsp;</span></td></tr>
<tr>
    <td align="center" height="70">&nbsp;
       <button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" onClick="pilihBtnSave();" style="width:90px;height:55px;" title="Save Multi Folder">
          <table class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'" border="0" width="100%" height="100%">
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
</table>

</center>
</body>

<?php
if($halamanPost == "simpanMultiFolder")
{ 
?>
<script type="text/javascript">
	parent.exitChild("no", "", <?php echo $foldSubGet; ?>, "", "", "", "closeNewFolder");
</script>
<?php
}
?>