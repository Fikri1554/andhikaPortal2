<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/main.js"></script>
<?php
require_once('../../config.php');
$ideGet = $_GET['id'];
$dateTime = $CPublic->dateTimeGabung();
$fileExtension = $CFile->fileExtension();
//echo $ideGet;
$judul = "";
if($halamanGet == "PO")
{
	if($aksiGet == "new")
	{
		$judul = "New Policy File";
	}
	if($aksiGet == "edit")
	{
		$judul = "Edit Policy File";
	}
}
if($halamanGet == "PR")
{
	
	if($aksiGet == "new")
	{
		$judul = "New Procedure File";
	}
	if($aksiGet == "edit")
	{
		$judul = "Edit Procedure File";
	}
	
	if($aksiGet == "newForm")
	{
		$judul = "New Form File";
		$dir = "form/";
		
		$formProcedure = ucwords(strtolower($CEmpl->detilMstdoc($ideGet, "namedoc")));
	}
	if($aksiGet == "editForm")
	{
		$judul = "Edit Form File";
		$dir = "form/";
		
		$ideProcedure = $CEmpl->detilMstform($ideGet, "ide");
		$formProcedure = ucwords(strtolower($CEmpl->detilMstdoc($ideProcedure, "namedoc")));
		$nameDoc = $CEmpl->detilMstform($ideGet,"namedoc");
	}
}

if($halamanGet == "FO" && $aksiGet == "newForm")
{
	$judul = "New Form File";
	$dir = "form/";
	
	$formProcedure = "";
}

if($halamanGet == "FO" && $aksiGet == "editForm")
{
	$judul = "Edit Form File";
	$dir = "form/";
	
	$formProcedure = $formProcedure = ucwords(strtolower($CEmpl->detilMstdoc($ideGet, "namedoc")));
	
	$ideProcedure = $CEmpl->detilMstform($ideGet, "ide");
	$formProcedure = ucwords(strtolower($CEmpl->detilMstdoc($ideProcedure, "namedoc")));
	$nameDoc = $CEmpl->detilMstform($ideGet,"namedoc");
}

if($aksiGet == "edit")
{
	$nameDoc = $CEmpl->detilMstdoc($ideGet,"namedoc");
}

if($aksiPost == "new")
{
	$nmFile = mysql_real_escape_string( $_POST['nmFile'] );
	$filename = $_FILES['fileUpload']['name'];
    $source = $_FILES['fileUpload']['tmp_name'];
	$jenis = $_POST['halaman'];
	$jenisDoc = "Policy";
	if($jenis == "PR")
	{
		$jenisDoc = "Procedure";
	}
	
	$expNamaFile = explode(".", $_FILES["fileUpload"]["name"]);
	$tipeFile = end($expNamaFile);
	
	$name = $userIdLogin."-".$dateTime."-".$nmFile.".".$tipeFile;
		
	$dir = mysql_real_escape_string( $_POST['dir'] );
	
	$path = "../data/document/".$name;

	$queryMax = $CKoneksi->mysqlQuery("SELECT urutan FROM empldoc WHERE jenis='".$jenis."' ORDER BY urutan DESC LIMIT 1");
	$rowMax = $CKoneksi->mysqlFetch($queryMax);
	$urutanMax = $rowMax['urutan'];
	$urutan = $urutanMax+1;
		
	$CKoneksi->mysqlQuery("INSERT INTO empldoc (jenis, namedoc, filedoc, urutan, addusrdt) VALUES ('".$jenis."', '".$nmFile."', '".$name."', '".$urutan."', '".$CPublic->userWhoAct()."');");
	$lastInsertId = mysql_insert_id();
		
	move_uploaded_file($_FILES['fileUpload']['tmp_name'], $path);
	$CHistory->updateLogEmpl($userIdLogin, "Buat Dokumen ".$jenisDoc." baru (ide = <b>".$lastInsertId."</b>, nama file = <b>".$nmFile."</b>)");
}

if($aksiPost == "edit")
{
	$ide = mysql_real_escape_string( $_POST['ide'] );	
	$nmFile = mysql_real_escape_string( $_POST['nmFile'] );	
	$filename = $_FILES["fileUpload"]["name"];
	$source = $_FILES['fileUpload']['tmp_name'];
	$jenis = $_POST['halaman'];
	$jenisDoc = "Policy";
	if($jenis == "PR")
	{
		$jenisDoc = "Procedure";
	}
	//echo $nmFile." | ".$tipeFile." | ".$jenisDoc ;
	
	if($filename == "")
	{
		$CKoneksi->mysqlQuery("UPDATE empldoc SET namedoc='".$nmFile."', updusrdt='".$CPublic->userWhoAct()."' WHERE ide=".$ide." AND deletests=0;");
		$CHistory->updateLogEmpl($userIdLogin, "Edit Dokumen ".$jenisDoc." (ide = <b>".$ide."</b>");
	}
	if($filename != "")
	{
		$expNamaFile = explode(".", $_FILES["fileUpload"]["name"]);
		$tipeFile = end($expNamaFile);
		
		$nmFileBefore = $CEmpl->detilMstdoc($ide,"filedoc");
		$fileDoc = $userIdLogin."-".$dateTime."-".$nmFile.".".$tipeFile;
		
		$pathBefore = "../data/document/".$nmFileBefore;
		$pathDelete = "../data/documentDel//Up-".$nmFileBefore;
		$path = "../data/document/".$fileDoc;
		
		$CKoneksi->mysqlQuery("UPDATE empldoc SET namedoc='".$nmFile."', filedoc='".$fileDoc."', updusrdt='".$CPublic->userWhoAct()."' WHERE ide=".$ide." AND deletests=0;");	
		
		copy($pathBefore, $pathDelete); //kopikan file ke folder documentDel/
		move_uploaded_file($_FILES['fileUpload']['tmp_name'], $path);
		unlink($pathBefore); //delete file yang akan digantikan
		
		$CHistory->updateLogEmpl($userIdLogin, "Edit Dokumen ".$jenisDoc." (ide = <b>".$ide."</b>");
	}
}

if($aksiPost == "editForm")
{
	$ide = mysql_real_escape_string( $_POST['ide'] );	
	$nmFile = mysql_real_escape_string( $_POST['nmFile'] );	
	$fileUpload = $_FILES["fileUpload"]["name"];
	
	echo $ide." | ".$nmFile." | ".$fileUpload;
	if($fileUpload == "")
	{
		$CKoneksi->mysqlQuery("UPDATE emplform SET namedoc='".$nmFile."', updusrdt='".$CPublic->userWhoAct()."' WHERE idform=".$ide." AND deletests=0;");
		$CHistory->updateLogEmpl($userIdLogin, "Edit Form (idForm = <b>".$ide."</b>)");
	}
	if($fileUpload != "")
	{
		$expNamaFile = explode(".", $_FILES["fileUpload"]["name"]);
		$tipeFile = end($expNamaFile);
		$nmFileBefore = $CEmpl->detilMstform($ide,"filedoc");

		$fileDocNew = $userIdLogin."-".$dateTime."-".$nmFile.".".$tipeFile;
		
		$pathBefore = "../data/document/".$nmFileBefore;
		$pathDelete = "../data/documentDel//Up-".$nmFileBefore;
		$path = "../data/document/".$fileDocNew;
		
		copy($pathBefore, $pathDelete); //kopikan file ke folder documentDel/
		move_uploaded_file($_FILES['fileUpload']['tmp_name'], $path);
		unlink($pathBefore); //delete file yang akan digantikan
		
		$CKoneksi->mysqlQuery("UPDATE emplform SET namedoc='".$nmFile."', filedoc='".$fileDocNew."', extdoc='".$tipeFile."' ,updusrdt='".$CPublic->userWhoAct()."' WHERE idform=".$ide." AND deletests=0;");
		$CHistory->updateLogEmpl($userIdLogin, "Edit Form (idForm = <b>".$ide."</b>)");
	}
}

if($aksiPost == "newForm")
{
	$nmFile = mysql_real_escape_string( $_POST['nmFile'] );
	$ide = mysql_real_escape_string( $_POST['ide'] );
	if($ide == "")
	{
		$ide = $_POST['selectProc'];
	}
	$nmProc = $CEmpl->detilMstdoc($ide, "namedoc");

	$expNamaFile = explode(".", $_FILES["fileUpload"]["name"]);
	$tipeFile = end($expNamaFile);
	//echo $ide." |".$nmFile." | ".$tipeFile." | ".$nmProc." | ".$tipeFile;
	$fileDocNew = $userIdLogin."-".$dateTime."-".$ide."-".$nmFile.".".$tipeFile;
	
	$path = "../data/document/".$fileDocNew;
	
	$queryMax = $CKoneksi->mysqlQuery("SELECT urutan FROM emplform WHERE ide='".$ide."' AND deletests=0 ORDER BY urutan DESC LIMIT 1");
	$rowMax = $CKoneksi->mysqlFetch($queryMax);
	$urutanMax = $rowMax['urutan'];
	$urutan = $urutanMax+1;
	
	$queryUrutanLuarMax = $CKoneksi->mysqlQuery("SELECT urutanluar FROM emplform WHERE deletests=0 ORDER BY urutanluar DESC LIMIT 1");
	$rowUrutanLuarMax = $CKoneksi->mysqlFetch($queryUrutanLuarMax);
	$urutanLuarMax = $rowUrutanLuarMax['urutanluar'];
	$urutanLuar = $urutanLuarMax+1;
	
	$CKoneksi->mysqlQuery("INSERT INTO emplform (ide, urutan, urutanluar, namedoc, filedoc, extdoc, addusrdt) VALUES ('".$ide."', '".$urutan."', '".$urutanLuar."', '".$nmFile."', '".$fileDocNew."', '".$tipeFile."', '".$CPublic->userWhoAct()."');");
	$lastInsertId = mysql_insert_id();
	
	move_uploaded_file($_FILES['fileUpload']['tmp_name'], $path);
	
	$CHistory->updateLogEmpl($userIdLogin, "Buat Form baru (idForm = <b>".$lastInsertId."</b>, nama file = <b>".$nmFile."</b>)");
}
?>
<script>
function submitFile()
{
	var fileUpload = formFile.fileUpload.value;
	var tipeFile = fileUpload.split('.').pop();
	var fileExtension = "<?php echo $fileExtension; ?>";
	var adaFileExt = fileExtension.indexOf(tipeFile+",");
	var nmFileAwal = formFile.nmFileAwal.value.replace(/&/g,"%26");
	var nmFile = formFile.nmFile.value.replace(/&/g,"%26");
	var aksi = formFile.aksi.value;
	var halaman = formFile.halaman.value;
	
	
	if(aksi == "new" || aksi == "newForm")
	{
		if(fileUpload.replace(/ /g,"") == "") // file upload tidak boleh kosong
		{
			document.getElementById('errorMsg').innerHTML = "File upload still empty";
			document.getElementById('fileUpload').focus();
			return false;
		}
	}
	
	if(aksi == "new" || aksi == "edit" && fileUpload.replace(/ /g,"") != "")// file upload harus zip
	{
		if(tipeFile == "pdf" || tipeFile == "PDF")
		{
		}
		else
		{
			document.getElementById('errorMsg').innerHTML = "File upload must PDF";
			document.getElementById('fileUpload').focus();
			return false;
		}
	}
	
	if(aksi == "edit")
	{
		
	}
	
	if(aksi == "newForm" || aksi == "editForm" && fileUpload!="")
	{
		if(adaFileExt == -1)
		{
			document.getElementById('errorMsg').innerHTML = "File type not allowed!";
			document.getElementById('fileUpload').focus();
			return false;
		}
	}
	
	if(nmFile.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').innerHTML = "File Name still empty!";
		document.getElementById('nmFile').focus();
		return false;
	}
	
	/*if(nmFile.search(/\\|\/|:|\*|\?|"|<|>|\|/i) != "-1")
	{
		document.getElementById('errorMsg').innerHTML = "A file name cannot contain any of the following characters : \ / : * ? \" < > | ;";
		document.getElementById('nmFile').focus();
		return false;
	}*/
	
	var iChars = "*\\\;/|\":<>?";
	for (var i = 0; i < document.formFile.nmFile.value.length; i++) 
	{
    	if (iChars.indexOf(document.formFile.nmFile.value.charAt(i)) != -1) 
		{
    		document.getElementById('errorMsg').innerHTML = "A file name cannot contain any of the following characters : \ / : * ? \" < > | ;";
			document.getElementById('nmFile').focus();
    		return false;
        }
    }
	
	if(halaman == "FO" && aksi == "newForm")
	{
		var selectProc = document.getElementById('selectProc').value ;
		if(selectProc.replace(/ /g,"") == "0")
		{
			document.getElementById('errorMsg').innerHTML = "Related Procedure still not choosen!";
			document.getElementById('selectProc').focus();
			return false;
		}
	}
	
	var fileAdaTidak = document.getElementById('fileAdaTidak').value;
	if(fileAdaTidak == "ada") //jika file ada di database
	{
		if(nmFile != nmFileAwal) // jika file tidak sama dengan file awal dan sudah ada di database
		{
			document.getElementById('errorMsg').innerHTML = "File name already exists!";
			document.getElementById('nmFile').focus();
			return false;
		}
	}
	
	var answer  = confirm("Are you sure want to save?");
	if(answer)
	{
		formFile.submit();
	}
	else
	{	return false;	}
}

function ajaxGetFile(namaFile, aksi, idHalaman)
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
	
	if(aksi == "cekFileAdaTidak")
	{
		var ide = document.getElementById('ide').value;
		var halaman = document.getElementById('halaman').value;
		var aksiForm = document.getElementById('aksi').value;
		var namaFile = document.getElementById('nmFile').value.replace(/&/g,"%26");
		var parameters="halaman="+aksi+"&namaFile="+namaFile+"&jenis="+halaman+"&aksiForm="+aksiForm+"&ide="+ide;
	}
	
	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}

function pilihUpload()
{
	document.getElementById('errorMsg').innerHTML = "&nbsp;";
	
	var fileUpload = document.getElementById('fileUpload').value;
	//var namaFile = fileUpload.split('\\').pop().split('.').shift();//NAMA FILE TANPA EKSTENSION
	//document.getElementById('nmFile').value = namaFile
	
	var namaFile = fileUpload.split('\\').pop();
	document.getElementById('nmFile').value = namaFileCustom(namaFile);
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

function stopRKey(evt) {// Disabled Enter Key
  var evt = (evt) ? evt : ((event) ? event : null);
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
}
document.onkeypress = stopRKey; 
</script>
<body bgcolor="#F8F8F8">
<center>
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="99%" align="center">
<tr valign="top" style="width:100%;">
	<td align="left">
    	<span class="teksLvlFolder" style="color:#666;font-size:14px;"><b><?php echo $formProcedure;?></b></span>
    </td>
	<td align="right">
    	<span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;"><b>:: <?php echo $judul;?> ::</b></span>
    </td>
</tr>
<tr><td height="5" colspan="2"></td></tr>
<tr valign="top">
	<td colspan="2" class="tdMyFolder" bgcolor="#FFFFFF" height="437" valign="top" align="center">
    <form action="halNewFile.php" name="formFile" id="formFile" method="post" enctype="multipart/form-data">
    <table cellpadding="0" cellspacing="5" width="60%" height="100%" class="formInput" border="0">
                <tr><td height="10"></td></tr>
                <tr valign="top">
                    <td height="28" width="23%">File upload</td>
                    <td width="77%">
                        <input type="file" class="elementDefault" id="fileUpload" name="fileUpload" style="width:99%;height:28px;" title="Choose File from LocalDisk" onChange="pilihUpload();ajaxGetFile('', 'cekFileAdaTidak', 'tdFileAdaTidak');">
                    </td>
                </tr>
                <tr valign="top">
                    <td height="28">File Name</td>
                    <td>
        			<input type="text" class="elementDefault" id="nmFile" name="nmFile" style="width:99%;height:28px;" value="<?php echo $nameDoc;?>" onFocus="ajaxGetFile(this.value, 'cekFileAdaTidak', 'tdFileAdaTidak');" onKeyUp="ajaxGetFile(this.value, 'cekFileAdaTidak', 'tdFileAdaTidak');">
                    </td>
                </tr>
                <?php
					if($halamanGet == "FO" && $aksiGet == "newForm")
					{
				?> 
					<tr valign="top">
						<td height="28">Related Procedure</td>
						<td>
						<select id="selectProc" name="selectProc" class="elementMenu" style="width:99%;">
                        	<option value="0">-- PLEASE SELECT -- </option>
                <?php
					$sql = $CKoneksi->mysqlQuery("SELECT * FROM empldoc WHERE jenis='PR' AND deletests=0 ORDER BY urutan ASC");
					while($r = $CKoneksi->mysqlFetch($sql))
					{
						echo "<option value=\"".$r['ide']."\">".$r['namedoc']."</option>";
					}
				?>
						</select>
						</td>
					</tr>
                <?php }?>
                <tr>
                	<td colspan="2" height="20" align="center" valign="middle">&nbsp; <span id="errorMsg" class="errorMsg">&nbsp;</span></td>
                </tr>
                <tr>
                    <td>
                    	<input type="hidden" id="nmFileAwal" name="nmFileAwal" value="<?php echo $nameDoc; ?>" />
                    	<input type="hidden" id="halaman" name="halaman" value="<?php echo $halamanGet;?>"/>
                    	<input type="hidden" id="aksi" name="aksi" value="<?php echo $aksiGet;?>"/>
                        <input type="hidden" id="dir" name="dir" value="<?php echo $dir;?>"/>
                        <input type="hidden" id="ide" name="ide" value="<?php echo $ideGet?>"/>
                    </td>
                    <td id="tdFileAdaTidak">&nbsp;<input type="hidden" id="fileAdaTidak" name="fileAdaTidak" value="kosong"></td>
                </tr>
		</table>
        </form>
    </td>
</tr>
<tr><td height="5"></td></tr>
<tr valign="top">
	<td colspan="2" class="tdMyFolder" bgcolor="#FFFFFF" height="65" valign="middle">
       &nbsp;<button onClick="parent.tutup();" class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" style="width:80px;height:55px;" title="Close Window">
       	<table width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
            <tr>
                <td align="center"><img src="../../picture/Metro-Shut-Down-Blue-32.png" height="25"/> </td> 
            </tr>
            <tr>
                <td align="center">CLOSE</td>
            </tr>
            </table>
       </button>
       &nbsp;<button onClick="submitFile(); return false;" class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" style="width:80px;height:55px;" title="Save Data">
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
</table>

</center>
</body>
<?php
if($aksiPost == "new" || $aksiPost == "newForm" ||$aksiPost == "edit" || $aksiPost == "editForm")
{
	$jenisDoc = "Policy";
	if($halamanGet == "PR")
	{
		$jenisDoc = "Procedure";
	}
	if($aksiPost == "new")
	{
		//$report = "*".$jenisDoc." \"".$nmFile."\" Successfully Added.";
		$report = "Document Successfully Added.";
	}
	if($aksiPost == "newForm")
	{
		//$report = "*Form \"".$nmFile."\" of ".$nmProc." Successfully Added.";
		$report = "Form Successfully Added.";
	}
	if($aksiPost == "edit")
	{
		//$report = "*".$jenisDoc." \"".$nmFile."\" Successfully Edit.";
		$report = "Document Successfully Edit.";
	}
	if($aksiPost == "editForm")
	{
		//$report = "*Form \"".$nmFile."\" of ".$nmProc." Successfully Edit.";
		$report = "Form Successfully Edit.";
	}
?>
	<script language="javascript">
		parent.exitDoc();
		parent.refreshRightFrame();
		parent.report('<?php echo $report;?>');
	</script>	
<?php
}
?>