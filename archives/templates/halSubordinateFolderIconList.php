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
<body onClick="allHidden();">
    
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
	<td width="100%" height="100%" style="">
<?php
$html = "";
$urutan = 0;
$urutanTombol = 1;

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
	$html.= tombol($CFolder, $urutan, $urutanTombol++, $row, $size, $tipeKonten, $CPublic, $jmlIsi);
}

echo $html;
?>
	<input type="hidden" id="jmlFold" value="<?php echo $urutan; ?>">
    </td>
</tr>
</table>
</body>

<?php
function tombol($CFolder, $urutan, $urutanTombol, $row, $size, $tipeKonten, $CPublic, $jmlIsi)
{	
	$bgRow = " style=\"background-repeat:no-repeat;background-position:right;\" background=\"../../picture/imgSubordinateKecil.png\"";

	$onClickDetail = "parent.openThickboxWindow('".$row['ide']."','detailSubordinateFolder');";
	$onClickExpand = "parent.pilihBtnExpand('".$row['ide']."', '".$row['foldsub']."', '".$row['idfold']."', '".$row['tipekonten']."');";

	$pathFolder="../data/document/";
	$fileFold = $CFolder->detilFold($row['ide'], "filefold");
	$levelFolder="LEVEL".$row['foldsub'];
	
	$sizeTitle = $CFolder->display_size($CFolder->dirSize($pathFolder.$levelFolder."/".$fileFold));
	$title = $row['namefold']."\nContent : ".ucfirst($row2['tipekonten'])." (".$jmlIsi.")\nDate Modified : ".$row['tglbuat']."\nSize : ".$sizeTitle;
	if($row['tipekonten'] == "folder")
	{
		$title = $row['namefold']."\nContent : ".ucfirst($row['tipekonten'])." (".$jmlIsi.")\nDate Modified : ".$row['tglbuat'];
	}
	
	$popUpSetting= "<div class=\"elementDefault\" id=\"setFold".$urutanTombol."\" style=\"display:none;width:auto;position:absolute;\">
                <table width=\"100\" cellpadding=\"2\" cellspacing=\"0\">
                <tr>
                    <td>
                    	<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"".$onClickDetail."\" style=\"width:95px;height:30px;\" title=\"Detail of this Folder\">
                            <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
                              <tr>
                                <td align=\"left\"><img src=\"../../picture/Information-blue-32.png\" height=\"15\"/>&nbsp;&nbsp;DETAIL</td>
                              </tr>
                            </table>
                        </button>
                     </td>
                </tr>
                <tr>
                    <td>
                    	<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"".$onClickExpand."\" style=\"width:95px;height:30px;\" title=\"Go to this Folder Content\">
                            <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
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
	onMouseOut=\"this.className='btnIconFolder'\" style=\"width:120px;height:95px;top:145;float:left;margin-left:32;margin-top:20;\" 
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
					".$CPublic->potongKarakter($row['namefold'], 15)."</td>
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