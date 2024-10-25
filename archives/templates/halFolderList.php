<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
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
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>

<script>
/*$(document).ready(function() {
      alert("document ready occurred!");
	  loadScroll('folderList');
});*/

/*$(window).load(function() {
      //alert("window load occurred!");
	setTimeout(function()
	{
		loadScroll('folderList');
	},1000);
	//document.documentElement.scrollTop = document.body.scrollTop = 1000;
});*/
</script>

<body onLoad="loadScroll('folderList');" onUnload="saveScroll('folderList');">
    
<table width="100%">
<?php
$aksiGet = $_GET['aksi'];

$ideGet = $_GET['ide'];
$idFoldRef = $_GET['idFoldRef'];
$nmFold = mysql_real_escape_string( $_GET['nmFold'] );
$descFold = mysql_real_escape_string( $_GET['descFold'] );
$foldSubGet = $_GET['foldSub'];
$contentTypeGet = $_GET['contentType'];

$dateTime = $CPublic->dateTimeGabung();
$tglbuat = $CPublic->indonesiaDate()." ".$CPublic->jamServer();
//mysql_real_escape_string

if($aksiGet == "simpanNewFolder")
{
	$idFoldLast = $CFolder->idFoldLast($foldSubGet, $idFoldRef);
	
	$fileFold = $userIdLogin."-".$idFoldLast."-".$dateTime;
	$addUsrdt = $CPublic->userWhoAct();
	
	$convFold = "N";
	if($idFoldRef != "")
	{
		$convFold = $CFolder->detilFoldByIdFold($idFoldRef, "convfold");
	}
	
	$CKoneksi->mysqlQuery("INSERT INTO tblfolder(idfold, idfoldref, foldsub, folderowner, namefold, filefold, descfold, iddivisi, tipekonten, tglbuat, convfold, addusrdt) 
	VALUES 
	('".$idFoldLast."', '".$idFoldRef."',  '".$foldSubGet."', '".$userIdLogin."', '".$nmFold."', '".$fileFold."', '".$descFold."', '', '".$contentTypeGet."', '".$tglbuat."', '".$convFold."', '".$addUsrdt."');");
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
		mkdir($pathFolder."", 0700);
		mkdir($pathFolder."/".$fileFold, 0700);
	}
}
if($aksiGet == "simpanEditFolder")
{
	$CKoneksi->mysqlQuery("UPDATE tblfolder SET namefold='".$nmFold."', descfold='".$descFold."', tipekonten='".$contentTypeGet."', updusrdt='".$CPublic->userWhoAct()."' WHERE ide=".$ideGet." AND deletests=0");
	$CKoneksi->mysqlQuery("UPDATE tblauthorfold SET namefold='".$nmFold."' WHERE idefold=".$ideGet." AND deletests=0");
	
	$CHistory->updateLog($userIdLogin, "Rubah Folder (ide = <b>".$ideGet."</b>)");
}

if($aksiGet == "deleteFolder")
{
	$folderSek = $CFolder->detilFold($ideGet, "filefold");
	$convFold = $CFolder->detilFold($ideGet, "convfold");
	if($convFold == "N" || $convFold == "")
	{
		$pathFolderSek = $pathArchives."data/document/LEVEL".$foldSubGet."/".$folderSek;
	}
	if($convFold == "Y")
	{
		$pathFolderSek = $pathArchives."data/documentConvFold/LEVEL".$foldSubGet."/".$folderSek;
	}
	
	rmdir($pathFolderSek);
	$CKoneksi->mysqlQuery("UPDATE tblfolder SET deletests=1, delusrdt='".$CPublic->userWhoAct()."' WHERE ide=".$ideGet." AND deletests=0");
	$CKoneksi->mysqlQuery("UPDATE tblauthorfold SET deletests=1, delusrdt='".$CPublic->userWhoAct()."' WHERE idefold=".$ideGet." AND folderowner='".$userIdLogin."' AND deletests=0");
	$CHistory->updateLog($userIdLogin, "Hapus Folder (ide = <b>".$ideGet."</b>)");
}

$html = "";
$urutan = 1;

if($halamanGet == "cari")
{
	$paramCariGet = mysql_real_escape_string( $_GET['paramCari'] );
	$query = $CKoneksi->mysqlQuery("SELECT * FROM tblfolder WHERE namefold LIKE '%".$paramCariGet."%' AND foldsub='".$foldSubGet."' AND idfoldref='".$idFoldRef."' AND folderowner=".$userIdLogin." AND deletests=0 ORDER BY namefold ASC");
}
else
{
	$query = $CKoneksi->mysqlQuery("SELECT * FROM tblfolder WHERE foldsub='".$foldSubGet."' AND idfoldref='".$idFoldRef."' AND folderowner=".$userIdLogin." AND deletests=0 ORDER BY namefold ASC");
}
while($row = $CKoneksi->mysqlFetch($query))
{
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
	
	$deleteFolder = "parent.deleteFolder('".$row['ide']."', '".$row['foldsub']."');";
	if($jmlIsi != "0")
	{
		$deleteFolder = "alert('Can\'t delete files');";
	}
	
	$bgRow = "";
	$idAuthorFold = $CFolder->detilAuthorFoldByIdFold($row['ide'], "idauthorfold");
	if($idAuthorFold != "")
	{
		$bgRow = " style=\"background-repeat:no-repeat;background-position:right;\" background=\"../../picture/imgShared.png\"";
	}
	
	$fileFold = $CFolder->detilFold($row['ide'], "filefold");
	$levelFolder="LEVEL".$_GET['foldSub'];
	
	$convFold = $CFolder->detilFold($row['ide'], "convfold");
	if($convFold == "N")
	{
		$size = $CFolder->display_size($CFolder->dirSize($pathFolder.$levelFolder."/".$fileFold));
	}
	if($convFold == "Y")
	{
		$size = $CFolder->display_size($CFolder->dirSize($pathFolderConvFold.$levelFolder."/".$fileFold));
	}
	
	$title = $row['namefold']."\nDate Modified : ".$row['tglbuat']."\nSize : ".$size;
	if($row['tipekonten'] == "folder")
	{
		$title = $row['namefold']."\nDate Modified : ".$row['tglbuat'];
	}
	//<tr onMouseOver=\"this.style.backgroundColor='#F2F9FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
	$html.="<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
            <td class=\"tdMyFolder\">
                <table width=\"100%\" border=\"0\">
                <tr>
                    <td width=\"5%\" align=\"center\" style=\"font-size:22px;color:#000080;font-weight:bold;font-family:Tahoma;\" onClick=\"parent.pilihBtnExpand('".$row['ide']."', '".$row['foldsub']."', '".$row['idfold']."', '".$row['tipekonten']."');\"  title=\"".$title."\">".$urutan++."</td>
                    <td width=\"59%\" onClick=\"parent.pilihBtnExpand('".$row['ide']."', '".$row['foldsub']."', '".$row['idfold']."', '".$row['tipekonten']."');\"  title=\"".$title."\">
                    	<table width=\"100%\" class=\"fontMyFolderList\" ".$bgRow.">
                        <tr>
                        	<td width=\"80\">Name</td><td width=\"10\">:</td>
							<td>
								<span style=\"color:#000080;\">".$CPublic->potongKarakter($row['namefold'], 80)."</span>
							</td>
                        </tr>
                        <tr>
                            <td>Created date</td>
							<td>:</td><td style=\"color:#000080;\">".$row['tglbuat']."</td>
                        </tr>
                        <tr>
                            <td>Content</td><td>:</td>
							<td style=\"color:#000080;\">".ucfirst($row['tipekonten'])." (<b>".$jmlIsi."</b>)</td>
                        </tr>
                        </table>
                    </td>
					
					<td width=\"40%\" align=\"right\" class=\"\">								
                    	<button class=\"btnStandarKecil\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['ide']."','detailFolder');\" style=\"width:75px;height:55px;\" title=\"Detail of this Folder\">
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
						<button class=\"btnStandarKecil\" type=\"button\" onClick=\"parent.pilihBtnExpand('".$row['ide']."', '".$row['foldsub']."', '".$row['idfold']."', '".$row['tipekonten']."');\" style=\"width:75px;height:55px;\" title=\"Go to this Folder Content\">
                            <table width=\"100%\" height=\"100%\">
                              <tr>
                                <td align=\"center\"><img src=\"../../picture/Outbox-blue-32.png\" height=\"25\"/> </td>
                                
                              </tr>
                              <tr>
                                <td align=\"center\">EXPAND</td>
                              </tr>
                            </table>
                        </button>
						&nbsp;
						<button class=\"btnStandarKecil\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['ide']."','editFolder');\" style=\"width:75px;height:55px;\" title=\"Edit this Folder\">
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
						<button class=\"btnStandarKecil\" onclick=\"".$deleteFolder."\" style=\"width:75px;height:55px;\">
                            <table width=\"100%\" height=\"100%\" title=\"Delete this Folder\">
                              <tr>
                                <td align=\"center\"><img src=\"../../picture/Button-Cross-blue-32.png\" height=\"25\"/> </td>
                                
                              </tr>
                              <tr>
                                <td align=\"center\">DELETE</td>
                              </tr>
                            </table>
                        </button>
                    </td>
                </table>
            </td>
        </tr>";
}
echo $html;
?>
</table>
</body>
</HTML>