<?php
require_once("../../config.php");

$aksiGet = $_GET['aksi'];
$userIdOwnerGet = $_GET['userIdOwner'];
//$idAuthorFoldGet = $_GET['idAuthorFold'];
//echo "aaa".$idAuthorFoldGet;

$idFoldRefGet = $_GET['idFoldRef'];
$foldSubGet = $_GET['foldSub'];
$paramCariGet = mysql_real_escape_string($_GET['paramCari']);
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/jquery-1.4.2.js"></script>
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
	var bukaFold = document.getElementById('bukaFold'+idFoldSelect).value;
	var owner = document.getElementById('owner').value;
	var idAuthorFold = parent.parent.document.getElementById('idAuthorFold').value;
	
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
	
	//var idAuthorFold = parent.document.getElementById('idAuthorFold').value;
	var idAuthorFold = parent.parent.document.getElementById('idAuthorFold').value;
	
	parent.window.frames["halFolderSide2"].frameElement.src = "halOtherSharedFolderSide2.php?idFold="+idFoldSelect+"&idAuthorFold="+idAuthorFold;
		//parent.parent.document.getElementById('btnNewFolder').disabled = true;
}

function btnKlikfold(idFoldSelect)
{
	//46.4
	
	var allIdFold = "";
	
	var splitNilai = idFoldSelect.split("."); //POTONG IDFOLD YANG DIKLIK MISAL IDFOLD 43.1.2

	// CARI BERAPA FOLDER YANG DILEWATIN MISAL IDFOLD 43.1.2 YG DILEWATIN, IDFOLD 43, IDFOLD 43.1, IDFOLD 43.1.2 
	var pjgSplit = splitNilai.length;
	
	if(idFoldSelect != "")
	{
		for(i = 0; i <= (pjgSplit-1); i++)
		{
			//alert(pjgSplit+" "+i);
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

<body onLoad="addEvents();loadScroll('otherSharedFolderSide1');" onUnload="saveScroll('otherSharedFolderSide1');">
<input type="hidden" id="idFoldSelectSeb">
<input type="hidden" id="btnKlikfold" onClick="btnKlikfold('<?php echo $idFoldRefGet; ?>');" value="btnKlikFold"> <!-- folder yg sedang aktiv -->
<input type="hidden" id="btnKlikfoldHalAwal" onClick="btnKlikfoldHalAwal('<?php echo $idFoldRefGet; ?>');" value="btnKlikfoldHalAwal">
<input type="hidden" id="owner" value="<?php echo $userIdOwnerGet;?>"/>

<?php
$html = "";
$urutan = 0;

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
		$ideFolder = $row['idefold']; // ide folder yg akan dibuka
		
		$nameFold = $CFolder->detilFold($ideFolder, "namefold");
		$idFold = $CFolder->detilFold($ideFolder, "idfold");
		$foldSub = $CFolder->detilFold($ideFolder, "foldsub");
		$tipeKonten = $CFolder->detilFold($ideFolder, "tipekonten");
		$tglBuat = $CFolder->detilFold($ideFolder, "tglbuat");		
		//echo $ideFolder." | ".$idFold." | ".$foldSub."<br>";
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
		
		//$size = $CFolder->display_size($CFolder->dirSize($pathFolder.$levelFolder."/".$fileFold));$pathFolderConvFold="../data/documentConvFold/";
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
			
		treeFold($html, $idFold, $foldSub, $CKoneksi, $CFolder, $CFile, $userIdOwnerGet, $CPublic, $row['idauthorfold']);	
			
		$html .= "</li>";
	}
	
}

echo "<ul id=\"LinkedList1\" class=\"LinkedList fontMyFolderList\" style=\"list-style:none;margin-left:0;\">".$html."</ul>";

function treeFold(&$html, $idFoldGet, $foldSub, $CKoneksi, $CFolder, $CFile, $userIdLogin, $CPublic, $idAuthoFold)
{
	$sql = $CKoneksi->mysqlQuery("SELECT * FROM tblfolder WHERE idfoldref='".$idFoldGet."' AND folderowner=".$userIdLogin." AND deletests=0 ORDER BY namefold ASC");

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
		$html .= "<ul ".$classUl.">";
	}
	
	$allIdFold = "";
	$urutan = 0;
	while ($row = $CKoneksi->mysqlFetch($sql)) 
	{
		$urutan++;
			
		if($row['tipekonten'] == "folder") // jika tipe konten folder maka ambil jumlah folder yang berada di level setelahnya
		{
			$jmlIsi = $CFolder->jmlFolder($row['idfold']);
		}
		else if($row['tipekonten'] == "file") // jika tipe konten file maka ambil jumlah file yang berada di level setelahnya
		{
			$jmlIsi = $CFile->jmlFile($row['idfold']);
		}
		$pathFolder="../data/document/";
		$fileFold = $CFolder->detilFold($row['ide'], "filefold");
		$levelFolder="LEVEL".$row['foldsub'];
		
		//$size = $CFolder->display_size($CFolder->dirSize($pathFolder.$levelFolder."/".$fileFold));
		$pathFolderConvFold="../data/documentConvFold/";
		$convFold = $CFolder->detilFold($row['ide'], "convfold");
		if($convFold == "N")
		{
			$size = $CFolder->display_size($CFolder->dirSize($pathFolder.$levelFolder."/".$fileFold));
		}
		if($convFold == "Y")
		{
			$size = $CFolder->display_size($CFolder->dirSize($pathFolderConvFold.$levelFolder."/".$fileFold));
		}
		$title = $row['namefold']."\nContent : ".ucfirst($row['tipekonten'])." (".$jmlIsi.")\nDate Modified : ".$row['tglbuat']."\nSize : ".$size;
		if($row['tipekonten'] == "folder")
		{
			$title = $row['namefold']."\nContent : ".ucfirst($row['tipekonten'])." (".$jmlIsi.")\nDate Modified : ".$row['tglbuat'];
		}
		
		$html .= "<li style=\"list-style:none;color:#000080;\" id=\"id".$row['idfold']."\" oncontextmenu=\"return false;\" title=\"".$title."\">";
		
		$pilihBtnExpand= "parent.parent.pilihBtnExpand('".$row['ide']."', '".$row['foldsub']."', '".$row['idfold']."', '".$row['tipekonten']."', '".$idAuthoFold."');";
		$html .= "<img id=\"imgFold".$row['idfold']."\" src=\"../../picture/folder-horizontal.png\" height=\"20\" onClick=\" ".$pilihBtnExpand." klikFold('".$row['idfold']."'); \" >&nbsp;&nbsp;";
		$html.= "<div id=\"idSpan".$row['idfold']."\" onClick=\" ".$pilihBtnExpand." klikFold('".$row['idfold']."'); \" 
		onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\"  
		style=\"display:inline;\">".$CPublic->potongKarakter($row['namefold'], 30)."</div>";
		
		$html .="<input type=\"hidden\" id=\"bukaFold".$row['idfold']."\" value=\"N\">";
		
		treeFold($html, $row['idfold'], $row['foldsub'], $CKoneksi, $CFolder, $CFile, $userIdLogin, $CPublic, $idAuthoFold);
		
		$html .= "</li>";
	}
	
	if ($CKoneksi->mysqlNRows($sql) > 0)
	{
		$html .= "</ul>";
	}
}
?>
 
</body>
