<?php 
require_once("../configSafir.php"); 
require_once("../class/clsencrypt.php");

$aksiGet = $_GET['aksi'];

$tutupWindow = "N";
if($aksiGet == "mulaiTranslate")
{
	//CEK EROR DAN CEK JIKA FILE SUDAH DIUPLOAD TANPA DISIMPAN DI SERVER DI TEMPORARY 
	if ($_FILES['fileUpload']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['fileUpload']['tmp_name'])) 
	{ 
		$isiTeks = file_get_contents($_FILES['fileUpload']['tmp_name']); 
		
		$enc = new Encryption;
		$encTeks = $enc->decrypt("andhika", $isiTeks);
		
		$statusAccept = "kosong";
//MENCARI IDDATA YANG SUDAH CLOSE, BERGUNA KETIKA IMPORT DARI BOARD MAKA LAPORAN YANG DI HO YANG DATA NYA AKAN DI REPLACE TERNYATA SUDAH CLOSE MAKA TIDAK BERUBAH
		$queryClosed = $CKoneksiSaf->mysqlQuery("SELECT iddata, namakapal, hdsn FROM datalaporan WHERE accept='Y' OR closed='Y' AND deletests=0;");
		while($rowClosed = $CKoneksiSaf->mysqlFetch($queryClosed))
		{
			$idDataClosed = $rowClosed['iddata'];
			$nmKapalClosed = $rowClosed['namakapal'];
			$hdsnClosed = $rowClosed['hdsn'];
			$stringMatch = "DELETE FROM datalaporan WHERE iddata='".$idDataClosed."' AND namakapal='".$nmKapalClosed."' AND hdsn='".$hdsnClosed."' LIMIT 1;";
			if(strpos($encTeks, $stringMatch) !== false)
				$statusAccept = "ada";
		}
		
		if($statusAccept == "ada")
		{
			$bgColorErr = "background-color:#FFE1E1;";
			$errorMsg = "<img src=\"../picture/exclamation-red.png\"/>&nbsp;This report has been Accept or Closed!";
		}
		if($statusAccept == "kosong")
		{
			$expEncTeks = explode("#-#-", $encTeks);
			for($i=0; $i<( count($expEncTeks)-1) ; $i++)
			{
				$CKoneksiSaf->mysqlQuery( str_replace("#n#n","\n",str_replace("#r#r","\r",$expEncTeks[$i])) );
				//echo str_replace("#n#n","\n",str_replace("#r#r","\r",$expEncTeks[$i]))."<br>";
			}
			
			$CKoneksiSaf->mysqlQuery("UPDATE datalaporan SET lastimport='".$CPublic->tglServer()."/".$CPublic->jamServer()."' WHERE iddata = '".$lastInsertIdData."' AND deletests=0 LIMIT 1;");
			$CHistory->updateLog2($userIdLogin, "Import Data Laporan (iddata = <b>".$idDataClosed."</b>, namakapal=<b>".$nmKapalClosed."</b>, hdsn=<b>".$hdsnClosed."</b>)");
	
			$tutupWindow = "Y";
		}
	}
}
?>

<script type="text/javascript" src="../js/jquery-1.4.3.js"></script>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css">
<link href="../css/button.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function tutup()
{
	parent.tb_remove(false);
	parent.setelahTutup();
	
}

function translate()
{
	document.getElementById('errorMsg').innerHTML = "";
	var fileUpload = document.getElementById('fileUpload').value;
	var tipeFile = fileUpload.split('.').pop();
	var fileExtension = "saf,";
	var adaFileExt = fileExtension.indexOf(tipeFile+",");
	
	
	if(fileUpload.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').style.backgroundColor = "#FFE1E1";
		document.getElementById('errorMsg').innerHTML = "<img src=\"../picture/exclamation-red.png\"/>&nbsp;File Upload still empty!";
		document.getElementById('fileUpload').focus(); 
		return false;
	}
	if(adaFileExt == -1)
	{
		document.getElementById('errorMsg').style.backgroundColor = "#FFE1E1";
		document.getElementById('errorMsg').innerHTML = "<img src=\"../picture/exclamation-red.png\"/>&nbsp;File type not allowed!";
		document.getElementById('fileUpload').focus(); 
		return false;
	}
	else
	{
		var answer  = confirm("Are you sure want to continue?");
		if(answer)
		{
			pleaseWait();
			document.onmousedown=disableLeftClick;
			
			formImport.submit();
		}
		else
		{
			return false;
		}
	}
}

function disableLeftClick()
{
	if (event.button==1) 
	{
		alert('Please Wait...')
		return false;
	}
}

function pilihUpload()
{
	document.getElementById('errorMsg').style.backgroundColor = "#FFFFFF";
	document.getElementById('errorMsg').innerHTML = "";
	
	var fileUpload = document.getElementById('fileUpload').value;
	var namaFile = fileUpload.split('\\').pop();
	document.getElementById('spanFileName').innerHTML = namaFile;
}

function pleaseWait()
{
	document.getElementById('loaderImg').style.visibility = "visible";
}

function doneWait()
{
	document.getElementById('loaderImg').style.visibility = "hidden";
}

function blinker() {
    $('.errorMsg').fadeOut(250);
    $('.errorMsg').fadeIn(500);
}
setInterval(blinker, 1500);
</script>

<style>
.pleaseWait
{
	position:absolute;
	width:556px;
	margin:0 auto;
}

.isiPleaseWait
{
	
	width:200px;
	padding: 4px;

	color:#333;
	font-family:Arial;
	font-weight:bold;
	font-size:12px;
	height:25px;
	background-color:#F4FBF4;
	
	-webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}
</style>

<div class="" style="position:relative;width:556px;margin:0 auto;">

	<div id="loaderImg" style="visibility:hidden;" class="pleaseWait">
    	<center>
    	<div class="isiPleaseWait tabelBorderAll">&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;</div>
        </center>
    </div>
   
</div>
 
<table cellpadding="0" cellspacing="0" width="<?php echo ($_GET['panjang']-6); ?>" height="<?php echo ($_GET['tinggi']-38); ?>">
<tr>
  	<td height="30" align="center" style="background-color:#666;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:14px;">&nbsp;:: IMPORT DATA ::&nbsp;</td>
</tr>
<tr><td height="10"></td></tr>
<form method="post" action="halImport.php?aksi=mulaiTranslate" enctype="multipart/form-data" id="formImport" name="formImport">
<tr>
	<td valign="top" height="100">
    	<fieldset>
            <legend style="font:11px Arial;color: #006;font-weight:bold;background-color:#FFF;">&nbsp;&nbsp;&nbsp;GENERAL INFORMATION&nbsp;&nbsp;&nbsp;</legend>
            <table width="100%" height="100%" bgcolor="#F0FFF0" border="0" class="">
            <tr><td colspan="2">&nbsp;</td></tr> 
            <tr valign="top">
                <td width="111" class="boldPersonal">File Upload</td>
                <td width="423">
                	 <input type="file" class="elementTypeFile" name="fileUpload" id="fileUpload" style="width:420px;" onChange="pilihUpload();" title="Choose File from LocalDisk">
                </td>
            </tr>
            <tr valign="middle">
                <td class="boldPersonal">File Name</td>
                <td>
                <span id="spanFileName" class="styeSpanText">
                <?php
				if(isset($_FILES['fileUpload']['name']))
				{
					echo $_FILES['fileUpload']['name'];
				}
				?>
                </span></td>
            </tr> 
            <tr>
            	<td colspan="2">&nbsp;</td>
            </tr> 
            </table>
        </fieldset>
	</td>
</tr>
<input type="hidden" id="halaman" name="halaman" value="<?php echo $halaman; ?>" />
</form>
<tr><td height="5"></td></tr>
<tr>
	<td height="25" valign="bottom" class="tabelBorderAll errorMsg" id="errorMsg" style="border-style:dashed;border-color:#FF9B9B;<?php echo $bgColorErr; ?>"><?php echo $errorMsg; ?></td>
</tr>

<tr><td height="5"></td></tr>
<tr>
	<td height="40">
    	<button class="btnStandar" onMouseOver="this.className='btnStandarHover'" onMouseOut="this.className='btnStandar'" style="width:64px;height:30px;" title="CLOSE" onclick="tutup();return false;">
              <table class="fontBtn" onMouseOver="this.className='fontBtnHover'" onMouseOut="this.className='fontBtn'" cellpadding="0" cellspacing="0" width="100%" height="26">
              <tr valign="middle">
                  <td align="center" width="22"><img src="../picture/door-open-out.png"/> </td>
                  <td align="left">CLOSE</td> 
              </tr>
              </table>
          </button>&nbsp;
          <button class="btnStandar" onMouseOver="this.className='btnStandarHover'" onMouseOut="this.className='btnStandar'" style="width:87px;height:30px;" title="TRANSLATE FILE IMPORT" onclick="translate();return false;">
              <table class="fontBtn" onMouseOver="this.className='fontBtnHover'" onMouseOut="this.className='fontBtn'" cellpadding="0" cellspacing="0" width="100%" height="26">
              <tr valign="middle">
                  <td align="center" width="22"><img src="../picture/compile.png"/> </td>
                  <td align="left">TRANSLATE</td> 
              </tr>
              </table>
          </button>
    </td>
</tr>
<tr><td></td></tr>
<tr><td height="10"></td></tr>
</table>

<script>
<?php
if($tutupWindow == "Y")
{
	echo "tutup();";
}
?>
</script>