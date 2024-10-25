<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$pathFolder="../data/document/";
$pathFolderConvFold="../data/documentConvFold/";

$aksiGet = $_GET['aksi'];
$userIdOwnerGet = $_GET['userIdOwner'];
$empNoSharedGet = $_GET['empNoShared'];

$idFoldRefGet = $_GET['idFoldRef'];
$foldSubGet = $_GET['foldSub'];
$idAuthorFoldGet = $_GET['idAuthorFold'];
$paramCariGet = $_GET['paramCari'];

?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript">
/*var timeOut;
function scrollToTop() {
  if (document.body.scrollTop!=0 || document.documentElement.scrollTop!=0){
    window.scrollBy(0,-50);
    timeOut=setTimeout('scrollToTop()',1000);
  }
  else clearTimeout(timeOut);
}*/
/*$('#link').click(function(){
    $("body").animate({ scrollTop: 0 }, 10000);
    return false;
 });*/
</script>
<body onLoad="loadScroll('ownShareList');" onUnload="saveScroll('ownShareList');">
<table width="100%">
<?php
$html = "";
$urutan = 1;

$jmlFolder = 0;

if($aksiGet == "empChoose")
{
	if($empNoSharedGet == "99999") // jika yang dpilih bernilai All
	{
		if($halamanGet == "cari")
		{
			$query = $CKoneksi->mysqlQuery("SELECT * FROM tblauthorfold WHERE folderowner=".$userIdOwnerGet." AND namefold LIKE '%".$paramCariGet."%' AND deletests=0 GROUP BY idefold ORDER BY namefold ASC");
		}
		else
		{
			$query = $CKoneksi->mysqlQuery("SELECT * FROM tblauthorfold WHERE folderowner=".$userIdOwnerGet." AND deletests=0 GROUP BY idefold ORDER BY namefold ASC");
		}
	}
	else
	{
		if($halamanGet == "cari")
		{
			$query=$CKoneksi->mysqlQuery("SELECT * FROM tblauthorfold WHERE empno=".$empNoSharedGet." AND folderowner=".$userIdOwnerGet." AND namefold LIKE '%".$paramCariGet."%' AND deletests=0 ORDER BY namefold ASC");
		}
		else
		{
			$query=$CKoneksi->mysqlQuery("SELECT * FROM tblauthorfold WHERE empno=".$empNoSharedGet." AND folderowner=".$userIdOwnerGet." AND deletests=0 ORDER BY namefold ASC");
		}
	}
	
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$query2 = $CKoneksi->mysqlQuery("SELECT * FROM tblfolder WHERE ide ='".$row['idefold']."' AND deletests=0");
		while($row2 = $CKoneksi->mysqlFetch($query2))
		{
			$levelFolder="LEVEL".$row2['foldsub'];
			if(is_dir($pathFolder.$levelFolder."/".$row2['filefold']))
			{
					//$folderSize = $CFolder->dirSize($pathFolder.$levelFolder."/".$row2['filefold']);
				$convFold = $CFolder->detilFold($row2['ide'], "convfold");
				if($convFold == "N")
				{
					$folderSize = $CFolder->dirSize($pathFolder.$levelFolder."/".$row2['filefold']);
				}
				if($convFold == "Y")
				{
					$folderSize = $CFolder->dirSize($pathFolderConvFold.$levelFolder."/".$row2['filefold']);
				}
			}
			else
			{
				$folderSize = "Not a Folder";
			}
			
			if($row2['tipekonten'] == "folder") // jika tipe konten folder maka ambil jumlah folder yang berada di level setelahnya
			{
				$jmlIsi = $CFolder->jmlFolder($row2['idfold']);
			}
			else if($row2['tipekonten'] == "file") // jika tipe konten file maka ambil jumlah file yang berada di level setelahnya
			{
				$jmlIsi = $CFile->jmlFile($row2['idfold']);
			}
			
			$size = $CFolder->display_size($folderSize)." (".$folderSize." Bytes)";
			$tipeKonten = ucfirst($row2['tipekonten'])."</span> (<b>".$jmlIsi."</b>)";
			
			$html.= tombol($CFolder, $urutan++, $row, $row2, $size, $tipeKonten, $row['idauthorfold']);
		}
	}
	$jmlFolder = $CKoneksi->mysqlNRows($query);
	echo $html;
}
if($aksiGet == "expand")
{
	if($halamanGet == "cari")
	{
		$query2 = $CKoneksi->mysqlQuery("SELECT * FROM tblfolder WHERE foldsub='".$foldSubGet."' AND idfoldref='".$idFoldRefGet."' AND namefold LIKE '%".$paramCariGet."%' AND deletests=0 ORDER BY namefold ASC");
	}
	else
	{
		$query2 = $CKoneksi->mysqlQuery("SELECT * FROM tblfolder WHERE foldsub='".$foldSubGet."' AND idfoldref='".$idFoldRefGet."' AND deletests=0 ORDER BY namefold ASC");
	}
	while($row2 = $CKoneksi->mysqlFetch($query2))
	{
		$levelFolder="LEVEL".$row2['foldsub'];
		if(is_dir($pathFolder.$levelFolder."/".$row2['filefold']))
		{
				//$folderSize = $CFolder->dirSize($pathFolder.$levelFolder."/".$row2['filefold']);
			$convFold = $CFolder->detilFold($row2['ide'], "convfold");
			if($convFold == "N")
			{
				$folderSize = $CFolder->dirSize($pathFolder.$levelFolder."/".$row2['filefold']);
			}
			if($convFold == "Y")
			{
				$folderSize = $CFolder->dirSize($pathFolderConvFold.$levelFolder."/".$row2['filefold']);
			}
		}
		else
		{
			$folderSize = "Not a Folder";
		}
		
		if($row2['tipekonten'] == "folder") // jika tipe konten folder maka ambil jumlah folder yang berada di level setelahnya
		{
			$jmlIsi = $CFolder->jmlFolder($row2['idfold']);
		}
		else if($row2['tipekonten'] == "file") // jika tipe konten file maka ambil jumlah file yang berada di level setelahnya
		{
			$jmlIsi = $CFile->jmlFile($row2['idfold']);
		}
		
		$size = $CFolder->display_size($folderSize)." (".$folderSize." Bytes)";
		$tipeKonten = ucfirst($row2['tipekonten'])." (<b>".$jmlIsi."</b>)";
		$html.= tombol($CFolder, $urutan++, "", $row2, $size, $tipeKonten, $idAuthorFoldGet);
	}
	$jmlFolder = $CKoneksi->mysqlNRows($query2);
	echo $html;
}
?>
<!--<tr><td><a id="link" href="#" onClick="scrollToTop();return false">back to the top of page</a></td></tr>-->
</table>
</body>
<?php
function tombol($CFolder, $urutan, $row, $row2, $size, $tipeKonten, $idAuthorFold)
{	
	$bgRow = " style=\"background-repeat:no-repeat;background-position:right;\" background=\"../../picture/imgShared.png\"";
	
	$pathFolder="../data/document/";
	$fileFold = $CFolder->detilFold($row2['ide'], "filefold");
	$levelFolder="LEVEL".$row2['foldsub'];
	
		//$size = $CFolder->display_size($CFolder->dirSize($pathFolder.$levelFolder."/".$fileFold));
	$pathFolderConvFold="../data/documentConvFold/";
	$convFold = $CFolder->detilFold($row2['ide'], "convfold");
	if($convFold == "N")
	{
		$size = $CFolder->display_size($CFolder->dirSize($pathFolder.$levelFolder."/".$fileFold));
	}
	if($convFold == "Y")
	{
		$size = $CFolder->display_size($CFolder->dirSize($pathFolderConvFold.$levelFolder."/".$fileFold));
	}
	
	$title = $row2['namefold']."\nDate Modified : ".$row2['tglbuat']."\nSize : ".$size;
	if($row2['tipekonten'] == "folder")
	{
		$title = $row2['namefold']."\nDate Modified : ".$row2['tglbuat'];
	}
	
	$onClickDetail = "parent.openThickboxWindow('".$row2['ide']."','detailOwnShare');";
	$onClickExpand = "parent.pilihBtnExpand('".$row2['ide']."', '".$row2['foldsub']."', '".$row2['idfold']."', '".$row2['tipekonten']."', '".$idAuthorFold."');";
	$html = "";
	$html = "<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
				<td class=\"tdMyFolder\">
					<table width=\"100%\" border=\"0\">
					<tr>
						<td width=\"5%\" align=\"center\" style=\"font-size:22px;color:#000080;font-weight:bold;font-family:Tahoma;\" onClick=\"".$onClickExpand."\" title=\"".$title."\">".$urutan++."</td>
						<td width=\"74%\" onClick=\"".$onClickExpand."\" title=\"".$title."\">
							<table width=\"100%\" class=\"fontMyFolderList\" ".$bgRow.">
							<tr>
								<td width=\"80\">Name</td><td width=\"10\">:</td>
								<td>
									<span style=\"color:#000080;\">".$row2['namefold']."</span>
								</td>
							</tr>
							<tr>
								<td>Created date</td><td>:</td>
								<td style=\"color:#000080;\">".$row2['tglbuat']."</td>
							</tr>
							<tr>
								<td>Content</td><td>:</td>
								<td style=\"color:#000080;\">".$tipeKonten."</td>
							</tr>
							</table>
						</td>
						
						<td width=\"25%\" align=\"right\">						
							<button class=\"btnStandarKecil\" type=\"button\" onClick=\"".$onClickDetail."\" style=\"width:90px;height:55px;\" title=\"Detail of this Own Shared Folder\">
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
							<button class=\"btnStandarKecil\" type=\"button\" onClick=\"".$onClickExpand."\" style=\"width:90px;height:55px;\" title=\"Go to this Own Shared Folder Content\">
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