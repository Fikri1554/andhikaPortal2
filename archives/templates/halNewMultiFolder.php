<?php
require_once("../../config.php");

$foldSubGet = $_GET['foldSub'];
$idFoldRefGet = $_GET['idFoldRef'];
$halamanGet = $_GET['halaman'];

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
	
	var folderAdaTidakForm = document.getElementById('folderAdaTidakForm').value;
	var folderAdaTidakDB = document.getElementById('folderAdaTidakDB').value;
	
	if(folderAdaTidakForm == "ada")
	{
		document.getElementById('errorMsg').innerHTML = "Folder name Cant be same";
		return false;
	}
	if(folderAdaTidakDB == "ada")
	{
		document.getElementById('errorMsg').innerHTML = "Folder name already exists";
		return false;
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
		var idFoldRef = document.getElementById('idFoldRef').value;
		var parameters="halaman="+aksi+"&jmlFolder="+jmlFolder+"&idFoldRef="+idFoldRef;
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

function tulisNmFold()
{
	var jmlFolder = document.getElementById('jmlFolder').value;
	var nmFold = "";
	var allNmFold = "";
	
	for(var i=1; i<=jmlFolder; i++)
	{
		nmFold = document.getElementById('nmFold_'+i).value.replace(/&/g,"%26");
		if(nmFold != "")
		{
			allNmFold += nmFold+"#*#*";
		}
	}
	document.getElementById('allNmFold').value = allNmFold;
	
	
	var splitAllNmFold = allNmFold.split("#*#*");
	splitAllNmFold.sort();
	
	document.getElementById('folderAdaTidakForm').value = "kosong";
	
	var last = splitAllNmFold[0];
	for (var i=1; i < splitAllNmFold.length; i++) 
	{
	   if (splitAllNmFold[i] == last)
	   {
		   document.getElementById('folderAdaTidakForm').value = "ada";
	   }
	   last = splitAllNmFold[i];
	}
	
	ajaxGetFolder(allNmFold, "cekFolderAdaTidakDB", 'tdFolderAdaTidak');
}

function ajaxGetFolder(allNmFold, aksi, idHalaman)
{
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

	if(aksi == "cekFolderAdaTidakDB")
	{
		var idFoldRef = document.getElementById('idFoldRef').value;
		var parameters="halaman="+aksi+"&allNmFold="+allNmFold+"&idFoldRef="+idFoldRef;
	}

	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}

</script>

<?php
if($halamanPost == "simpanMultiFolder")
{
	$dateTime = $CPublic->dateTimeGabung();
	$tglbuat = $CPublic->indonesiaDate()." ".$CPublic->jamServer();
	
	$convFold = "N";
	if($idFoldRefGet != "")
	{
		$convFold = $CFolder->detilFoldByIdFold($idFoldRefGet, "convfold");
	}
	
	$jmlFolderPost = $_POST['jmlFolder'];
	for($i=1; $i<=$jmlFolderPost; $i++)
	{
		$nmFold = $_POST["nmFold_".$i.""];
		$contentType = $_POST["contentType_".$i.""];
		
		$idFoldLast = $CFolder->idFoldLast($foldSubGet, $idFoldRefGet);
		$fileFold = $userIdLogin."-".$idFoldLast."-".$dateTime;
		$addUsrdt = $CPublic->userWhoAct(); 

		$CKoneksi->mysqlQuery("INSERT INTO tblfolder(idfold, idfoldref, foldsub, folderowner, namefold, filefold, descfold, tipekonten, tglbuat, convfold, addusrdt) 
		VALUES ('".$idFoldLast."', '".$idFoldRefGet."',  '".$foldSubGet."', '".$userIdLogin."', '".$nmFold."', '".$fileFold."', '', '".$contentType."', '".$tglbuat."', '".$convFold."', '".$addUsrdt."');");
		$lastInsertId = mysql_insert_id();
		$CHistory->updateLog($userIdLogin, "Buat Folder baru (ide = <b>".$lastInsertId."</b>, nama folder = <b>".$nmFold."</b>)");
		
		if($convFold == "N")
		{
			$pathFolder = $pathArchives."/data/document/LEVEL".$foldSubGet;
		}
		if($convFold == "Y")
		{
			$pathFolder = $pathArchives."/data/documentConvFold/LEVEL".$foldSubGet;
		}
		
		if(is_dir($pathFolder))
		{
			mkdir($pathFolder."/".$fileFold, 0700);
		}
		else
		{
			mkdir($pathFolder, 0700);
			mkdir($pathFolder."/".$fileFold, 0700);
		}
	}
}
?>

<body bgcolor="#FFFFFF">
<center>
<input type="hidden" id="allNmFold">
<input type="hidden" id="tes">
<form method="post" id="formMultiFolder" name="formMultiFolder" action="halNewMultiFolder.php?foldSub=<?php echo $foldSubGet; ?>&idFoldRef=<?php echo $idFoldRefGet; ?>" enctype="multipart/form-data">
<table cellpadding="0" cellspacing="0" height="100%" width="55%" border="0">
<!--<tr>
	<td align="center" height="40">
        <table cellpadding="0" cellspacing="0" width="60%">
        <tr>
            <td align="center"><span class="teksMyFolder">:: Create New Several Folders ::</span></td>
        </tr>
        </table>	
    </td>
</tr>-->
<tr>
	<td height="20">
    	<table cellpadding="0" cellspacing="0">
        <tr>
        	<td>&nbsp;<input type="hidden" id="folderAdaTidakForm" name="folderAdaTidakForm" value="kosong"></td>
            <td id="tdFolderAdaTidak">&nbsp;<input type="hidden" id="folderAdaTidakDB" name="folderAdaTidakDB" value="kosong"></td>
        </tr>
        </table>
    </td>
</tr>

<tr>
	<td height="40" class="tabelBorderBottomJust" style="border-bottom-style:groove;border-bottom-width:5;">
    	<table cellpadding="0" cellspacing="5" width="100%" height="100%" class="formInput" border="0">
        <tr>
            <td valign="top">
                <input type="text" class="elementSearch" id="jmlFolder" maxlength="2" style=" width:45px; height:28px;text-align:center;" onFocus="onlyNumber(this.value);" onKeyUp="onlyNumber(this.value);" value="3">&nbsp;
                <button type="button" onClick="klikBtnCreate('createRow', 'idHalRow');" class="btnBrowse" onMouseOver="this.className='btnBrowseHover'" onMouseOut="this.className='btnBrowse'" style="height:28px;width:90px;" title="Create row with written number">
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
<tr>
	<td valign="top" id="idHalRow">
    	<table cellpadding="0" cellspacing="0" width="100%">
        
        
        <!-- URUTAN 1 ############### -->
        <tr>
        	<td class="tabelBorderBottomJust" style="border-style:dotted;">
                <table cellpadding="0" cellspacing="5" width="100%" class="formInput" border="0">
                <tr>
                    <td class="tabelBorderAll" width="10%" rowspan="3" align="center" style="font-size:14px;color:#000080;font-weight:bold;font-family:Tahoma;">1</td>
                    
                </tr>
                <tr valign="top">
                    <td rowspan="2">&nbsp;</td>
                    <td width="25%">Name</td>
                    <td width="65%"><input type="text" class="elementDefault" id="nmFold_1" name="nmFold_1" style="width:310px;height:28px;" onFocus="tulisNmFold();" onKeyUp="tulisNmFold();"></td>
                </tr>
                <tr>
                    <td>Content Type</td>
                    <td height="20">
                        <input type="radio" name="contentType_1" id="contentType" value="folder" checked/>&nbsp;Folder&nbsp;&nbsp;
                        <input type="radio" name="contentType_1" id="contentType" value="file"/>&nbsp;File
                    </td>
                </tr>
            	</table>
            </td>
        </tr>
        
        <!-- URUTAN 2 ############### -->
        <tr>
        	<td class="tabelBorderBottomJust" style="border-style:dotted;">
                <table cellpadding="0" cellspacing="5" width="100%" class="formInput" border="0">
                <tr>
                    <td class="tabelBorderAll" width="10%" rowspan="3" align="center" style="font-size:14px;color:#000080;font-weight:bold;font-family:Tahoma;">2</td>
                    
                </tr>
                <tr valign="top">
                    <td rowspan="2">&nbsp;</td>
                    <td width="25%">Name</td>
                    <td width="65%"><input type="text" class="elementDefault" id="nmFold_2" name="nmFold_2" style="width:310px;height:28px;" onFocus="tulisNmFold();" onKeyUp="tulisNmFold();"></td>
                </tr>
                <tr>
                    <td>Content Type</td>
                    <td height="20">
                        <input type="radio" name="contentType_2" id="contentType" value="folder" checked/>&nbsp;Folder&nbsp;&nbsp;
                        <input type="radio" name="contentType_2" id="contentType" value="file"/>&nbsp;File
                    </td>
                </tr>
            	</table>
            </td>
        </tr>
        
        <!-- URUTAN 3 ############### -->
        <tr>
        	<td class="tabelBorderBottomJust" style="border-style:dotted;">
                <table cellpadding="0" cellspacing="5" width="100%" class="formInput" border="0">
                <tr>
                    <td class="tabelBorderAll" width="10%" rowspan="3" align="center" style="font-size:14px;color:#000080;font-weight:bold;font-family:Tahoma;">3</td>
                    
                </tr>
                <tr valign="top">
                    <td rowspan="2">&nbsp;</td>
                    <td width="25%">Name</td>
                    <td width="65%"><input type="text" class="elementDefault" id="nmFold_3" name="nmFold_3" style="width:310px;height:28px;" onFocus="tulisNmFold();" onKeyUp="tulisNmFold();"></td>
                </tr>
                <tr>
                    <td>Content Type</td>
                    <td height="20">
                        <input type="radio" name="contentType_3" id="contentType" value="folder" checked/>&nbsp;Folder&nbsp;&nbsp;
                        <input type="radio" name="contentType_3" id="contentType" value="file"/>&nbsp;File
                    </td>
                </tr>
            	</table>
            </td>
        </tr>
        
        <input type="hidden" id="jmlFolder" name="jmlFolder" value="3">
        <input type="hidden" name="halaman" id="halaman" value="simpanMultiFolder"> 
        <input type="hidden" id="idFoldRef" name="idFoldRef" value="<?php echo $idFoldRefGet; ?>" /> 
        </table>   
    </td>
</tr>

<tr><td height="20" align="center" valign="middle" class="tabelBorderTopJust" style="border-top-style:groove;border-top-width:5;">&nbsp; <span id="errorMsg" class="errorMsg">&nbsp;</span></td></tr>
<tr>
    <td align="center" height="70">&nbsp;
       <button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" onClick="pilihBtnSave(); return false;" style="width:90px;height:55px;" title="Save a Create New Several Folders">
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

</form>

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