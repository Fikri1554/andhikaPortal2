<?php
require_once("../../config.php");

$idFoldGet = $_GET['idFold'];
$aksiGet = $_GET['aksi'];
$paramCariGet = mysql_real_escape_string( $_GET['paramCari'] );
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
	
	parent.window.frames["halFolderSide2"].frameElement.src = "halFolderSide2.php?idFold="+idFoldSelect;
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
			
			klikFold(allIdFold);
		}
	}
}

</script>

</head>

<body onLoad="addEvents();loadScroll('folderSide1');" onUnload="saveScroll('folderSide1');">
<input type="hidden" id="idFoldSelectSeb">
<input type="hidden" id="btnKlikfold" onClick="btnKlikfold('<?php echo $idFoldGet; ?>');" value="btnKlikFold">

<?php

function treeFold(&$html="", $idFoldGet, $foldSub, $CKoneksi, $CFolder, $CFile, $userIdLogin, $CPublic)
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
		$html .= "<ul ".$classUl.">
		";
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
		$pathFolderConvFold="../data/documentConvFold/";
		$fileFold = $CFolder->detilFold($row['ide'], "filefold");
		$levelFolder="LEVEL".$row['foldsub'];
		
		//$size = $CFolder->display_size($CFolder->dirSize($pathFolder.$levelFolder."/".$fileFold));
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
		
		$pilihBtnExpand= "parent.parent.pilihBtnExpand('".$row['ide']."', '".$row['foldsub']."', '".$row['idfold']."', '".$row['tipekonten']."');";
		$html .= "<img id=\"imgFold".$row['idfold']."\" src=\"../../picture/folder-horizontal.png\" height=\"20\" onClick=\" ".$pilihBtnExpand." klikFold('".$row['idfold']."'); \" >&nbsp;&nbsp;";
		$html.= "<div id=\"idSpan".$row['idfold']."\" onClick=\" ".$pilihBtnExpand." klikFold('".$row['idfold']."'); \" 
		onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\"  
		style=\"display:inline;\">".$CPublic->potongKarakter($row['namefold'], 30)."</div>";
		
		$html .="<input type=\"hidden\" id=\"bukaFold".$row['idfold']."\" value=\"N\">";
		
		treeFold($html, $row['idfold'], $row['foldsub'], $CKoneksi, $CFolder, $CFile, $userIdLogin, $CPublic);
		
		$html .= "</li>";
		
	}
	
	if ($CKoneksi->mysqlNRows($sql) > 0)
	{
		$html .= "</ul>";
	}
}

$html = "";

treeFold($html, "", "", $CKoneksi, $CFolder, $CFile, $userIdLogin, $CPublic);

echo $html;
?>
 
</body>