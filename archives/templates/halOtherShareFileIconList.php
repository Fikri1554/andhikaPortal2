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

<body onClick="allHidden();" onLoad="loadScroll('otherShareFileList');" onUnload="saveScroll('otherShareFileList');"> 
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
	<td width="100%" height="100%" style="">
<?php
$aksiGet = $_GET['aksi'];
$idFoldGet = $_GET['idFold'];
$foldSubGet = $_GET['foldSub'];
$userIdOwnerGet = $_GET['userIdOwner'];
$idAuthorFoldGet = $_GET['idAuthorFold'];
$paramCariGet = $_GET['paramCari'];

$html = "";
$urutan = 0;
if($halamanGet == "cari")
{
	$query = $CKoneksi->mysqlQuery("SELECT * FROM mstdoc WHERE idfold='".$idFoldGet."' AND fileowner =".$userIdOwnerGet." AND namedoc LIKE '%".$paramCariGet."%' AND deletests=0 ORDER BY namedoc ASC");
}
else
{
	$query = $CKoneksi->mysqlQuery("SELECT * FROM mstdoc WHERE idfold='".$idFoldGet."' AND fileowner =".$userIdOwnerGet." AND deletests=0 ORDER BY namedoc ASC");
}
while($row = $CKoneksi->mysqlFetch($query))
{
	$urutan++;
	
	$idFold = $CFile->detilFile($row['ide'], "idfold");
	$fileFold = $CFolder->detilFoldByIdFold($idFold, "filefold");
	$foldSub = $CFolder->detilFoldByIdFold($idFold, "foldsub");
	$levelFolder="LEVEL".$foldSub;
	$convFold = $row['convfold'];
	$pathDoc = $row['pathdoc'];
	$fileDoc = $row['filedoc'];
	
	$pathFile = "";
	if($convFold == "N")// JIKA FILE BUKAN DARI CONVERT FOLDER USER DATA / INPUT DARI SISTEM MY FOLDER
	{
		$pathFile = $pathFolder.$levelFolder."/".$fileFold."/".$row['filedoc'];
	}
	else if($convFold == "Y") // JIKA FILE BERASAL DARI CONVERT FOLDER USER DATA
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
	
	$aksesDetail = $CFolder->detilAuthorFold($idAuthorFoldGet, "detailfile");
	$aksesOpen = $CFolder->detilAuthorFold($idAuthorFoldGet, "openfile");
	$aksesUpload = $CFolder->detilAuthorFold($idAuthorFoldGet, "uploadfile");

	$onClickDetail = "alert('You do not have permissions');";
	$onClickOpen = "alert('You do not have permissions');";
	
	if($aksesDetail == "Y")
	{
		$onClickDetail = "parent.openThickboxWindow('".$row['ide']."','detailOtherShareFile');";
	}
	if($aksesOpen == "Y")
	{
		$onClickOpen = $clickBtnOpen;
	}
	
	$expWktUpload = explode(" ", $row['wktupload']);
	$expWktUpload1 = explode("-", $expWktUpload[0]);
	$expWktUpload2 = $expWktUpload[1];
	
	$tglUpload = $expWktUpload1[2];
	$blnUpload = ucfirst( strtolower( $CPublic->bulanSetengah( $expWktUpload1[1], "ind") ) );
	$thnUpload = $expWktUpload1[0];
	
	$wktUpload = $tglUpload." ".$blnUpload." ".$thnUpload." ".$expWktUpload2;
	
	$fileSizeTitle = $CFolder->display_size(filesize($pathFolder.$levelFolder."/".$fileFold."/".$row['filedoc']));
	$title = $row['namedoc']."\nDate Modified :".$row['wktupload']."\nSize : ".$fileSizeTitle;
	
	$popUpSetting.= "<div class=\"elementDefault\" id=\"setFile".$urutan."\" style=\"display:none;width:auto;position:absolute;\">
                <table width=\"100\" cellpadding=\"2\" cellspacing=\"0\">
                <tr>
                    <td>
                    	<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"".$onClickDetail."\" style=\"width:95px;height:30px;\" title=\"Detail of this File\">
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
                    	<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"".$onClickOpen."\" style=\"width:95px;height:30px;\" title=\"Open/Save this File to LocalDisk\">
                            <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
                              <tr>
                                <td align=\"left\"><img src=\"".$iconShow."\" height=\"15\"/>&nbsp;&nbsp;OPEN/SAVE</td>
                              </tr>
                            </table>
                        </button>
                    </td>
                </tr>";
	if($aksesUpload == "Y")
	{
		$popUpSetting.= btnEdit($row['ide']);
	}
	else
	{
		$popUpSetting.= "";
	}			
	$popUpSetting.="
                </table>
            </div>";
	
	$html.= "<div class=\"btnIconFolder\" onMouseOver=\"this.className='btnIconFolderHover'\" onMouseOut=\"this.className='btnIconFolder'\" style=\"width:120px;height:95px;top:145;float:left;margin-left:32;margin-top:20;\" onClick=\"allHidden();\" ondblclick=\"".$clickBtnOpen."\" oncontextmenu=\"showCollapse('".$urutan."');return false;\" title=\"".$title."\">
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
	
	/*$html.="<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
				<td class=\"tdMyFolder\">
					<table width=\"100%\" border=\"0\">
					<tr>
						<td width=\"5%\" align=\"center\" style=\"font-size:22px;color:#000080;font-weight:bold;font-family:Tahoma;\" onClick=\"".$onClickOpen."\">".$urutan++."</td>
						<td width=\"69%\" onClick=\"".$onClickOpen."\">
							<table width=\"100%\" class=\"fontMyFolderList\" ".$bgRow.">
							<tr>
								<td width=\"80\">Name</td><td width=\"10\">:</td>
								<td style=\"color:#000080;\">".$row['namedoc']."</td>
							</tr>
							<tr>
								<td>Upload date</td><td>:</td>
								<td style=\"color:#000080;\">".$wktUpload."</td>
							</tr>
							<tr>
								<td>Type file</td><td>:</td>
								<td style=\"color:#000080;\">".$row['extdoc']."</td>
							</tr>
							</table>
						</td>
						<td width=\"35%\" align=\"right\">".$hrefOpenFile."
							<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onClick=\"".$onClickDetail."\" style=\"width:75px;height:55px;\" title=\"Detail of this Other Shared Folder\">
								<table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
								  <tr>
									<td align=\"center\"><img src=\"../../picture/Information-blue-32.png\" height=\"25\"/> </td>
									
								  </tr>
								  <tr>
									<td align=\"center\">DETAIL</td>
								  </tr>
								</table>
							</button>
							&nbsp;
							<button type=\"button\" class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" onClick=\"".$onClickOpen."\" style=\"width:75px;height:55px;\" title=\"Open/Save this Other Shared File\">
								<table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
								  <tr>
									<td align=\"center\"><img src=\"".$iconShow."\" height=\"28\"/></td>
								  </tr>
								  <tr>
									<td align=\"center\">OPEN/SAVE</td>
								  </tr>
								</table>
							</button>";
	if($aksesUpload == "Y")
	{
		$html.= btnEdit($row['ide']);
	}
	else
	{
		$html.= "";
	}
    $html.="            </td>
					</tr>
					</table>
				</td>
			</tr>";*/
}
echo $html;
?>
    <input type="hidden" id="jmlFile" value="<?php echo $urutan; ?>">
    </td></tr>
</table>
</body>

<?php
function btnEdit($ide)
{
	$html = "";
	/*$html.= "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"parent.openThickboxWindow('".$ide."','editOtherShareFile');\" style=\"width:75px;height:55px;\" title=\"Edit this Other Shared File\">
				  <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
					<tr>
					  <td align=\"center\"><img src=\"../../picture/Auction-blue-32.png\" height=\"28\"/> </td>
					</tr>
					<tr>
					  <td align=\"center\">EDIT</td>
					</tr>
				  </table>
			  </button>";*/
	//for icon html
	$html.= "<tr><td>
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"parent.openThickboxWindow('".$ide."','editOtherShareFile');\" style=\"width:95px;height:30px;\" title=\"Edit this File\">
					<table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
					  <tr>
						<td align=\"left\"><img src=\"../../picture/Auction-blue-32.png\" height=\"15\"/>&nbsp;&nbsp;EDIT</td>
					  </tr>
					</table>
				</button></td></tr>";
	return $html;
}
$setAllCollapse = "";
for($ii = 1; $ii <= $urutan; $ii++)
{
	$setAllCollapse.= "animatedcollapse.addDiv('setFile".$ii."', 'fade=1,speed=500,height=auto,overflow-y=scroll');";
}

echo "<script>".$setAllCollapse."</script>";
?>