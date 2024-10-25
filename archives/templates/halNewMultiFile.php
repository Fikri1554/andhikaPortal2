<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$foldSubGet = $_GET['foldSub'];
$idFoldGet = $_GET['idFold'];
$halamanGet = $_GET['halaman'];
$fileExtension = $CFile->fileExtension();
$fileExtensionNValid = $CFile->fileExtensionNValid();

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
	
	
	var jmlFile = document.getElementById('jmlFile').value;
	for(var i = 1; i <= jmlFile; i++)
	{
		if(document.getElementById('fileUpload_'+i).value.replace(/ /g,"") == "")
		{
			document.getElementById('errorMsg').innerHTML = "File upload Sequence "+i+" still empty!";
			return false;
		}
		if(document.getElementById('nmFile_'+i).value.replace(/ /g,"") == "")
		{
			document.getElementById('errorMsg').innerHTML = "File name Sequence "+i+" still empty!";
			return false;
		}
	}
	
	for(var a = 1; a <= jmlFile; a++)
	{
		var fileUpload = document.getElementById('fileUpload_'+a).value;
		var fileUpload2 = document.getElementById('fileUpload_'+a);
		var tipeFile = fileUpload.split('.').pop();
		
		var fileExtension = "<?php echo $fileExtension; ?>";
		var adaFileExt = fileExtension.indexOf(tipeFile+",");
		
		var fileExtensionNValid = "<?php echo $fileExtensionNValid; ?>";
		var adaFileExtNValid = fileExtensionNValid.indexOf(tipeFile+",");
		
		if(adaFileExt == -1)
		{
			if(adaFileExtNValid == -1)
			{
				document.getElementById('errorMsg').innerHTML = "File type Sequence "+a+" not allowed!";
				return false;
			}
			else
			{
				var answer  = confirm("File type not allowed \n\rAre you sure want to continue ?");
				if(answer)
				{
				}
				else
				{
					return false;
				}
			}
		}
	}
	
	var fileAdaTidakForm = document.getElementById('fileAdaTidakForm').value;
	var fileAdaTidakDB = document.getElementById('fileAdaTidakDB').value;
	
	if(fileAdaTidakForm == "ada")
	{
		document.getElementById('errorMsg').innerHTML = "File name Cant be same";
		return false;
	}
	if(fileAdaTidakDB == "ada")
	{
		document.getElementById('errorMsg').innerHTML = "File name already exists";
		return false;
	}
	
	var answer  = confirm("Are you sure want to save?");
	if(answer)
	{
		formMultiFile.submit();
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
	
	if(aksi == "createRowFile")
	{
		var idFold = document.getElementById('idFold').value;
		var jmlFile = document.getElementById('jmlFile').value;
		var parameters="halaman="+aksi+"&jmlFile="+jmlFile+"&idFold="+idFold;
	}
	
	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}

function onlyNumber(jmlFileValue)
{
	var jmlFile = document.getElementById('jmlFile');
	if(jmlFileValue > 10)
	{
		alert('Value must less than 10');
		jmlFile.value = 3;
	}
	else
	{
		hpMask = new Mask("##", "number");
		hpMask.attach(jmlFile);
	}
}

function pilihUpload(urutan)
{
	var fileUpload = document.getElementById('fileUpload_'+urutan).value;
	//var namaFile = fileUpload.split('\\').pop(); //NAMA FILE BESERTA EKSTENSION
	//var namaFile = fileUpload.split('\\').pop().split('.').shift();//NAMA FILE TANPA EKSTENSION
	/*if(document.getElementById('nmFile_'+urutan).value == "")
	{
		document.getElementById('nmFile_'+urutan).value = namaFile
	}*/
	//document.getElementById('nmFile_'+urutan).value = namaFile
	
	var namaFile = fileUpload.split('\\').pop();
	document.getElementById('nmFile_'+urutan).value = namaFileCustom(namaFile);

	cekNmFile();
	//ajaxGetFile(namaFile, "cekFileAdaTidak", 'tdFileAdaTidak');
}

function namaFileCustom(namaFileParam)
{
	var nilai = '';
	var namaFile = namaFileParam.split('.');
	
	
	for(i = 0; i<=(namaFile.length-2); i++ )
	{
		if(i == 0)
		{
			nilai += namaFile[i];
		}
		else
		{
			nilai += "."+namaFile[i];
		}
	}
	
	return nilai;
}

function cekNmFile()
{
	var jmlFile = document.getElementById('jmlFile').value;
	var nmFile = "";
	var allNmFile = "";
	
	for(var i=1; i<=jmlFile; i++)
	{
		nmFile = document.getElementById('nmFile_'+i).value.replace(/&/g,"%26");
		if(nmFile != "")
		{
			allNmFile += nmFile+"#*#*";
		}
	}
	document.getElementById('allNmFile').value = allNmFile;
	
	var splitAllNmFile = allNmFile.split("#*#*");
	splitAllNmFile.sort();
	
	document.getElementById('fileAdaTidakForm').value = "kosong";
	
	var last = splitAllNmFile[0];

	for (var a = 1; a < splitAllNmFile.length; a++)  // CEK JIKA DALAM SESAMA FORM FILE ADA NAMA FILE YANG SAMA
	{
	   if (splitAllNmFile[a] == last)
	   {
		   document.getElementById('fileAdaTidakForm').value = "ada";
	   }
	   last = splitAllNmFile[a];
	}
	
	ajaxGetFile(allNmFile, "cekFileAdaTidakDB", 'tdFileAdaTidak');
}

function ajaxGetFile(allNmFile, aksi, idHalaman)
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

	if(aksi == "cekFileAdaTidakDB")
	{
		var idFold = document.getElementById('idFold').value;
		var parameters="halaman="+aksi+"&allNmFile="+allNmFile+"&idFold="+idFold;
	}

	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}


</script>

<?php

if($halamanPost == "simpanMultiFile")
{
	$dateTime = $CPublic->dateTimeGabung();
	$wktUpload = $CPublic->waktuSek();

	$jmlFilePost = $_POST['jmlFile'];
	for($i=1; $i<=$jmlFilePost; $i++)
	{
		$nmFile = mysql_real_escape_string( $_POST["nmFile_".$i.""] );	
		$expNamaFile = explode(".", $_FILES["fileUpload_".$i.""]["name"]);
		$tipeFile = end($expNamaFile);
		
		$idDocLast = $CFile->idDocLast($idFoldGet);
		$fileDocNew = $userIdLogin."-".$idDocLast."-".$idFoldGet."-".$dateTime."-".$userIdLogin.".".$tipeFile;
		$convFold = $CFolder->detilFoldByIdFold($idFoldGet, "convfold");
		
		$CKoneksi->mysqlQuery("INSERT INTO mstdoc (iddoc, idfold, fileowner, namedoc, filedoc, extdoc, wktupload, lastuploadbyid, lastuploadbyname, descdoc, convfold, addusrdt)VALUES ('".$idDocLast."', '".$idFoldGet."', '".$userIdLogin."', '".$nmFile."', '".$fileDocNew."', '".$tipeFile."', '".$wktUpload."', '".$userIdLogin."', '".$userFullnm."', '".$descFile."', '".$convFold."', '".$CPublic->userWhoAct()."');");
		$lastInsertId = mysql_insert_id();
		
		$foldSub = $CFolder->detilFoldByIdFold($idFoldGet, "foldsub"); // atau level
		$fileFolder = $CFolder->detilFoldByIdFold($idFoldGet, "filefold");
		
		if($convFold == "N")
		{
			$pathFilePaste = $pathArchives."data/document/LEVEL".$foldSub."/".$fileFolder."/".$fileDocNew;
		}
		if($convFold == "Y")
		{
			$pathFilePaste = $pathArchives."data/documentConvFold/LEVEL".$foldSub."/".$fileFolder."/".$fileDocNew;
		}
		
		move_uploaded_file($_FILES["fileUpload_".$i.""]["tmp_name"], $pathFilePaste);
	
		$CHistory->updateLog($userIdLogin, "Buat File baru (ide = <b>".$lastInsertId."</b>, nama file = <b>".$nmFile."</b>)");
	}
}

?>

<body bgcolor="#FFFFFF">
<center>
<input type="hidden" id="allNmFile" style="width:300px;">
<input type="hidden" id="fileAdaTidakForm" name="fileAdaTidakForm" value="kosong" style="width:300px;">
<form method="post" id="formMultiFile" name="formMultiFile" action="halNewMultiFile.php?foldSub=<?php echo $foldSubGet; ?>&idFold=<?php echo $idFoldGet; ?>" enctype="multipart/form-data">
<table cellpadding="0" cellspacing="0" height="100%" width="55%" border="0">
<!--<tr>
	<td align="center" height="35">
        <table cellpadding="0" cellspacing="0" width="60%">
        <tr>
            <td align="center"><span class="teksMyFolder">:: Create New Several File ::</span></td>
        </tr>
        </table>	
    </td>
</tr>-->
<tr>
	<td height="40" class="tabelBorderBottomJust" style="border-bottom-style:groove;border-bottom-width:5;">
    	<table cellpadding="0" cellspacing="5" width="100%" height="100%" class="formInput" border="0">
        <tr align="left">
            <td valign="top" width="50%">
                <input type="text" class="elementSearch" id="jmlFile" maxlength="2" style=" width:25px; height:17px;text-align:center;" onFocus="onlyNumber(this.value);" onKeyUp="onlyNumber(this.value);" value="3">&nbsp;
                <button type="button" onClick="klikBtnCreate('createRowFile', 'idHalRow');" class="btnBrowse"style="height:28px;width:90px;" title="Create row with written number">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                    <tr>
                        <td align="center" valign="middle">Create Row</td>
                    </tr>
                    </table>
                </button>
                &nbsp;
            </td>
            <td width="50%" id="tdFileAdaTidak">&nbsp;<input type="hidden" id="fileAdaTidakDB" name="fileAdaTidakDB" value="kosong"></td>
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
                <tr align="left">
                	<td rowspan="2">&nbsp;</td>
                    <td>File upload</td>
                    <td height="20">
                        <input type="file" class="elementDefault" name="fileUpload_1" id="fileUpload_1" style="width:320px;" onChange="pilihUpload('1');" title="Choose File from LocalDisk">
                    </td>
                </tr>
                <tr valign="top">
                    
                    <td width="25%" align="left">Name</td>
                    <td width="65%">
                        <input type="text" class="elementDefault" id="nmFile_1" name="nmFile_1" style="width:310px;height:17px;" onFocus="cekNmFile();" onKeyUp="cekNmFile();">
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
                <tr align="left">
                	<td rowspan="2">&nbsp;</td>
                    <td>File upload</td>
                    <td height="20">
                        <input type="file" class="elementDefault" name="fileUpload_2" id="fileUpload_2" style="width:320px;" onChange="pilihUpload('2');" title="Choose File from LocalDisk">
                    </td>
                </tr>
                <tr valign="top" align="left">
                    
                    <td width="25%">Name</td>
                    <td width="65%">
                    	<input type="text" class="elementDefault" id="nmFile_2" name="nmFile_2" style="width:310px;height:28px;" onFocus="cekNmFile();" onKeyUp="cekNmFile();">
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
                <tr align="left">
                	<td rowspan="2">&nbsp;</td>
                    <td>File upload</td>
                    <td height="20">
                        <input type="file" class="elementDefault" name="fileUpload_3" id="fileUpload_3" style="width:320px;" onChange="pilihUpload('3');" title="Choose File from LocalDisk">
                    </td>
                </tr>
                <tr valign="top" align="left">
                    
                    <td width="25%">Name</td>
                    <td width="65%">
                    	<input type="text" class="elementDefault" id="nmFile_3" name="nmFile_3" style="width:310px;height:28px;" onFocus="cekNmFile();" onKeyUp="cekNmFile();">
                    </td>
                </tr>
                
            	</table>
            </td>
        </tr>
        
        <input type="hidden" id="jmlFile" name="jmlFile" value="3">
        <input type="hidden" name="halaman" id="halaman" value="simpanMultiFile"> 
        <input type="hidden" name="idFold" id="idFold" value="<?php echo $idFoldGet; ?>"> 
        </table>
    </td>
</tr>




<tr><td height="20" align="center" valign="middle" class="tabelBorderTopJust" style="border-top-style:groove;border-top-width:5;">&nbsp; <span id="errorMsg" class="errorMsg">&nbsp;</span></td></tr>
<tr>
    <td align="center" height="60">&nbsp;
       <button type="submit" class="btnStandarKecil" onClick="pilihBtnSave();return false;" style="width:90px;height:55px;" title="Save Choosen Several File">
            <table border="0" width="100%" height="100%">
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
if($halamanPost == "simpanMultiFile")
{
	?>
	<script language="javascript">
		parent.exit();
	</script>	
<?php
}
?>
</HTML>