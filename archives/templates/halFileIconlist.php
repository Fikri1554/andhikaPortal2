<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$pathFolder="../data/document/";
$pathFolderConvFold="../data/documentConvFold/";
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="../../js/jquery.min.js"></script>

<script type="text/javascript" src="../../js/animatedcollapse.js"></script>
<script>
function dobelKlik(pdfYes, linkPdf, hrefOpenFile)
{
	var jmlFile = document.getElementById('jmlFile').value;
	for(var i = 1; i <= jmlFile; i++)
	{
		animatedcollapse.hide('setFile'+i);
		document.getElementById('statHidden'+i).value = "N";
	}
	
	if(pdfYes == "Y")
	{
		parent.openPdf(linkPdf);
	}
	else if(pdfYes == "N")
	{
		document.getElementById(hrefOpenFile).click();
	}
}

function showCollapse(urutan)
{
	//setTimeout(function()
	//{
		var jmlFile = document.getElementById('jmlFile').value;
		for(var i = 1; i <= jmlFile; i++)
		{
			if(urutan != i)
			{
				animatedcollapse.hide('setFile'+i);
				document.getElementById('statHidden'+i).value = "Y";
			}
		}
		
		var statHiddenSel = document.getElementById('statHidden'+urutan).value;
		
		if(statHiddenSel == "Y")
		{
			animatedcollapse.show('setFile'+urutan);
			document.getElementById('statHidden'+urutan).value = "N";
			return false;
		}
		else if(statHiddenSel == "N")
		{
			animatedcollapse.hide('setFile'+urutan);
			document.getElementById('statHidden'+urutan).value = "Y";
			return false;
		}
	//},300);
}

function allHidden()
{
	var jmlFile = document.getElementById('jmlFile').value;
	for(var i = 1; i <= jmlFile; i++)
	{
		animatedcollapse.hide('setFile'+i);
		document.getElementById('statHidden'+i).value = "Y";
	}
}

animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
	//$: Access to jQuery
	//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
	//state: "block" or "none", depending on state
}
animatedcollapse.init();

</script>

<style>

</style>

<body onClick="allHidden();" onLoad="loadScroll('fileIconList');" onUnload="saveScroll('fileIconList');"> 
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
	<td width="100%" height="100%" style="">
<?php
$aksiGet = $_GET['aksi'];
$idFoldGet = $_GET['idFold'];

if($aksiGet == "deleteFile")
{
	$foldSub = $CFolder->detilFoldByIdFold($idFoldGet, "foldsub"); // atau level
	$fileFolder = $CFolder->detilFoldByIdFold($idFoldGet, "filefold");
	$fileDocSek = $CFile->detilFile($ideGet, "filedoc");

	$convFold = $CFile->detilFile($ideGet, "convfold");
	if($convFold == "N")
	{
		$fileSek = $pathArchives."data/document/LEVEL".$foldSub."/".$fileFolder."/".$fileDocSek;
		$folderDelete = $pathArchives."data/documentDel/Del-(".$dateTime.")-".$fileDocSek;
	}
	if($convFold == "Y")
	{
		$fileSek = $pathArchives."data/documentConvFold/LEVEL".$foldSub."/".$fileFolder."/".$fileDocSek;
		$folderDelete = $pathArchives."data/documentDel/Del-(".$dateTime.")-".$fileDocSek;
	}
	
	copy($fileSek, $folderDelete);
	
	unlink($fileSek);
	
	$CKoneksi->mysqlQuery("UPDATE mstdoc SET deletests = 1, delusrdt = '".$CPublic->userWhoAct()."' WHERE ide = '".$ideGet."' and idfold='".$idFoldGet."' AND deletests=0;");
	$CHistory->updateLog($userIdLogin, "Hapus File (ide = <b>".$ideGet."</b>, idfold = <b>".$idFoldGet."</b>)");
}

if($aksiGet == "deleteCariFile")
{
	$idFoldGet = $CFile->detilFile($ideGet, "idfold");;
	$foldSub = $CFolder->detilFoldByIdFold($idFoldGet, "foldsub"); // atau level
	$fileFolder = $CFolder->detilFoldByIdFold($idFoldGet, "filefold");
	$fileDocSek = $CFile->detilFile($ideGet, "filedoc");

	$convFold = $CFile->detilFile($ideGet, "convfold");
	if($convFold == "N")
	{
		$fileSek = $pathArchives."data/document/LEVEL".$foldSub."/".$fileFolder."/".$fileDocSek;
		$folderDelete = $pathArchives."data/documentDel/Del-(".$dateTime.")-".$fileDocSek;
	}
	if($convFold == "Y")
	{
		$fileSek = $pathArchives."data/documentConvFold/LEVEL".$foldSub."/".$fileFolder."/".$fileDocSek;
		$folderDelete = $pathArchives."data/documentDel/Del-(".$dateTime.")-".$fileDocSek;
	}
	
	copy($fileSek, $folderDelete);
	
	unlink($fileSek);
	
	$CKoneksi->mysqlQuery("UPDATE mstdoc SET deletests = 1, delusrdt = '".$CPublic->userWhoAct()."' WHERE ide = '".$ideGet."' and idfold='".$idFoldGet."' AND deletests=0;");
	$CHistory->updateLog($userIdLogin, "Hapus File (ide = <b>".$ideGet."</b>, idfold = <b>".$idFoldGet."</b>)");
}

$html = "";
$urutan = 0;

if($halamanGet == "cari")
{
	$paramCariGet = mysql_real_escape_string( $_GET['paramCari'] );
	$query=$CKoneksi->mysqlQuery("SELECT * FROM mstdoc WHERE namedoc LIKE '%".$paramCariGet."%' AND idfold='".$idFoldGet."' AND fileowner =".$userIdLogin." AND deletests=0 ORDER BY namedoc ASC");
}
else
{
	$query=$CKoneksi->mysqlQuery("SELECT * FROM mstdoc WHERE idfold='".$idFoldGet."' AND fileowner =".$userIdLogin." AND deletests=0 ORDER BY namedoc ASC");
}
while($row = $CKoneksi->mysqlFetch($query))
{
	$urutan++;
	
	$iconShow = "../../picture/".$CFile->detilExtension($row['extdoc'] , "iconekstension");
	if($CFile->detilExtension($row['extdoc'] , "iconekstension") == "")
	{
		$iconShow = "../../picture/file.png";
	}
	
	$idFold = $CFile->detilFile($row['ide'], "idfold");
	$fileFold = $CFolder->detilFoldByIdFold($idFold, "filefold");
	$foldSub = $CFolder->detilFoldByIdFold($idFold, "foldsub");
	$levelFolder="LEVEL".$foldSub;
	$convFold = $row['convfold'];
	$pathDoc = $row['pathdoc'];
	$fileDoc = $row['filedoc'];
	$oldNameDoc = $row['oldnamedoc'];

	$pathFile = "";
	if($convFold == "N")// JIKA FILE BUKAN DARI CONVERT FOLDER USER DATA / INPUT DARI SISTEM MY FOLDER
	{
		//$pathFile = $pathFolder.$levelFolder."/".$fileFold."/".$row['filedoc'];
		$pathFile = $pathFolder.$levelFolder."/".$fileFold."/".$row['filedoc'];
	}
	else if($convFold == "Y") // JIKA FILE BERASAL DARI CONVERT FOLDER USER DATA
	{
		$pathFile = $pathFolderConvFold.$levelFolder."/".$fileFold."/".$row['filedoc'];
		/*if($row['gantinamedoc'] == "N")
		{
			$pathFile = str_replace("C:/wamp/www/andhikaPortalTes/archives/", "../", $pathDoc).$fileDoc;
			$pathFileSize = $pathDoc.$fileDoc;
		}
		else if($row['gantinamedoc'] == "Y")
		{
			$pathFile = str_replace("C:/wamp/www/andhikaPortalTes/archives/", "../", $pathDoc).$oldNameDoc.".".$row['extdoc'];
			$pathFileSize = $pathDoc.$oldNameDoc.".".$row['extdoc'];
			$oldNameDocText = "( ".$oldNameDoc." )";
		}*/
	}
	
	$clickBtnOpen = "document.getElementById('hrefOpenFile".$row['ide']."').click();";
	$fileSize = $CFolder->display_size(filesize($pathFile));
	//$hrefOpenFile = "<a id=\"hrefOpenFile".$row['ide']."\" href=\"".$CPublic->konversiSymbol($pathFile)."\"></a>";
	//$hrefOpenFile = "<a id=\"hrefOpenFile".$row['ide']."\" href=\"downloadFile.php?nameFile=".$row['namedoc']."&extFile=".$row['extdoc']."&docFile=".$CPublic->konversiSymbol($pathFile)."\"></a>";
	$hrefOpenFile = "<a id=\"hrefOpenFile".$row['ide']."\" href=\"downloadFile.php?ideFile=".$row['ide']."\"></a>";
	$pdfYes = "N";
	if($row['extdoc'] == "pdf")
	{
		//$pdfYes = "Y";
		//$clickBtnOpen = "parent.openPdf('".$CPublic->konversiSymbol($pathFile)."');";
	}
	if($row['extdoc'] == "gif" || "GIF" || "png" || "PNG" || "bmp" || "BMP" || "tif" || "TIF" || "jpg" || "JPG" || "jpeg" || "JPEG" || "txt")
	{
		//$clickBtnOpen = "window.open('".$CPublic->konversiSymbol($pathFile)."', 'WindowC', 'resizable=1');";
	}
	
	//$dobelKlik = "dobelKlik('".$pdfYes."', '".$pathFile."', 'hrefOpenFile".$row['ide']."');";
	$dobelKlik = $clickBtnOpen;
	if($row['extdoc'] == "gif" || "GIF" || "png" || "PNG" || "bmp" || "BMP" || "tif" || "TIF" || "jpg" || "JPG" || "jpeg" || "JPEG" || "txt")
	{
		//$dobelKlik = "window.open('".$CPublic->konversiSymbol($pathFile)."', 'WindowC', 'resizable=1');";
	}
	
	$title = $row['namedoc']."\nDate Modified :".$row['wktupload']."\nSize : ".$fileSize;

	$popUpSetting = "<div class=\"elementDefault\" id=\"setFile".$urutan."\" style=\"display:none;width:auto;position:absolute;\">
                <table width=\"100\" cellpadding=\"2\" cellspacing=\"0\">
                <tr>
                    <td>
                    	<button class=\"btnStandarKecil\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['ide']."','detailFile');\" style=\"width:110px;height:30px;\" title=\"Detail of this File\">
                            <table width=\"100%\" height=\"100%\">
                              <tr>
                                <td align=\"left\"><img src=\"../../picture/Information-blue-32.png\" height=\"15\"/>&nbsp;&nbsp;DETAIL</td>
                              </tr>
                            </table>
                        </button>
                     </td>
                </tr>
                <tr>
                    <td>
                    	<button class=\"btnStandarKecil\" type=\"button\" onclick=\"".$clickBtnOpen."\" style=\"width:110px;height:30px;\" title=\"Open/Save this File to LocalDisk\">
                            <table width=\"100%\" height=\"100%\">
                              <tr>
                                <td align=\"left\"><img src=\"".$iconShow."\" height=\"15\"/>&nbsp;&nbsp;OPEN/SAVE</td>
                              </tr>
                            </table>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>
                    	<button class=\"btnStandarKecil\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['ide']."','editFile');\" style=\"width:110px;height:30px;\" title=\"Edit this File\">
                            <table width=\"100%\" height=\"100%\">
                              <tr>
                                <td align=\"left\"><img src=\"../../picture/Auction-blue-32.png\" height=\"15\"/>&nbsp;&nbsp;EDIT</td>
                              </tr>
                            </table>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>
                    	<button class=\"btnStandarKecil\" type=\"button\" onclick=\"parent.deleteFile('".$row['ide']."');\" style=\"width:110px;height:30px;\" title=\"Delete this File\">
                            <table width=\"100%\" height=\"100%\">
                              <tr>
                                <td align=\"left\"><img src=\"../../picture/Button-Cross-blue-32.png\" height=\"15\"/>&nbsp;&nbsp;DELETE</td> 
                              </tr>
                            </table>
                        </button>
                    </td>
                </tr>
                     
                </table>
            </div>";
	
	$html.= "<div class=\"btnIconFolder\" onMouseOver=\"this.className='btnIconFolderHover'\" onMouseOut=\"this.className='btnIconFolder'\" style=\"width:120px;height:95px;top:145;float:left;margin-left:32px;margin-top:20px;\" onClick=\"allHidden();\" ondblclick=\"".$dobelKlik."\" oncontextmenu=\"showCollapse('".$urutan."');return false;\" title=\"".$title."\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"120\" height=\"95\" class=\"fontBtnKecil\" border=\"0\">
				  <tr>
					<td align=\"center\" valign=\"bottom\" height=\"35\"><img src=\"".$iconShow."\" height=\"30\"/> </td>
				  </tr>
				  <tr>
				  	<td align=\"center\" valign=\"top\" height=\"18\">Type : ".$row['extdoc']."</td>
				  </tr>
				  <tr>
					<td align=\"center\" valign=\"top\" style=\"color:#000080;\">
					".$popUpSetting ."
					".$CPublic->potongKarakter($row['namedoc'], 15)."</td>
				  </tr>
				</table>
		   </div>
		   <input type=\"hidden\" id=\"statHidden".$urutan."\" value=\"Y\">
		   ".$hrefOpenFile."
		   ";
}
echo $html;
?>
    	<input type="hidden" id="jmlFile" value="<?php echo $urutan; ?>">
    </td>
</tr>

</table>
</body>

<?php

$setAllCollapse = "";
for($ii = 1; $ii <= $urutan; $ii++)
{
	$setAllCollapse.= "animatedcollapse.addDiv('setFile".$ii."', 'fade=1,speed=500,height=auto,overflow-y=scroll');";
}

echo "<script>".$setAllCollapse."</script>";
?>
</HTML>