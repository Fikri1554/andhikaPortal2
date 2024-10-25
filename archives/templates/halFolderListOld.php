<?php
require_once("../../config.php");

$idFoldGet = $_GET['idFold'];
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
}

function btnKlikfold(idFoldSelect)
{
	var allIdFold = "";
	
	var splitNilai = idFoldSelect.split("."); //POTONG IDFOLD YANG DIKLIK MISAL IDFOLD 43.1.2
	// CARI BERAPA FOLDER YANG DILEWATIN MISAL IDFOLD 43.1.2 YG DILEWATIN, IDFOLD 43, IDFOLD 43.1, IDFOLD 43.1.2 
	for(i = 0; i < splitNilai.length; i++)
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
		if(idFoldSelect!="")
		{
			klikFold(allIdFold);
		}
	}
}

function klikRightMouse(idFold)
{
	showCollapse(idFold);return false;
}

function showCollapse(idFold)
{
	var allIdFold = document.getElementById('allIdFold').value;
	var splitAllIdFold = allIdFold.split("-"); 
	
	for(var i = 0; i <= splitAllIdFold.length; i++)
	{
		if(idFold != splitAllIdFold[i])
		{
			animatedcollapse.hide('setFold'+splitAllIdFold[i]);
			document.getElementById('statHidden'+splitAllIdFold[i]).value = "Y";
		}
	}
	
	var statHiddenSel = document.getElementById('statHidden'+idFold).value;
	
	
	if(statHiddenSel == "Y")
	{
		alert('setFold'+idFold);
		animatedcollapse.show('setFold'+idFold);
		document.getElementById('statHidden'+idFold).value = "N";
		return false;
	}
	else if(statHiddenSel == "N")
	{
		animatedcollapse.hide('setFold'+idFold);
		document.getElementById('statHidden'+idFold).value = "Y";
		return false;
	}
}

function allHidden()
{
	var allIdFold = document.getElementById('allIdFold').value;
	
	var splitAllIdFold = allIdFold.split("-"); 
	
	for(var i = 0; i <= splitAllIdFold.length; i++)
	{
		if(splitAllIdFold[i] != "")
		{
			//animatedcollapse.hide('setFold'+splitAllIdFold[i]);
			//document.getElementById('statHidden'+splitAllIdFold[i]).value = "Y";
		}
	}
}

animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
	//$: Access to jQuery
	//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
	//state: "block" or "none", depending on state
}
animatedcollapse.init();
</script>

</head>

<body onLoad="addEvents();allHidden();" style="">
<?php 
/*echo $idFoldGet; */
$aksiGet = $_GET['aksi'];

$ideGet = $_GET['ide'];
$idFoldRef = $_GET['idFoldRef'];
$nmFold = mysql_real_escape_string( $_GET['nmFold'] );
$descFold = mysql_real_escape_string( $_GET['descFold'] );
$foldSubGet = $_GET['foldSub'];
$contentTypeGet = $_GET['contentType'];

$dateTime = $CPublic->dateTimeGabung();
$tglbuat = $CPublic->indonesiaDate()." ".$CPublic->jamServer();

if($aksiGet == "simpanNewFolder")
{
	$idFoldLast = $CFolder->idFoldLast($foldSubGet, $idFoldRef);
	
	$fileFold = $userIdLogin."-".$idFoldLast."-".$dateTime;
	$addUsrdt = $CPublic->userWhoAct();

	$CKoneksi->mysqlQuery("INSERT INTO tblfolder(idfold, idfoldref, foldsub, folderowner, namefold, filefold, descfold, iddivisi, tipekonten, tglbuat, addusrdt) 
	VALUES ('".$idFoldLast."', '".$idFoldRef."',  '".$foldSubGet."', '".$userIdLogin."', '".$nmFold."', '".$fileFold."', '".$descFold."', '', '".$contentTypeGet."', '".$tglbuat."', '".$addUsrdt."');");
	$lastInsertId = mysql_insert_id();
	$CHistory->updateLog($userIdLogin, "Buat Folder baru (ide = <b>".$lastInsertId."</b>, nama folder = <b>".$nmFold."</b>)");
	
	if(is_dir($pathArchives."/data/document/LEVEL".$foldSubGet.""))
	{
		mkdir($pathArchives."/data/document/LEVEL".$foldSubGet."/".$fileFold, 0700);
	}
	else
	{
		mkdir($pathArchives."/data/document/LEVEL".$foldSubGet."", 0700);
		mkdir($pathArchives."/data/document/LEVEL".$foldSubGet."/".$fileFold, 0700);
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
	/*$folderSek = $CFolder->detilFold($ideGet, "filefold");
	$pathFolderSek = $pathArchives."data/document/LEVEL".$foldSubGet."/".$folderSek;
	//echo $pathFolderSek;
	rmdir($pathFolderSek);
	$CKoneksi->mysqlQuery("UPDATE tblfolder SET deletests=1, delusrdt='".$CPublic->userWhoAct()."' WHERE ide=".$ideGet." AND deletests=0");
	$CKoneksi->mysqlQuery("UPDATE tblauthorfold SET deletests=1, delusrdt='".$CPublic->userWhoAct()."' WHERE idefold=".$ideGet." AND folderowner='".$userIdLogin."' AND deletests=0");
	$CHistory->updateLog($userIdLogin, "Hapus Folder (ide = <b>".$ideGet."</b>)");*/
}



?>
<input type="hidden" id="idFoldSelectSeb">
<input type="button" id="btnKlikfold" onClick="btnKlikfold('<?php echo $idFoldGet; ?>');" style="display:none;">

<?php
function treeFold(&$html="", $idFoldRef="", $foldSub, $CKoneksi, $CFolder, $CFile, $userIdLogin)
{
	$query = "SELECT * FROM tblfolder WHERE idfoldref='".$idFoldRef."' AND folderowner=".$userIdLogin." AND deletests=0 ORDER BY namefold ASC";
	$sql = $CKoneksi->mysqlQuery($query);
	
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
		
		
		
		$html .= "<li style=\"list-style:none;color:#000080;\" id=\"id".$row['idfold']."\" oncontextmenu=\"klikRightMouse('".$row['idfold']."');return false;\">";
		$html .= "<img id=\"imgFold".$row['idfold']."\" src=\"../../picture/folder-horizontal.png\" height=\"20\">&nbsp;&nbsp;";
		
		
		
		$pilihBtnExpand= "parent.parent.pilihBtnExpand('".$row['ide']."', '".$row['foldsub']."', '".$row['idfold']."', '".$row['tipekonten']."');";
		
		$html.= "<div id=\"idSpan".$row['idfold']."\" onClick=\" ".$pilihBtnExpand." klikFold('".$row['idfold']."'); \" 
		onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\"  
		style=\"display:inline;\" title=\"Content : ".ucfirst($row['tipekonten'])." (".$jmlIsi.")\">".$row['namefold']."</div>";
		
		$html .="<input type=\"hidden\" id=\"bukaFold".$row['idfold']."\" value=\"N\">";
		
		$popUpSetting = popUpSetting($row['idfold']);
		
		$html .= $popUpSetting;
		$html .= "<input type=\"hidden\" id=\"statHidden".$row['idfold']."\" value=\"Y\">";
		
		treeFold($html, $row['idfold'], $row['foldsub'], $CKoneksi, $CFolder, $CFile, $userIdLogin);
		
		//$html .=  $popUpSetting;
		
		$html .= "</li>";
		
	}
	
	if ($CKoneksi->mysqlNRows($sql) > 0)
	{
		$html .= "</ul>";
	}
}

$html = "";
$popUpSetting = "";
treeFold($html, "", "", $CKoneksi, $CFolder, $CFile, $userIdLogin);
echo $html;
?>
 
<input type="hidden" id="allIdFold" value="<?php echo allIdFold($CKoneksi, $userIdLogin); ?>">
</body>

<?php

$setAllCollapse = "";
$query = $CKoneksi->mysqlQuery("SELECT * FROM tblfolder WHERE folderowner=".$userIdLogin." AND deletests=0 ORDER BY namefold ASC");
while ($row = $CKoneksi->mysqlFetch($query)) 
{
	$setAllCollapse.= "animatedcollapse.addDiv('setFold".$row['idfold']."', 'fade=1,speed=500,height=auto,overflow-y=scroll');";
}

echo "<script>".$setAllCollapse."</script>";

function allIdFold($CKoneksi, $userId)
{
	$html = "";
	$query = $CKoneksi->mysqlQuery("SELECT idfold FROM tblfolder WHERE folderowner=".$userId." AND deletests=0 ORDER BY namefold ASC");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$html .= $row['idfold']."-";
	}
		
	return $html;
}

function popUpSetting($idFold)
{
	return "<div class=\"elementDefault\" id=\"setFold".$idFold."\" style=\"display:none;width:auto;position:absolute;\">
                <table width=\"100\" cellpadding=\"2\" cellspacing=\"0\">
                <tr>
                    <td>
                    	<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['ide']."','detailFolder');\" style=\"width:95px;height:30px;\" title=\"Detail of this Folder\">
                            <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
                              <tr>
                                <td align=\"left\"><img src=\"../../picture/Information-blue-32.png\" height=\"15\"/>&nbsp;&nbsp;DETAIL | ".$idFold."</td>
                              </tr>
                            </table>
                        </button>
                     </td>
                </tr>
                <tr>
                    <td>
                    	<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"parent.pilihBtnExpand('".$row['ide']."', '".$row['foldsub']."', '".$row['idfold']."', '".$row['tipekonten']."');\" style=\"width:95px;height:30px;\" title=\"Go to this Folder Content\">
                            <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
                              <tr>
                                <td align=\"left\"><img src=\"../../picture/Outbox-blue-32.png\" height=\"15\"/>&nbsp;&nbsp;EXPAND</td>
                              </tr>
                            </table>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>
                    	<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['ide']."','editFolder');\" style=\"width:95px;height:30px;\" title=\"Edit this Folder\">
                            <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
                              <tr>
                                <td align=\"left\"><img src=\"../../picture/Auction-blue-32.png\" height=\"15\"/>&nbsp;&nbsp;EDIT</td>
                              </tr>
                            </table>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>
                    	<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"".$deleteFolder."\" style=\"width:95px;height:30px;\" title=\"Delete this Folder\">
                            <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
                              <tr>
                                <td align=\"left\"><img src=\"../../picture/Button-Cross-blue-32.png\" height=\"15\"/>&nbsp;&nbsp;DELETE</td> 
                              </tr>
                            </table>
                        </button>
                    </td>
                </tr>
                     
                </table>
            </div>";
}
?>
