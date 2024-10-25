<?php
require_once("../../config.php");

$foldSubGet = $_GET['foldSub'];
$idFoldRefGet = $_GET['idFoldRef'];
?>

<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript">
function pilihBtnSave()
{
	var lokasiFolder = document.getElementById('lokasiFolder').value;
	if(lokasiFolder.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').innerHTML = "Browse folder still empty!";
		return false;
	}
	
	var answer  = confirm("Are you sure want to save?");
	if(answer)
	{
		
		formUploadFolder.submit();
	}
	else
	{	return false;	}
}

function ambilFolder(lokasiFolder)
{
	var lokasiFolder = document.getElementById('lokasiFolder').value;
	if(lokasiFolder.replace(/ /g,"") != "")
	{
		try
		{
			var fso = new ActiveXObject("Scripting.FileSystemObject");
			var parentFolder = fso.GetFolder(lokasiFolder);
			
			var enumFolders = new Enumerator(parentFolder.SubFolders);
			var folderItem = "";
			for (; !enumFolders.atEnd(); enumFolders.moveNext())
			{
				folderItem += enumFolders.item()+"#*#*";
			}
			document.getElementById('allFolder').value = folderItem;
			return true;
		}
		catch(_err)
		{
			alert ("Failed to Populate Folder List:\n" + _err.description );	
			return false;
		}
	}
	
}

function ambilFile(lokasiFolder)
{
	try
	{
		var fso = new ActiveXObject("Scripting.FileSystemObject");
		var parentFolder = fso.GetFolder(lokasiFolder);

		var enumFiles = new Enumerator(parentFolder.files);
		var fileItem = "";
		for (; !enumFiles.atEnd(); enumFiles.moveNext())
		{
			fileItem += enumFiles.item()+"#*#*";
		}
		
		document.getElementById('allFile').value = fileItem;
		return true;
	}
	catch (_err)
	{
		alert ("Failed to Populate File List:\n" + _err.description );	
		return false;
	}
}

function getFolderPath()
{
	return showModalDialog("FolderDialog.html","","width:400px;height:400px;resizeable:yes;");
}

function ajaxGetFolder(aksi, idHalaman)
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

	if(aksi == "cekFolderAdaTidakDB_ExistsFold")
	{
		var idFoldRef = "<?php echo $idFoldRefGet; ?>";
		var allFolder = document.getElementById('allFolder').value.replace(/&/g,"%26");
		var parameters="halaman="+aksi+"&allFolder="+allFolder+"&idFoldRef="+idFoldRef;
	}

	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}
</script>


<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<?php
//echo $_SERVER['REMOTE_ADDR'];	
if($halamanPost == "simpanExistingFolder")
{
	$lokasiFolder = $_POST['lokasiFolder'];
	$allFolder = $_POST['allFolder'];
	
	$dateTime = $CPublic->dateTimeGabung();
	$tglbuat = $CPublic->indonesiaDate()." ".$CPublic->jamServer();
	
	$convFold = "N";
	if($idFoldRefGet != "")
	{
		$convFold = $CFolder->detilFoldByIdFold($idFoldRefGet, "convfold");
	}

	$explodeAllFolder = explode("#*#*",$allFolder);
	$pjgExplodeAllFolder = count($explodeAllFolder);
	for($i=0; $i<=$pjgExplodeAllFolder; $i++)
	{
		if($explodeAllFolder[$i]!="")
		{
			$folderTerakhir = cariFolderTerakhir($explodeAllFolder[$i]);
			$nmFold = $folderTerakhir;
			
			$idFoldLast = $CFolder->idFoldLast($foldSubGet, $idFoldRefGet);
			$fileFold = $userIdLogin."-".$idFoldLast."-".$dateTime;
			$contentType = "folder";
			$addUsrdt = $CPublic->userWhoAct();
			
			$cekNmFoldDB = cekNmFoldDB($CKoneksi, $userIdLogin, $idFoldRefGet, $folderTerakhir);
			if($cekNmFoldDB == "kosong") // jika nama folder tidak ada di database dan didalam folder yang sama maka bisa simpan
			{
				$CKoneksi->mysqlQuery("INSERT INTO tblfolder(idfold, idfoldref, foldsub, folderowner, namefold, filefold, descfold, tipekonten, tglbuat, addusrdt) VALUES ('".$idFoldLast."', '".$idFoldRefGet."',  '".$foldSubGet."', '".$userIdLogin."', '".$nmFold."', '".$fileFold."', '', '".$contentType."', '".$tglbuat."', '".$addUsrdt."');");
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
				
/*				if(is_dir($pathArchives."/data/document/LEVEL".$foldSubGet.""))
				{
					mkdir($pathArchives."/data/document/LEVEL".$foldSubGet."/".$fileFold, 0700);
				}
				else
				{
					mkdir($pathArchives."/data/document/LEVEL".$foldSubGet."", 0700);
					mkdir($pathArchives."/data/document/LEVEL".$foldSubGet."/".$fileFold, 0700);
				}*/
			}
		}
	}
}

function cariFolderTerakhir($pathFolder)
{
	$expPathFolder = explode("\\", $pathFolder);
	$pjgExpPathFolder = count($expPathFolder);
	for($i=0; $i<=($pjgExpPathFolder-1); $i++)
	{
		if($expPathFolder[$i]!="")
		{
			if($i == ($pjgExpPathFolder-1))
			{
				$folderTerakhir = $expPathFolder[$i];
			}
		}
	}
	return $folderTerakhir;
}
?>

<body bgcolor="#FFFFFF">
<center>
<table cellpadding="0" cellspacing="0" height="100%" width="55%" border="0" class="">
<!--<tr>
	<td align="center" height="40">
        <table cellpadding="0" cellspacing="0" width="70%">
        <tr>
            <td align="center"><span class="teksMyFolder">:: Create From Existing Folder  ::</span></td>
        </tr>
        </table>	
    </td>
</tr>-->
<tr>
	<td height="20">&nbsp;
    
	</td>
</tr>
<tr>
    <td align="center" valign="middle" height="80">
    <form action="halExistingFolder.php?foldSub=<?php echo $foldSubGet; ?>&idFoldRef=<?php echo $idFoldRefGet; ?>" method="post" enctype="multipart/form-data" id="formUploadFolder" name="formUploadFolder">
        <table cellpadding="0" cellspacing="5" width="100%" height="100%" class="formInput" border="0">
        <tr>
            <td colspan="2" valign="top" height="20">
            <input type="text" class="elementDefault" name="lokasiFolder" id="lokasiFolder" size="70" style="height:28px;" onFocus="ambilFolder(document.getElementById('lokasiFolder').value);ajaxGetFolder('cekFolderAdaTidakDB_ExistsFold', 'errorMsg');">&nbsp;
            
            <button type="button" onClick="lokasiFolder.value=getFolderPath();document.getElementById('lokasiFolder').focus();" style="height:28px;width:80px;background-color:#ECE9D8;border:#CCC 1px solid;cursor:pointer;position:absolute;" title="Choose Folder from LocalDisk">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" style="font-size:13px;cursor:pointer;color:#333;position:relative;">
                <tr>
                    <td align="center" valign="middle">Browse...</td>
                </tr>
                </table>
            </button>
            </td>
        </tr>
        <tr>
            <td width="22%" height="20">Content Type</td>
            <td width="78%">
                <input type="radio" name="contentType" id="contentType1" value="folder" checked/>&nbsp;Folder
            </td>
        </tr>
        <tr>
        	<td colspan="2" height="1">
            <input type="hidden" name="allFolder" id="allFolder" style="width:400px;">   
            <input type="hidden" name="halaman" id="halaman" value="simpanExistingFolder"> 
            <input type="hidden" name="idFoldRef" id="idFoldRef"> 
            </td>
        </tr>
        </table>
    </form>
    </td>
</tr>
<tr><td height="20" align="left" valign="middle" class=""><span id="errorMsg" class="errorMsg" style="margin-left:0px;">&nbsp;</span></td></tr>
<tr>
    <td align="center" height="70" valign="top">&nbsp;
       <button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" onClick="pilihBtnSave();" style="width:90px;height:55px;" title="Save Choosen Existing Folder">
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
<tr><td>&nbsp;</td></tr>
</table>

</center>
</body>

<?php
if($halamanPost == "simpanExistingFolder")
{ 
?>
<script type="text/javascript">
	parent.exitChild("no", "", <?php echo $foldSubGet; ?>, "", "", "", "closeNewFolder");
</script>
<?php
}

function cekNmFoldDB($CKoneksi, $folderOwner, $idFoldRef, $namaFold)
{
	$adaKosong = "";
	$query = $CKoneksi->mysqlQuery("SELECT namefold FROM tblfolder WHERE idfoldref='".$idFoldRef."' AND namefold='".$namaFold."' AND folderowner=".$folderOwner." AND deletests=0 LIMIT 1");	
	$row = $CKoneksi->mysqlFetch($query);
	
	if($row['namefold'] != "")
	{
		$adaKosong.= "ada";
	}
	else
	{
		$adaKosong.= "kosong";
	}
	
	return $adaKosong;
}
?>