<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$pathFolder="../data/document/";

$userIdOwnerGet = $_GET['userIdOwner'];
$foldSubGet = $_GET['foldSub'];
$idFoldRefGet = $_GET['idFoldRef'];
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>

<body onLoad="loadScroll('subordFolderList');" onUnload="saveScroll('subordFolderList');">
    
<table width="100%">
<?php
$html = "";
$urutan = 0;
$jmlFolder = 0;

$query = $CKoneksi->mysqlQuery("SELECT * FROM tblfolder WHERE foldsub='".$foldSubGet."' AND idfoldref='".$idFoldRefGet."' AND folderowner=".$userIdOwnerGet." AND deletests=0 ORDER BY namefold ASC");
while($row = $CKoneksi->mysqlFetch($query))
{
	$urutan++;
	$levelFolder="LEVEL".$row['foldsub'];
	if(is_dir($pathFolder.$levelFolder."/".$row['filefold']))
	{
		$folderSize = $CFolder->dirSize($pathFolder.$levelFolder."/".$row['filefold']);
	}
	else
	{
		$folderSize = "Not a Folder";
	}
	
	if($row['tipekonten'] == "folder") // jika tipe konten folder maka ambil jumlah folder yang berada di level setelahnya
	{
		$jmlIsi = $CFolder->jmlFolder($row['idfold']);
	}
	else if($row['tipekonten'] == "file") // jika tipe konten file maka ambil jumlah file yang berada di level setelahnya
	{
		$jmlIsi = $CFile->jmlFile($row['idfold']);
	}
	
	$size = $CFolder->display_size($folderSize)." (".$folderSize." Bytes)";
	$tipeKonten = ucfirst($row['tipekonten'])." (<b>".$jmlIsi."</b>)";
	$html.= tombol($CFolder, $urutan, $row, $size, $tipeKonten, $CPublic);
}
$jmlFolder = $CKoneksi->mysqlNRows($query);
echo $html;
?>
</table>
</body>

<?php
function tombol($CFolder, $urutan, $row, $size, $tipeKonten, $CPublic)
{	
	$bgRow = " style=\"background-repeat:no-repeat;background-position:right;\" background=\"../../picture/imgSubordinate.png\"";

	$onClickDetail = "parent.openThickboxWindow('".$row['ide']."','detailSubordinateFolder');";
	$onClickExpand = "parent.pilihBtnExpand('".$row['ide']."', '".$row['foldsub']."', '".$row['idfold']."', '".$row['tipekonten']."');";
	
	$fileFold = $CFolder->detilFold($row['ide'], "filefold");
	$levelFolder="LEVEL".$_GET['foldSub'];
	
	$pathFolder="../data/document/";
	$sizeTitle = $CFolder->display_size($CFolder->dirSize($pathFolder.$levelFolder."/".$fileFold));
	$title = $row['namefold']."\nDate Modified : ".$row['tglbuat']."\nSize : ".$sizeTitle;
	if($row['tipekonten'] == "folder")
	{
		$title = $row['namefold']."\nDate Modified : ".$row['tglbuat'];
	}
	
	$html = "";
	$html = "<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
				<td class=\"tdMyFolder\">
					<table width=\"100%\" border=\"0\">
					<tr>
						<td width=\"5%\" align=\"center\" style=\"font-size:22px;color:#000080;font-weight:bold;font-family:Tahoma;\" onClick=\"".$onClickExpand."\" title=\"".$title."\">".$urutan++."</td>
						<td width=\"70%\" onClick=\"".$onClickExpand."\" title=\"".$title."\">
							<table width=\"100%\" class=\"fontMyFolderList\" ".$bgRow.">
							<tr>
								<td width=\"80\">Name</td><td width=\"10\">:</td>
								<td>
									<span style=\"color:#000080;\">".$CPublic->potongKarakter($row['namefold'], 100)."</span>
								</td>
							</tr>
							<tr>
								<td>Created date</td><td>:</td><td style=\"color:#000080;\">".$row['tglbuat']."</td>
							</tr>
							<tr>
								<td>Content</td><td>:</td>
								<td style=\"color:#000080;\">".$tipeKonten."</td>
							</tr>
							</table>
						</td>
						
						<td width=\"25%\" align=\"right\">	
													
							<button class=\"btnStandarKecil\" type=\"button\" onClick=\"".$onClickDetail."\" style=\"width:90px;height:55px;\" title=\"Detail of this Subordinate's Folder\">
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
							<button class=\"btnStandarKecil\" type=\"button\" onClick=\"".$onClickExpand."\" style=\"width:90px;height:55px;\" title=\"Go to this Subordinate's Folder Content\">
								<table width=\"100%\" height=\"100%\">
								  <tr>
									<td align=\"center\"><img src=\"../../picture/Outbox-blue-32.png\" height=\"25\"/> </td>
									
								  </tr>
								  <tr>
									<td align=\"center\">EXPAND</td>
								  </tr>
								</table>
							</button>
						</td>
					</table>
				</td>
			</tr>";
	return $html;
}
?>
<script>
<?php
	echo "parent.document.getElementById('jmlFolder').value = ".$jmlFolder.";";
?>
</script>
</HTML>