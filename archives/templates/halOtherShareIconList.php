<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$pathFolder="../data/document/";
$pathFolderConvFold="../data/documentConvFold/";

$aksiGet = $_GET['aksi'];
$userIdOwnerGet = $_GET['userIdOwner'];
$idAuthorFoldGet = $_GET['idAuthorFold'];
$aksiGet = $_GET['aksi'];
$idFoldRefGet = $_GET['idFoldRef'];
$foldSubGet = $_GET['foldSub'];
$paramCariGet = $_GET['paramCari'];
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
				//document.getElementById('tes'+i).value = "lanjut";
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
		//document.getElementById('tes'+i).value = "tes"+i;
	}
}

animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
	//$: Access to jQuery
	//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
	//state: "block" or "none", depending on state
}
animatedcollapse.init();
</script>
    
<body onClick="allHidden();" onLoad="loadScroll('otherShareIconList');" onUnload="saveScroll('otherShareIconList');">
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
	<td width="100%" height="100%" style="">
<?php
$html = "";
$urutan = 0;
$urutanTombol = 1;

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
			$html.= tombol($CFolder, $urutan++, $urutanTombol++, $row, $row2, $size, $tipeKonten, $row['idauthorfold'], $CPublic, $jmlIsi);
		}
	}
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
		$html.= tombol($CFolder, $urutan++, $urutanTombol++, "", $row2, $size, $tipeKonten, $idAuthorFoldGet, $CPublic, $jmlIsi);
	}
	echo $html;
}
?>
		<input type="hidden" id="jmlFold" value="<?php echo $urutan; ?>">
    </td>
</tr>
</table>
</table>
</body>
<?php
function tombol($CFolder, $urutan, $urutanTombol, $row, $row2, $size, $tipeKonten, $idAuthorFold, $CPublic, $jmlIsi)
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
	
	$bgRow = " style=\"background-repeat:no-repeat;background-position:right;\" background=\"../../picture/imgSharedKecil.png\"";
	
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
	
	$popUpSetting= "<div class=\"elementDefault\" id=\"setFold".$urutanTombol."\" style=\"display:none;width:auto;position:absolute;\">
                <table width=\"100\" cellpadding=\"2\" cellspacing=\"0\">
                <tr>
                    <td>
                    	<button class=\"btnStandarKecil\" type=\"button\" onclick=\"".$onClickDetail."\" style=\"width:95px;height:30px;\" title=\"Detail of this Folder\">
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
                    	<button class=\"btnStandarKecil\" type=\"button\" onclick=\"".$onClickExpand."\" style=\"width:95px;height:30px;\" title=\"Go to this Folder Content\">
                            <table width=\"100%\" height=\"100%\">
                              <tr>
                                <td align=\"left\"><img src=\"../../picture/Outbox-blue-32.png\" height=\"15\"/>&nbsp;&nbsp;EXPAND</td>
                              </tr>
                            </table>
                        </button>
                    </td>
                </tr>
                <tr>
                </table>
            </div>";
	
	$dobelKlik = $onClickExpand;
	$html.= "<div id=\"idDiv".$urutanTombol."\" class=\"btnIconFolder\" onMouseOver=\"this.className='btnIconFolderHover'\" 
	onMouseOut=\"this.className='btnIconFolder'\" style=\"width:120px;height:95px;top:145;float:left;margin-left:32px;margin-top:20px;\" 
	onClick=\"allHidden();\" ondblclick=\"".$dobelKlik."\" oncontextmenu=\"showCollapse('".$urutanTombol."');return false;\" title=\"".$title."\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"120\" height=\"95\" class=\"fontBtnKecil\" border=\"0\">
				  <tr>
					<td align=\"center\" valign=\"bottom\" height=\"30\" ".$bgRow."><img src=\"../../picture/Folder-blue-32.png\" height=\"25\"/> </td>
				  </tr>
				  <tr>
				  	<td align=\"center\" valign=\"top\" height=\"18\">Content : ".ucfirst($row['tipekonten'])." (".$jmlIsi.")</td>
				  </tr>
				  <tr>
					<td align=\"center\" valign=\"top\" style=\"color:#000080;\">
					".$popUpSetting."
					".$CPublic->potongKarakter($row2['namefold'], 15)."</td>
				  </tr>
				</table>
		   </div>
		   <input type=\"hidden\" id=\"statHidden".$urutanTombol."\" value=\"Y\">
		   ";
	
	return $html;
}
$setAllCollapse = "";
for($ii = 1; $ii <= $urutan; $ii++)
{
	$setAllCollapse.= "animatedcollapse.addDiv('setFold".$ii."', 'fade=1,speed=500,height=auto,overflow-y=scroll');";
}

echo "<script>".$setAllCollapse."</script>";
?>
</HTML>