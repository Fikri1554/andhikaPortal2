<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$pathFolder="../data/document/";
$pathFolderConvFold="../data/documentConvFold/";
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>

<body onLoad="loadScroll('subordFileList');" onUnload="saveScroll('subordFileList');">
<table width="100%">
<?php
$idFoldGet = $_GET['idFold'];
$foldSubGet = $_GET['foldSub'];
$userIdOwnerGet = $_GET['userIdOwner'];
$html = "";
$urutan = 1;
$query = $CKoneksi->mysqlQuery("SELECT * FROM mstdoc WHERE idfold='".$idFoldGet."' AND fileowner =".$userIdOwnerGet." AND deletests=0 ORDER BY namedoc ASC");

while($row = $CKoneksi->mysqlFetch($query))
{
	$idFold = $CFile->detilFile($row['ide'], "idfold");
	$fileFold = $CFolder->detilFoldByIdFold($idFold, "filefold");
	$foldSub = $CFolder->detilFoldByIdFold($idFold, "foldsub");
	$levelFolder="LEVEL".$foldSub;
	$convFold = $row['convfold'];
	$pathDoc = $row['pathdoc'];
	$fileDoc = $row['filedoc'];
	
	$pathFile = "";
	if($convFold == "N")
	{
		$pathFile = $pathFolder.$levelFolder."/".$fileFold."/".$row['filedoc'];
	}
	else if($convFold == "Y")
	{
		$pathFile = $pathFolderConvFold.$levelFolder."/".$fileFold."/".$row['filedoc'];
	}
	
	$fileSize = filesize($pathFolder.$levelFolder."/".$fileFold."/".$row['filedoc']);
	
	$fileCreated = date ( "d F Y H:i", filectime( $pathFolder.$levelFolder."/".$fileFold."/".$row['filedoc'] ) );
	
	//$hrefOpenFile = "<a id=\"hrefOpenFile".$row['ide']."\" href=\"".$pathFolder.$levelFolder."/".$fileFold."/".$row['filedoc']."\"></a>";
	$hrefOpenFile = "<a id=\"hrefOpenFile".$row['ide']."\" href=\"downloadFile.php?ideFile=".$row['ide']."\"></a>";
	$clickBtnOpen = "document.getElementById('hrefOpenFile".$row['ide']."').click();";
	
	$iconShow = "../../picture/".$CFile->detilExtension($row['extdoc'] , "iconekstension");
	//$bgRow = " style=\"background-repeat:no-repeat;background-position:right;\" background=\"../../picture/imgPdfFile.png\"";
	if($row['extdoc'] == "pdf")
	{
		//$clickBtnOpen = "parent.openPdf('".$pathFolder.$levelFolder."/".$fileFold."/".$row['filedoc']."');";
	}
	if($row['extdoc'] == "gif" || "GIF" || "png" || "PNG" || "bmp" || "BMP" || "tif" || "TIF" || "jpg" || "JPG" || "jpeg" || "JPEG" || "txt")
	{
		//$clickBtnOpen = "window.open('".$pathFolder.$levelFolder."/".$fileFold."/".$row['filedoc']."', 'WindowC', 'resizable=1');";
	}
	
	$expWktUpload = explode(" ", $row['wktupload']);
	$expWktUpload1 = explode("-", $expWktUpload[0]);
	$expWktUpload2 = $expWktUpload[1];
	
	$tglUpload = $expWktUpload1[2];
	$blnUpload = ucfirst( strtolower( $CPublic->bulanSetengah( $expWktUpload1[1], "ind") ) );
	$thnUpload = $expWktUpload1[0];
	
	$wktUpload = $tglUpload." ".$blnUpload." ".$thnUpload." ".$expWktUpload2;
	
	$onClickDetail = "parent.openThickboxWindow('".$row['ide']."','detailSubordinateFile');";
	$onClickOpen = $clickBtnOpen;
// KETIKA KLIK BTN EDIT PADA SUBORDINATE LIST, PANGGIL FUNCTION 'openThickboxWindow'
	$onClickEdit = "parent.openThickboxWindow('".$row['ide']."','editSubordinateFile');";
	
	$fileSizeTitle = $CFolder->display_size(filesize($pathFolder.$levelFolder."/".$fileFold."/".$row['filedoc']));
	$title = $row['namedoc']."\nDate Modified :".$row['wktupload']."\nSize : ".$fileSizeTitle;
	
	$html.="<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
				<td class=\"tdMyFolder\">
					<table width=\"100%\" border=\"0\">
					<tr>
						<td width=\"5%\" align=\"center\" style=\"font-size:22px;color:#000080;font-weight:bold;font-family:Tahoma;\" onClick=\"".$onClickOpen."\" title=\"".$title."\">".$urutan++."</td>
						<td width=\"60%\">
							<table width=\"100%\" class=\"fontMyFolderList\" ".$bgRow." onClick=\"".$onClickOpen."\" title=\"".$title."\">
							<tr>
								<td width=\"80\">Name</td><td width=\"10\">:</td><td style=\"color:#000080;\"><b>".$CPublic->potongKarakter($row['namedoc'], 70)."</b></td>
							</tr>
							<tr>
								<td>Upload date</td><td>:</td><td style=\"color:#000080;\">".$wktUpload."</td>
							</tr>
							<tr>
								<td>Type file</td><td>:</td><td style=\"color:#000080;\">".$row['extdoc']."</td>
							</tr>
							</table>
						</td>
						<td width=\"35%\" align=\"right\">".$hrefOpenFile."
							<button class=\"btnStandarKecil\" type=\"button\" onClick=\"".$onClickDetail."\" style=\"width:75px;height:55px;\" title=\"Detail of this Subordinate's File\">
								<table width=\"100%\" height=\"100%\">
								  <tr>
									<td align=\"center\"><img src=\"../../picture/Information-blue-32.png\" height=\"25\"/> </td>
									
								  </tr>
								  <tr>
									<td align=\"center\">DETAIL</td>
								  </tr>
								</table>
							</button>
							&nbsp;
							<button type=\"button\" class=\"btnStandarKecil\" onClick=\"".$onClickOpen."\" style=\"width:75px;height:55px;\" title=\"Open/Save this Subordinate's File\">
								<table width=\"100%\" height=\"100%\">
								  <tr>
									<td align=\"center\"><img src=\"".$iconShow."\" height=\"28\"/></td>
								  </tr>
								  <tr>
									<td align=\"center\">OPEN/SAVE</td>
								  </tr>
								</table>
							</button>
							&nbsp;
							<button class=\"btnStandarKecil\" type=\"button\" onclick=\"".$onClickEdit."\" style=\"width:75px;height:55px;\" title=\"Edit this Subordinate's File\">
								  <table width=\"100%\" height=\"100%\">
									<tr>
									  <td align=\"center\"><img src=\"../../picture/Auction-blue-32.png\" height=\"28\"/> </td>
									</tr>
									<tr>
									  <td align=\"center\">EDIT</td>
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