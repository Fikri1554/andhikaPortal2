<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$pathFolder="../data/document/";
$pathFolderConvFold="../data/documentConvFold/";
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../../../js/main.js"></script>

<body onLoad="loadScroll('fileList');" onUnload="saveScroll('fileList');">
<table width="100%">
<?php
$aksiGet = $_GET['aksi'];
$idFoldGet = $_GET['idFold'];
$dateTime = $CPublic->dateTimeGabung();

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
		//$fileSek = $CFile->detilFile($ideGet, "pathdoc").$fileDocSek;
		//$folderDelete = $pathArchives."data/documentDel/Del-(".$dateTime.")-".$fileDocSek;
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
		//$fileSek = $pathArchives."data/documentConvFold/LEVEL".$foldSub."/".$fileFolder."/".$fileDocSek;
		//$folderDelete = $pathArchives."data/documentDel/Del-(".$dateTime.")-".$fileDocSek;
		$fileSek = $CFile->detilFile($ideGet, "pathdoc").$fileDocSek;
		$folderDelete = $pathArchives."data/documentDel/Del-(".$dateTime.")-".$fileDocSek;
	}
	
	copy($fileSek, $folderDelete);
	unlink($fileSek);
	$CKoneksi->mysqlQuery("UPDATE mstdoc SET deletests = 1, delusrdt = '".$CPublic->userWhoAct()."' WHERE ide = '".$ideGet."' and idfold='".$idFoldGet."' AND deletests=0;");
	$CHistory->updateLog($userIdLogin, "Hapus File (ide = <b>".$ideGet."</b>, idfold = <b>".$idFoldGet."</b>)");
}

$html = "";
$urutan = 1;
if($halamanGet == "cari" || $aksiGet == "deleteCariFile")
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
	$idFold = $CFile->detilFile($row['ide'], "idfold");
	$fileFold = $CFolder->detilFoldByIdFold($idFold, "filefold");
	$foldSub = $CFolder->detilFoldByIdFold($idFold, "foldsub");
	$levelFolder="LEVEL".$foldSub;
	$convFold = $row['convfold'];
	$pathDoc = $row['pathdoc'];
	$fileDoc = $row['filedoc'];
	$oldNameDoc = $row['oldnamedoc'];

	$iconShow = "../../picture/".$CFile->detilExtension($row['extdoc'] , "iconekstension");
	if($CFile->detilExtension($row['extdoc'] , "iconekstension") == "")
	{
		$iconShow = "../../picture/file.png";
	}
	//$bgRow = " style=\"background-repeat:no-repeat;background-position:right;\" background=\"../../picture/imgPdfFile.png\"";

	$oldNameDocText = "";
	$pathFile = "";
	if($convFold == "N")
	{
		$pathFile = $pathFolder.$levelFolder."/".$fileFold."/".$row['filedoc'];
	}
	else if($convFold == "Y")
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

	if($row['extdoc'] == "pdf")
	{
		//$clickBtnOpen = "parent.openPdf('".$CPublic->konversiSymbol($pathFile)."');";
	}
	if($row['extdoc'] == "gif" || "GIF" || "png" || "PNG" || "bmp" || "BMP" || "tif" || "TIF" || "jpg" || "JPG" || "jpeg" || "JPEG" || "txt")
	{	
		//$clickBtnOpen = "window.open('".$CPublic->konversiSymbol($pathFile)."', 'WindowC', 'resizable=1');";	
	}
	//rawurlencode(basename($pathFile))
	$expWktUpload = explode(" ", $row['wktupload']);
	$expWktUpload1 = explode("-", $expWktUpload[0]);
	$expWktUpload2 = $expWktUpload[1];
	
	$tglUpload = $expWktUpload1[2];
	$blnUpload = ucfirst( strtolower( $CPublic->bulanSetengah( $expWktUpload1[1], "ind") ) );
	$thnUpload = $expWktUpload1[0];
	$wktUpload = $tglUpload." ".$blnUpload." ".$thnUpload." ".$expWktUpload2;

	$title = $row['namedoc']."\nDate Modified :".$row['wktupload']."\nSize : ".$fileSize;
	
	$html.="<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
				<td class=\"tdMyFolder\">
					<table width=\"100%\" boder=\"0\">
					<tr>
						<td width=\"5%\" align=\"center\" style=\"font-size:22px;color:#000080;font-weight:bold;font-family:Tahoma;\" onClick=\"".$clickBtnOpen."\" title=\"".$title."\">".$urutan++."</td>
						<td width=\"59%\" onClick=\"".$clickBtnOpen."\" title=\"".$title."\">
							<table width=\"100%\" class=\"fontMyFolderList\" ".$bgRow.">
							<tr>
								<td width=\"80\">Name</td>
								<td width=\"10\">:</td>
								<td style=\"color:#000080;\">".$CPublic->potongKarakter($row['namedoc'], 70)." ".$oldNameDocText."</td>
							</tr>
							<tr>
								<td>Upload date</td>
								<td>:</td>
								<td style=\"color:#000080;\">".$wktUpload."</td>
							</tr>
							<tr>
								<td>Type file</td><td>:</td>
								<td style=\"color:#000080;\">".$row['extdoc']."</td>
							</tr>
							</table>
						</td>
						<td width=\"37%\" align=\"right\">".$hrefOpenFile."
							<button class=\"btnStandarKecil\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['ide']."','detailFile');\" style=\"width:75px;height:55px;\" title=\"Detail of this File\">
								<table width=\"100%\" height=\"100%\" border=\"0\">
								  <tr>
									<td align=\"center\"><img src=\"../../picture/Information-blue-32.png\" height=\"25\"/> </td>
								  </tr>
								  <tr>
									<td align=\"center\">DETAIL</td>
								  </tr>
								</table>
							</button>
							&nbsp;
							<button type=\"button\" class=\"btnStandarKecil\" onClick=\"".$clickBtnOpen."\" style=\"width:80px;height:55px;\" title=\"Open/Save this File to LocalDisk\">
								<table width=\"100%\" height=\"100%\" border=\"0\">
								  <tr>
									<td align=\"center\"><img src=\"".$iconShow."\" height=\"25\"/></td>
								  </tr>
								  <tr>
									<td align=\"center\">OPEN/SAVE</td>
								  </tr>
								</table>
							</button>
							&nbsp;
							<button class=\"btnStandarKecil\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['ide']."','editFile');\" style=\"width:75px;height:55px;\" title=\"Edit this File\">
								<table width=\"100%\" height=\"100%\">
								  <tr>
									<td align=\"center\"><img src=\"../../picture/Auction-blue-32.png\" height=\"25\"/> </td>
								  </tr>
								  <tr>
									<td align=\"center\">EDIT</td>
								  </tr>
								</table>
							</button>
							&nbsp;
							<button class=\"btnStandarKecil\" type=\"button\" onclick=\"parent.deleteFile('".$row['ide']."');\" style=\"width:75px;height:55px;\" title=\"Delete this File\">
								<table width=\"100%\" height=\"100%\">
								  <tr>
									<td align=\"center\"><img src=\"../../picture/Button-Cross-blue-32.png\" height=\"25\"/> </td>
								  </tr>
								  <tr>
									<td align=\"center\">DELETE</td>
								  </tr>
								</table>
							</button>
                    	</td>
					</tr>
					
					</table>
				</td>
			</tr>";
}
echo $html;
?>

</table>
</body>

</HTML>