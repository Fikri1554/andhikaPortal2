<!DOCTYPE HTML><?php
require_once("../../config.php");

//$idFoldGet = $_GET['idFold']; id fold pada folder
$paramCariGet = mysql_real_escape_string( $_GET['paramCari'] );

$aksiGet = $_GET['aksi'];
$userIdOwnerGet = $_GET['userIdOwner'];
$empNoSharedGet = $_GET['empNoShared'];

$idFoldRefGet = $_GET['idFold']; //id fold pada ownshared
$foldSubGet = $_GET['foldSub'];
$idAuthorFoldGet = $_GET['idAuthorFold'];
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../../js/jquery.min.js"></script>

<script type="text/javascript" src="../../js/animatedcollapse.js"></script>
<style type="text/css">
ul.LinkedList { display: block; }
/* ul.LinkedList ul { display: none; } */
.HandCursorStyle { cursor: pointer; cursor: hand; }  /* For IE */
</style>

<script type="text/JavaScript">
// Add this to the onload event of the BODY element
function addEvents() 
{
	activateTree(document.getElementById("LinkedList1"));
}

// This function traverses the list and add links 
// to nested list items
function activateTree(oList)
{
  // Collapse the tree
	for (var i=0; i < oList.getElementsByTagName("ul").length; i++) {
	  oList.getElementsByTagName("ul")[i].style.display="none";            
	}                                                                  
	// Add the click-event handler to the list items
	if (oList.addEventListener) {
	  oList.addEventListener("click", toggleBranch, false);
	} else if (oList.attachEvent) { // For IE
	  oList.attachEvent("onclick", toggleBranch);
	}
	// Make the nested items look like links
	addLinksToBranches(oList);
}

// This is the click-event handler
function toggleBranch(event) 
{
	var oBranch, cSubBranches;
	if (event.target) {
	  oBranch = event.target;
	} else if (event.srcElement) { // For IE
	  oBranch = event.srcElement;
	}
	cSubBranches = oBranch.getElementsByTagName("ul");
	if (cSubBranches.length > 0) {
	  if (cSubBranches[0].style.display == "block") {
		cSubBranches[0].style.display = "none";
	  } else {
		cSubBranches[0].style.display = "block";
	  }
	}
}

// This function makes nested list items look like links
function addLinksToBranches(oList) 
{
	var cBranches = oList.getElementsByTagName("li");
	var i, n, cSubBranches;
	if (cBranches.length > 0) {
	  for (i=0, n = cBranches.length; i < n; i++) {
		cSubBranches = cBranches[i].getElementsByTagName("ul");
		if (cSubBranches.length > 0) {
		  addLinksToBranches(cSubBranches[0]);
		  /*cBranches[i].className = "HandCursorStyle";
		  cBranches[i].style.color = "blue";
		  cSubBranches[0].style.color = "black";
		  cSubBranches[0].style.cursor = "auto";*/
		  
		  /*cBranches[i].onmouseover = function onmouseover(){
				this.style.background='#DDF0FF';	}
		  cBranches[i].onmouseout = function onmouseout(){
				this.style.background='#FFFFFF';	}
		  cSubBranches[0].onmouseover = function onmouseover(){
				this.style.background='#FFFFFF';	}*/
		}
		cBranches[i].className = "HandCursorStyle";
	  }
	}
}

function klikFold(idFoldSelect)
{
	document.getElementById('id'+idFoldSelect).click(); //ID ELEMENT DARI <LI> YANG DIKLIK
	//alert(idFoldSelect);
	var bukaFold = document.getElementById('bukaFold'+idFoldSelect).value;
	var owner = document.getElementById('owner').value;
	if(bukaFold == "N")
	{
		document.getElementById('bukaFold'+idFoldSelect).value = "Y";
		document.getElementById('imgFold'+idFoldSelect).src = "../../picture/folder-horizontal-open.png";
	}
	else if(bukaFold == "Y")
	{
		document.getElementById('bukaFold'+idFoldSelect).value = "N";
		document.getElementById('imgFold'+idFoldSelect).src = "../../picture/folder-horizontal.png";
	}
	
	var idFoldSelectSeb = document.getElementById('idFoldSelectSeb').value; // ID ELEMENT DARI KLIK <SPAN> SEBELUMNYA 
	if(idFoldSelectSeb != "") // JIKA SEBELUMNYA PERNAH DIKLIK MAKA ID ELEMENT SEBELUMNYA DI NETRALKAN LAGI BACKGROUND NYA 
	{
		document.getElementById(idFoldSelectSeb).style.backgroundColor='#FFFFFF';
		document.getElementById(idFoldSelectSeb).onmouseover = function onmouseover(){
					this.style.backgroundColor='#DDF0FF';	}
		document.getElementById(idFoldSelectSeb).onmouseout = function onmouseout(){
					this.style.backgroundColor='#FFFFFF';	}
	}
	
	document.getElementById('idSpan'+idFoldSelect).style.backgroundColor='#DDF0FF';
	document.getElementById('idSpan'+idFoldSelect).onmouseover = function onmouseover(){
				this.style.backgroundColor='#DDF0FF';	}
	document.getElementById('idSpan'+idFoldSelect).onmouseout = function onmouseout(){
				this.style.backgroundColor='#DDF0FF';	}
	document.getElementById('idFoldSelectSeb').value = 'idSpan'+idFoldSelect;
	
	parent.window.frames["halFolderSide2"].frameElement.src = "halOwnSharedFolderSide2.php?idFold="+idFoldSelect+"&userIdOwner="+owner;
		//parent.parent.document.getElementById('btnNewFolder').disabled = true;
}

function btnKlikfold(idFoldSelect)
{
	var allIdFold = "";
	
	var splitNilai = idFoldSelect.split("."); //POTONG IDFOLD YANG DIKLIK MISAL IDFOLD 43.1.2

	// CARI BERAPA FOLDER YANG DILEWATIN MISAL IDFOLD 43.1.2 YG DILEWATIN, IDFOLD 43, IDFOLD 43.1, IDFOLD 43.1.2 
	var pjgSplit = splitNilai.length;
	if(idFoldSelect!="")
	{
		for(i = 0; i <= (pjgSplit-1); i++)
		{
			if(i == 0)
			{
				allIdFold += splitNilai[i];
			}
			else
			{
				allIdFold += "."+splitNilai[i];
			}
			//MISAL IDFOLD 43.1.2, MAKA FUNCTION DIBAWAH DIJALANKAN 3X YAITU klikFold('43'), klikFold('43.1'), klikFold('43.1.2'), DAN DIDAPAT HASIL AKHIR YAITU  klikFold('43.1.2')
			if (document.getElementById("id"+allIdFold) !== null)
			{
				klikFold(allIdFold);
			}
		}
	}
}

</script>

</head>

<body onLoad="addEvents();loadScroll('ownSharedFolderSide1');" onUnload="saveScroll('ownSharedFolderSide1');">
<input type="hidden" id="idFoldSelectSeb">
<input type="hidden" id="btnKlikfold" onClick="btnKlikfold('<?php echo $idFoldRefGet; ?>');" value="btnKlikFold"> <!-- folder yg sedang aktiv -->
<input type="hidden" id="owner" value="<?php echo $userIdLogin;?>" />

<?php
$html = "";
$urutan = 0;

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
		$ideFolder = $row['idefold']; // ide folder yg akan dibuka
		
		$nameFold = $CFolder->detilFold($ideFolder, "namefold");
		$idFold = $CFolder->detilFold($ideFolder, "idfold");
		$foldSub = $CFolder->detilFold($ideFolder, "foldsub");
		$tipeKonten = $CFolder->detilFold($ideFolder, "tipekonten");
		$tglBuat = $CFolder->detilFold($ideFolder, "tglbuat");
		
		// untuk title
		if($tipeKonten == "folder") // jika tipe konten folder maka ambil jumlah folder yang berada di level setelahnya
		{
			$jmlIsi = $CFolder->jmlFolder($idFold);
		}
		else if($tipeKonten == "file") // jika tipe konten file maka ambil jumlah file yang berada di level setelahnya
		{
			$jmlIsi = $CFile->jmlFile($idFold);
		}
		$pathFolder="../data/document/";
		$fileFold = $CFolder->detilFold($ideFolder, "filefold");
		$levelFolder="LEVEL".$foldSub;
		
			//$size = $CFolder->display_size($CFolder->dirSize($pathFolder.$levelFolder."/".$fileFold));
		$pathFolderConvFold="../data/documentConvFold/";
		$convFold = $CFolder->detilFold($row['idefold'], "convfold");
		if($convFold == "N")
		{
			$size = $CFolder->display_size($CFolder->dirSize($pathFolder.$levelFolder."/".$fileFold));
		}
		if($convFold == "Y")
		{
			$size = $CFolder->display_size($CFolder->dirSize($pathFolderConvFold.$levelFolder."/".$fileFold));
		}
		
		$title = $nameFold."\nContent : ".ucfirst($tipeKonten)." (".$jmlIsi.")\nDate Modified : ".$tglBuat."\nSize : ".$size;
		if($tipeKonten == "folder")
		{
			$title = $nameFold."\nContent : ".ucfirst($tipeKonten)." (".$jmlIsi.")\nDate Modified : ".$tglBuat;
		}
		// end of title
		
		$html .= "<li style=\"list-style:none;color:#000080;\" id=\"id".$idFold."\" oncontextmenu=\"return false;\" title=\"".$title."\">";
		
		$pilihBtnExpand= "parent.parent.pilihBtnExpand('".$ideFolder."', '".$foldSub."', '".$idFold."', '".$tipeKonten."', '".$row['idauthorfold']."');";
		$html .= "<img id=\"imgFold".$idFold."\" src=\"../../picture/folder-horizontal.png\" height=\"20\" onClick=\" ".$pilihBtnExpand." klikFold('".$idFold."'); \" >&nbsp;&nbsp;";
		
		$html.= "<div id=\"idSpan".$idFold."\" onClick=\" ".$pilihBtnExpand." klikFold('".$idFold."'); \" 
		onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\"  
		style=\"display:inline;\">".$CPublic->potongKarakter($nameFold, 30)."</div>";
		
		$html .="<input type=\"hidden\" id=\"bukaFold".$idFold."\" value=\"N\">";
		
		//treeFold($html, "", "", $CKoneksi, $CFolder, $CFile, $userIdLogin, $CPublic, $ideFolder, $row['idauthorfold']);
		treeFold($html, $idFold, $foldSub, $CKoneksi, $CFolder, $CFile, $userIdOwnerGet, $CPublic, $row['idauthorfold']);	
			
		$html .= "</li>";
	}
}
echo "<ul id=\"LinkedList1\" class=\"LinkedList fontMyFolderList\" style=\"list-style:none;margin-left:0;\">".$html."</ul>";

function treeFold(&$html, $idFoldGet, $foldSub, $CKoneksi, $CFolder, $CFile, $userIdLogin, $CPublic, $idAuthoFold)
{
	/*$sqlIde = "";
	if($idFoldGet == "")
	{
		$sqlIde = "ide ='".$ideFolder."' AND";
	}*/
	
	$sql = $CKoneksi->mysqlQuery("SELECT * FROM tblfolder WHERE idfoldref='".$idFoldGet."' AND folderowner=".$userIdLogin." AND deletests=0 ORDER BY namefold ASC");
	
	/*if($idFoldGet != "")
	{*/
		if ($CKoneksi->mysqlNRows($sql) > 0) 
		{
			if($foldSub == "")
			{
				$classUl = "id=\"LinkedList1\" class=\"LinkedList fontMyFolderList\" style=\"list-style:none;margin-left:0;\"";
			}
			else if($foldSub != "")
			{
				$classUl = "";
			}
			$html .= "<ul ".$classUl.">
			";
		}
	//}
	
	$allIdFold = "";
	$urutan = 0;
	while ($row2 = $CKoneksi->mysqlFetch($sql)) 
	{
		$urutan++;
			
		if($row2['tipekonten'] == "folder") // jika tipe konten folder maka ambil jumlah folder yang berada di level setelahnya
		{
			$jmlIsi = $CFolder->jmlFolder($row2['idfold']);
		}
		else if($row2['tipekonten'] == "file") // jika tipe konten file maka ambil jumlah file yang berada di level setelahnya
		{
			$jmlIsi = $CFile->jmlFile($row2['idfold']);
		}
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
		
		$title = $row2['namefold']."\nContent : ".ucfirst($row2['tipekonten'])." (".$jmlIsi.")\nDate Modified : ".$row2['tglbuat']."\nSize : ".$size;
		if($row2['tipekonten'] == "folder")
		{
			$title = $row2['namefold']."\nContent : ".ucfirst($row2['tipekonten'])." (".$jmlIsi.")\nDate Modified : ".$row2['tglbuat'];
		}
		
		$html .= "<li style=\"list-style:none;color:#000080;\" id=\"id".$row2['idfold']."\" oncontextmenu=\"return false;\" title=\"".$title."\">";
		
		$pilihBtnExpand= "parent.parent.pilihBtnExpand('".$row2['ide']."', '".$row2['foldsub']."', '".$row2['idfold']."', '".$row2['tipekonten']."', '".$idAuthorFoldGet."');";
		$html .= "<img id=\"imgFold".$row2['idfold']."\" src=\"../../picture/folder-horizontal.png\" height=\"20\" onClick=\" ".$pilihBtnExpand." klikFold('".$row2['idfold']."'); \" >&nbsp;&nbsp;";
		
		$html.= "<div id=\"idSpan".$row2['idfold']."\" onClick=\" ".$pilihBtnExpand." klikFold('".$row2['idfold']."'); \" 
		onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\"  
		style=\"display:inline;\">".$CPublic->potongKarakter($row2['namefold'], 30)."</div>";
		
		$html .="<input type=\"hidden\" id=\"bukaFold".$row2['idfold']."\" value=\"N\">";
		
		treeFold($html, $row2['idfold'], $row2['foldsub'], $CKoneksi, $CFolder, $CFile, $userIdLogin, $CPublic, $idAuthorFold);
		
		$html .= "</li>";
	}
	
	/*if($idFoldGet != "")
	{*/
		if ($CKoneksi->mysqlNRows($sql) > 0)
		{
			$html .= "</ul>";
		}
	//}
}

//$html = "";

//treeFold($html, "", "", $CKoneksi, $CFolder, $CFile, $userIdLogin, $CPublic);

//echo $html;
?>
 
</body>
<?php
/*function tombol($CFolder, $urutan, $row, $row2, $size, $tipeKonten, $idAuthorFold)
{	
	$bgRow = " style=\"background-repeat:no-repeat;background-position:right;\" background=\"../../picture/imgShared.png\"";

	$onClickDetail = "parent.parent.openThickboxWindow('".$row2['ide']."','detailOwnShare');";
	$onClickExpand = "parent.parent.pilihBtnExpand('".$row2['ide']."', '".$row2['foldsub']."', '".$row2['idfold']."', '".$row2['tipekonten']."', '".$idAuthorFold."');";
	$html = "";
	$html = "<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
				<td class=\"tdMyFolder\">
					<table width=\"100%\" border=\"0\">
					<tr>
						<td width=\"5%\" align=\"center\" style=\"font-size:22px;color:#000080;font-weight:bold;font-family:Tahoma;\" onClick=\"".$onClickExpand."\">".$urutan++."</td>
						<td width=\"74%\" onClick=\"".$onClickExpand."\">
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
							<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onClick=\"".$onClickDetail."\" style=\"width:90px;height:55px;\" title=\"Detail of this Own Shared Folder\">
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
							<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onClick=\"".$onClickExpand."\" style=\"width:90px;height:55px;\" title=\"Go to this Own Shared Folder Content\">
								<table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
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
}*/
?>
</HTML>