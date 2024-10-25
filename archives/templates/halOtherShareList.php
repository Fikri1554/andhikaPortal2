<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$pathFolder="../data/document/";
$pathFolderConvFold="../data/documentConvFold/";

$aksiGet = $_GET['aksi'];
$userIdOwnerGet = $_GET['userIdOwner'];
$idAuthorFoldGet = $_GET['idAuthorFold'];

$idFoldRefGet = $_GET['idFoldRef'];
$foldSubGet = $_GET['foldSub'];
$paramCariGet = mysql_real_escape_string($_GET['paramCari']);
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>

<body onLoad="loadScroll('otherFolderList');" onUnload="saveScroll('otherFolderList');">    
<table width="100%">
<?php
$html = "";
$urutan = 1;

$jmlFolder = 0;

if($aksiGet == "empChoose")
{
	if($halamanGet == "cari")
	{
		$query = $CKoneksi->mysqlQuery("SELECT * FROM tblauthorfold WHERE (empno=".$userEmpNo." OR empno=99999) AND folderowner=".$userIdOwnerGet." AND namefold LIKE '%".$paramCariGet."%' AND deletests=0 GROUP BY idefold ORDER BY namefold ASC");
	}
	else
	{
		$query = $CKoneksi->mysqlQuery("SELECT * FROM tblauthorfold WHERE (empno=".$userEmpNo." OR empno=99999) AND folderowner=".$userIdOwnerGet." AND deletests=0 GROUP BY idefold ORDER BY namefold ASC");
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
			$html.= tombol($CFolder, $urutan++, $row, $row2, $size, $tipeKonten, $row['idauthorfold'], $jmlIsi);
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
		$html.= tombol($CFolder, $urutan++, "", $row2, $size, $tipeKonten, $idAuthorFoldGet, $jmlIsi);
	}
	$jmlFolder = $CKoneksi->mysqlNRows($query2);
	echo $html;
}
?>
</table>
</body>
<?php
function tombol($CFolder, $urutan, $row, $row2, $size, $tipeKonten, $idAuthorFold, $jmlIsi)
{
	$aksesDetail = $CFolder->detilAuthorFold($idAuthorFold, "detail");
	$aksesExpand = $CFolder->detilAuthorFold($idAuthorFold, "expand");

	$onClickDetail = "alert('You do not have permissions');";
	$onClickExpand = "alert('You do not have permissions');";
	if($aksesDetail == "Y")
	{
		$onClickDetail = "parent.openThickboxWindow('".$row2['ide']."','detailOtherShare');";
	}
	if($aksesExpand == "Y")
	{
		$onClickExpand = "parent.pilihBtnExpand('".$row2['ide']."', '".$row2['foldsub']."', '".$row2['idfold']."', '".$row2['tipekonten']."', '".$idAuthorFold."', '".$idAuthorFold."');";
	}
	
	$bgRow = " style=\"background-repeat:no-repeat;background-position:right;\" background=\"../../picture/imgShared.png\"";
	$pathFolder="../data/document/";
	$fileFold = $CFolder->detilFold($row2['ide'], "filefold");
	$levelFolder="LEVEL".$row2['foldsub'];
	
		//$sizeTitle = $CFolder->display_size($CFolder->dirSize($pathFolder.$levelFolder."/".$fileFold));
	$pathFolderConvFold="../data/documentConvFold/";
	$convFold = $CFolder->detilFold($row2['ide'], "convfold");
	if($convFold == "N")
	{
		$sizeTitle = $CFolder->display_size($CFolder->dirSize($pathFolder.$levelFolder."/".$fileFold));
	}
	if($convFold == "Y")
	{
		$sizeTitle = $CFolder->display_size($CFolder->dirSize($pathFolderConvFold.$levelFolder."/".$fileFold));
	}
	
	$title = $row2['namefold']."\nContent : ".ucfirst($row2['tipekonten'])." (".$jmlIsi.")\nDate Modified : ".$row2['tglbuat']."\nSize : ".$sizeTitle;
	if($row2['tipekonten'] == "folder")
	{
		$title = $row2['namefold']."\nContent : ".ucfirst($row2['tipekonten'])." (".$jmlIsi.")\nDate Modified : ".$row2['tglbuat'];
	}
	
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
													
							<button class=\"btnStandarKecil\" type=\"button\" onClick=\"".$onClickDetail."\" style=\"width:90px;height:55px;\" title=\"Detail of this Other Shared Folder\">
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
							<button class=\"btnStandarKecil\" type=\"button\" onClick=\"".$onClickExpand."\" style=\"width:90px;height:55px;\" title=\"Go to this Other Shared Folder Content\">
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