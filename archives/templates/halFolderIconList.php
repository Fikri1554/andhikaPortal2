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
function dobelKlik(ide, foldSub, idFold, tipeKonten)
{
	parent.pilihBtnExpand(ide, foldSub, idFold, tipeKonten);
}

function showCollapse(urutan)
{
	//setTimeout(function()
	//{
		var jmlFold = document.getElementById('jmlFold').value;
		for(var i = 1; i <= jmlFold; i++)
		{
			if(urutan != i)
			{
				animatedcollapse.hide('setFold'+i);
				document.getElementById('statHidden'+i).value = "Y";
			}
		}
		
		var statHiddenSel = document.getElementById('statHidden'+urutan).value;
		
		if(statHiddenSel == "Y")
		{
			animatedcollapse.show('setFold'+urutan);
			document.getElementById('statHidden'+urutan).value = "N";
			return false;
		}
		else if(statHiddenSel == "N")
		{
			animatedcollapse.hide('setFold'+urutan);
			document.getElementById('statHidden'+urutan).value = "Y";
			return false;
		}
	//},300);
}

function allHidden()
{
	var jmlFold = document.getElementById('jmlFold').value;
	for(var i = 1; i <= jmlFold; i++)
	{
		animatedcollapse.hide('setFold'+i);
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

<body onClick="allHidden();" onLoad="loadScroll('folderIconList');" onUnload="saveScroll('folderIconList');"> 
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
	<td width="100%" height="100%" style="">
  
<?php
$idFoldRef = $_GET['idFoldRef'];
$foldSubGet = $_GET['foldSub'];
$nmFold = mysql_real_escape_string( $_GET['nmFold'] );
$descFold = mysql_real_escape_string( $_GET['descFold'] );
$contentTypeGet = $_GET['contentType'];

$dateTime = $CPublic->dateTimeGabung();
$tglbuat = $CPublic->indonesiaDate()." ".$CPublic->jamServer();
if($aksiGet == "simpanNewFolder")
{
	$idFoldLast = $CFolder->idFoldLast($foldSubGet, $idFoldRef);
	
	$fileFold = $userIdLogin."-".$idFoldLast."-".$dateTime;
	$addUsrdt = $CPublic->userWhoAct();
	$convFold = $CFolder->detilFoldByIdFold($idFoldRef, "convfold");

	$CKoneksi->mysqlQuery("INSERT INTO tblfolder(idfold, idfoldref, foldsub, folderowner, namefold, filefold, descfold, iddivisi, tipekonten, tglbuat, convfold, addusrdt) 
	VALUES ('".$idFoldLast."', '".$idFoldRef."',  '".$foldSubGet."', '".$userIdLogin."', '".$nmFold."', '".$fileFold."', '".$descFold."', '', '".$contentTypeGet."', '".$tglbuat."', '".$convFold."', '".$addUsrdt."');");
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
	if($convFold == "N")
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
$urutan = 0;

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
	$urutan++;
	
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
	
	$bgImgShared = "";
	$idAuthorFold = $CFolder->detilAuthorFoldByIdFold($row['ide'], "idauthorfold");
	if($idAuthorFold != "")
	{
		$bgImgShared = " style=\"background-repeat:no-repeat;background-position:top;background:url(../../picture/imgSharedKecil.png);
\"";
	}
	
	$popUpSetting = "<div class=\"elementDefault\" id=\"setFold".$urutan."\" style=\"display:none;width:auto;position:absolute;\">
                <table width=\"100\" cellpadding=\"2\" cellspacing=\"0\">
                <tr>
                    <td>
                    	<button class=\"btnStandarKecil\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['ide']."','detailFolder');\" style=\"width:95px;height:30px;\" title=\"Detail of this Folder\">
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
                    	<button class=\"btnStandarKecil\" onclick=\"parent.pilihBtnExpand('".$row['ide']."', '".$row['foldsub']."', '".$row['idfold']."', '".$row['tipekonten']."');\" style=\"width:95px;height:30px;\" title=\"Go to this Folder Content\">
                            <table width=\"100%\" height=\"100%\">
                              <tr>
                                <td align=\"left\"><img src=\"../../picture/Outbox-blue-32.png\" height=\"15\"/>&nbsp;&nbsp;EXPAND</td>
                              </tr>
                            </table>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>
                    	<button class=\"btnStandarKecil\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['ide']."','editFolder');\" style=\"width:95px;height:30px;\" title=\"Edit this Folder\">
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
                    	<button class=\"btnStandarKecil\" type=\"button\" onclick=\"".$deleteFolder."\" style=\"width:95px;height:30px;\" title=\"Delete this Folder\">
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
			
	$dobelKlik = "dobelKlik('".$row['ide']."', '".$row['foldsub']."', '".$row['idfold']."', '".$row['tipekonten']."');";

	$html.= "<div id=\"idDiv".$urutan."\" class=\"btnIconFolder\" onMouseOver=\"this.className='btnIconFolderHover'\" onMouseOut=\"this.className='btnIconFolder'\" style=\"width:120px;height:95px;top:145;float:left;margin-left:32px;margin-top:20px;\" onClick=\"allHidden();\" ondblclick=\"".$dobelKlik."\" oncontextmenu=\"showCollapse('".$urutan."');return false;\" title=\"".$title."\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"120\" height=\"95\" class=\"fontBtnKecil\" border=\"0\">
				  <tr>
					<td align=\"center\" valign=\"bottom\" height=\"30\" ".$bgImgShared."><img src=\"../../picture/Folder-blue-32.png\" height=\"25\"/> </td>
				  </tr>
				  <tr>
				  	<td align=\"center\" valign=\"top\" height=\"18\">Content : ".ucfirst($row['tipekonten'])." (".$jmlIsi.")</td>
				  </tr>
				  <tr>
					<td align=\"center\" valign=\"top\" style=\"color:#000080;\">
					".$popUpSetting ."
					".$CPublic->potongKarakter($row['namefold'], 15)."</td>
				  </tr>
				</table>
		   </div>
		   <input type=\"hidden\" id=\"statHidden".$urutan."\" value=\"Y\">
		   ";
}
echo $html;
?>
	<input type="hidden" id="jmlFold" value="<?php echo $urutan; ?>">
    </td>
</tr>

</table>
</body>

<?php
$setAllCollapse = "";
for($ii = 1; $ii <= $urutan; $ii++)
{
	$setAllCollapse.= "animatedcollapse.addDiv('setFold".$ii."', 'fade=1,speed=500,height=auto,overflow-y=scroll');";
}

echo "<script>".$setAllCollapse."</script>";
?>
</HTML>