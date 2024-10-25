<?php
require_once("../../config.php");
$paramCariGet = $_GET['paramCari'];
?>
<script type="text/javascript">
window.onscroll = 
function ()
{
	document.getElementById('fix').style.top = (document.pageYOffset?document.pageYOffset:document.body.scrollTop);
}
</script>
<?php
$limit = 35;
$limitForm = 29;
if($adminEmpl == "Y")
{
	$limit = 29;
	$limitForm = 26;
}

if($aksiGet == "deleteForm")
{
	$idformGet = $_GET['id'];
	$nmFileBefore = $CEmpl->detilMstform($idformGet,"filedoc");
	$nmForm = $CEmpl->detilMstform($idformGet,"namedoc");
	
	$pathBefore = $path."/employee/data/document/".$nmFileBefore;
	$pathDelete = $path."/employee/data/documentDel//Del-".$nmFileBefore;
	
	//echo $idformGet." | ".$nmFileBefore." | ".$nmForm;
	copy($pathBefore, $pathDelete); //kopikan file ke folder documentDel/
	unlink($pathBefore); //delete file
	
	$CKoneksi->mysqlQuery("UPDATE emplform SET deletests=1, delusrdt='".$CPublic->userWhoAct()."' WHERE idform=".$idformGet." AND deletests=0;");
}

if($aksiPost == "urutanNaik")
{
	$idForm = $_POST['idForm'];
	$urutanSeb = $_POST['urutanSeb'];
	$urutanSek = $_POST['urutanSek'];
	
	//echo $ide." | ".$urutanSeb." | ".$urutanSek." | ";
	
	$CKoneksi->mysqlQuery("UPDATE emplform SET urutanluar=".$urutanSek." WHERE urutanluar=".$urutanSeb." AND deletests=0;");// urutan sebelum, update ke urutan sekarang
	$CKoneksi->mysqlQuery("UPDATE emplform SET urutanluar=".$urutanSeb." WHERE idform=".$idForm." AND deletests=0;"); // urutan sekarang, update ke urutan sebelum
}

if($aksiPost == "urutanTurun")
{
	$idForm = $_POST['idForm'];
	$urutanSek = $_POST['urutanSek'];
	$urutanSet = $_POST['urutanSet'];
	
	//echo $ide." | ".$urutanSek." | ".$urutanSet." | ";
	
	$CKoneksi->mysqlQuery("UPDATE emplform SET urutanluar=".$urutanSek." WHERE urutanluar=".$urutanSet." AND deletests=0;");// urutan setelah, update ke urutan sekarang
	$CKoneksi->mysqlQuery("UPDATE emplform SET urutanluar=".$urutanSet." WHERE idform=".$idForm." AND deletests=0;"); // urutan sekarang, update ke urutan setelah
}
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../../js/animatedcollapse.js"></script>
<script>
// === start == Animated Collapsible DIV
//animatedcollapse.addDiv('div1', 'fade=1,height=auto,overflow-y=scroll')
animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
	/*$: Access to jQuery
	divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
	state: "block" or "none", depending on state*/
}
animatedcollapse.init()
// === end of == Animated Collapsible DIV

function rightFrame(ide, name, halaman)
{
	document.getElementById('ideOpen').value = ide;
	document.getElementById('nameOpen').value = name;
	parent.document.getElementById('btnZoom').style.display = 'block';
	
	setTimeout(function()
	{
		parent.document.getElementById('rightFrame').src = "";
		parent.document.getElementById('rightFrame').src = "templates/halOpenPdf.php?ide="+ide;
	},250);
}
function rightFrame1(ide, halaman)
{
	if(detectBrowser() == "MSIE" && ide != "")
	{
		setTimeout(function()
		{
			parent.document.getElementById('rightFrame').src = "";
			parent.document.getElementById('rightFrame').src = "templates/halOpenPdf.php?ide="+ide;
		},450);
	}
}

function trActive(idTr, idForm, ide, name)
{
	document.getElementById('nameFormOpen').value = name;
	var maxTr = document.getElementById('maxTr').value;
	document.getElementById('idFormOpen').value = idForm;
	document.getElementById('ideOpen').value = ide;
	for(i=1; i<maxTr; i++)
	{
		document.getElementById(i).style.backgroundColor = '#FFFFFF';
		document.getElementById(i).onmousehover = function onmousehover(){ this.style.backgroundColor = '#DDF0FF'; }
		document.getElementById(i).onmouseout = function onmouseout(){ this.style.backgroundColor = '#FFFFFF'; }
	}
	document.getElementById(idTr).style.backgroundColor = '#DDF0FF';
	document.getElementById(idTr).onmouseout = function onmouseout(){ this.style.backgroundColor = '#DDF0FF';}
}

function btnChange()
{
	document.getElementById('btnDl').disabled = false;
	document.getElementById('btnDl').className = 'btnStandarKecil';
	document.getElementById('btnDl').onmouseover = function onmouseover(){ this.className='btnStandarKecilHover'; }
	document.getElementById('btnDl').onmouseout = function onmouseout(){ this.className='btnStandarKecil'; }
	
	document.getElementById('tblDl').className = 'fontBtnKecil';
	document.getElementById('tblDl').onmouseover = function onmouseover(){ this.className='fontBtnKecilHover'; }
	document.getElementById('tblDl').onmouseout = function onmouseout(){ this.className='fontBtnKecil'; }
	
	document.getElementById('btnDel').disabled = false;
	document.getElementById('btnDel').className = 'btnStandarKecil';
	document.getElementById('btnDel').onmouseover = function onmouseover(){ this.className='btnStandarKecilHover'; }
	document.getElementById('btnDel').onmouseout = function onmouseout(){ this.className='btnStandarKecil'; }
	
	document.getElementById('tblDel').className = 'fontBtnKecil';
	document.getElementById('tblDel').onmouseover = function onmouseover(){ this.className='fontBtnKecilHover'; }
	document.getElementById('tblDel').onmouseout = function onmouseout(){ this.className='fontBtnKecil'; }
	
	document.getElementById('btnEdit').disabled = false;
	document.getElementById('btnEdit').className = 'btnStandarKecil';
	document.getElementById('btnEdit').onmouseover = function onmouseover(){ this.className='btnStandarKecilHover'; }
	document.getElementById('btnEdit').onmouseout = function onmouseout(){ this.className='btnStandarKecil'; }
	
	document.getElementById('tblEdit').className = 'fontBtnKecil';
	document.getElementById('tblEdit').onmouseover = function onmouseover(){ this.className='fontBtnKecilHover'; }
	document.getElementById('tblEdit').onmouseout = function onmouseout(){ this.className='fontBtnKecil'; }
	
	if(parent.document.getElementById('jenisDoc').value == "PR")
	{
		document.getElementById('btnAdd').disabled = false;
		document.getElementById('btnAdd').className = 'btnStandarKecil';
		document.getElementById('btnAdd').onmouseover = function onmouseover(){ this.className='btnStandarKecilHover'; }
		document.getElementById('btnAdd').onmouseout = function onmouseout(){ this.className='btnStandarKecil'; }
		
		document.getElementById('tblAdd').className = 'fontBtnKecil';
		document.getElementById('tblAdd').onmouseover = function onmouseover(){ this.className='fontBtnKecilHover'; }
		document.getElementById('tblAdd').onmouseout = function onmouseout(){ this.className='fontBtnKecil'; }
	}
}

function downloadForm()
{
	var idFormOpen = document.getElementById('idFormOpen').value;
	setTimeout(function()
	{
		document.getElementById('hrefOpenFile'+idFormOpen).click(); 
		//rightFrame1(document.getElementById('ideOpen').value,'<?php echo $halamanGet;?>');
	},250);
}

function urutanNaik(idForm, urutanSek, urutanSeb)
{
	formUrutan.idForm.value = idForm;
	formUrutan.urutanSeb.value = urutanSeb;
	formUrutan.urutanSek.value = urutanSek;
	formUrutan.aksi.value = "urutanNaik";
	formUrutan.submit();
}

function urutanTurun(idForm, urutanSek, urutanSet)
{
	formUrutan.idForm.value = idForm;
	formUrutan.urutanSek.value = urutanSek;
	formUrutan.urutanSet.value = urutanSet;
	formUrutan.aksi.value = "urutanTurun";
	formUrutan.submit();
}

</script>
<body onLoad="loadScroll('docList');" onUnload="saveScroll('docList')">
<table width="99%" bgcolor="#FFFFFF" cellspacing="1">
<input type="hidden" id="adminEmpl" value="<?php echo $adminEmpl;?>" />
<input type="hidden" id="ideOpen" value=""/>
<input type="hidden" id="nameOpen" value=""/>
<input type="hidden" id="idFormOpen" value=""/>
<input type="hidden" id="nameFormOpen" value=""/>

<form name="formUrutan" action="" method="post">
<input type="hidden" name="idForm" />
<input type="hidden" name="urutanSeb" />
<input type="hidden" name="urutanSek" />
<input type="hidden" name="urutanSet" />
<input type="hidden" name="aksi" />
</form>
	<?php
		if($adminEmpl == "Y")
		{
	?>
<!-- START == Button Action MANUAL/PROCEDURE ================================================================================ -->
	<tr>
    	<td colspan="2">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
        	<tr>
            	<td height="28">
                <div id="fix" style="position:absolute;top:0px;left:0px;background-color:#666;height:28;">&nbsp;
                	<button id="btnDl" class="btnStandarKecilDis" onMouseOver="this.className='btnStandarKecilDis';" onMouseOut="this.className='btnStandarKecilDis';" style="width:90px;" onClick="downloadForm();" title="Download Form" disabled>
                        <table id="tblDl" class="fontBtnKecilDis" onMouseOver="this.className='fontBtnKecilDis'" onMouseOut="this.className='fontBtnKecilDis'" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td align="center">
                                    <img src="../../picture/Gnome-Document-Save-32.png"/ height="18px" style="vertical-align:middle;">
                                </td>
                                <td align="center">
                                     Download
                                </td>
                            </tr>
                        </table>
                    </button>&nbsp;
                    <button id="btnDel" style="width:73px;" class="btnStandarKecilDis" onMouseOver="this.className='btnStandarKecilDis';" onMouseOut="this.className='btnStandarKecilDis';" onClick="parent.deleteFormLuar(document.getElementById('idFormOpen').value,document.getElementById('nameFormOpen').value,'deleteForm','FO'); return false;" title="Delete Document" disabled>
                        <table id="tblDel" cellpadding="0" cellspacing="0" width="100%" height="100%" class="fontBtnKecilDis" onMouseOver="this.className='fontBtnKecilDis'" onMouseOut="this.className='fontBtnKecilDis'">
                          <tr>
                            <td align="center"><img src="../../picture/Button-Cross-blue-32.png" height="18" style="vertical-align:bottom;"/> </td>
                            <td align="center">Delete</td>
                          </tr>
                        </table>
                    </button>&nbsp;
                    <button id="btnEdit" style="width:60px;"class="btnStandarKecilDis" onMouseOver="this.className='btnStandarKecilDis';" onMouseOut="this.className='btnStandarKecilDis';" onClick="parent.openThickboxWindow(document.getElementById('idFormOpen').value,'editForm'); return false;" title="Delete Document" disabled>
                        <table id="tblEdit" cellpadding="0" cellspacing="0" width="100%" height="100%" class="fontBtnKecilDis" onMouseOver="this.className='fontBtnKecilDis'" onMouseOut="this.className='fontBtnKecilDis'">
                            <tr>
                                <td align="center"><img src="../../picture/Auction-blue-32.png"/ height="18px" style="vertical-align:middle;"> </td>
                                <td align="center">Edit</td>
                            </tr>
                        </table>
                    </button>&nbsp;
                    </div>
                </td>
            </tr>
        </table>
        </td>
    </tr>
<!-- END OF == Button Action MANUAL/PROCEDURE =============================================================================== -->
<?php
		}
	$i=1;
	$query = $CKoneksi->mysqlQuery("SELECT * FROM emplform WHERE deletests=0 ORDER BY urutanluar ASC");
	if($paramCariGet != "")
	{
		$query = $CKoneksi->mysqlQuery("SELECT * FROM emplform WHERE namedoc LIKE '%".$paramCariGet."%' AND deletests=0 ORDER BY urutanluar ASC");
	}
	$jmlDoc = $CKoneksi->mysqlNRows($query);
	if($jmlDoc == 0)
	{
		echo "<span class=\"errorMsg\">*There is no document available*</span>";
	}
	while($row = $CKoneksi->mysqlFetch($query))
	{
		if($adminEmpl == "Y")
		{
			$urutan = $row['urutanluar'];
			$urutanSeb = $CEmpl->urutanFormLuarSebSet("urutanluar","<",$urutan,"DESC");// urutan sebelum $urutan
			$urutanSet = $CEmpl->urutanFormLuarSebSet("urutanluar",">",$urutan,"ASC");// urutan setelah $urutan
			
			$iconNaik = "<img src=\"../../picture/urutanAtas.png\" width=\"20\" height=\"12\" onMouseOver=\"this.style.backgroundColor='#1582f5';\" onMouseOut=\"this.style.backgroundColor='transparent';\" onclick=\"urutanNaik('".$row['idform']."','".$urutan."','".$urutanSeb."'); return false();\"/>&nbsp;";
			$iconTurun = "<img src=\"../../picture/urutanBawah.png\" width=\"20\" height=\"12\" onMouseOver=\"this.style.backgroundColor='#1582f5';\" onMouseOut=\"this.style.backgroundColor='transparent';\" onclick=\"urutanTurun('".$row['idform']."','".$urutan."','".$urutanSet."'); return false();\"/>&nbsp;";
			
			$urutanMin = $CEmpl->urutanFormLuarMinMax("urutanluar","ASC");// urutan pertama
			$urutanMax = $CEmpl->urutanFormLuarMinMax("urutanluar","DESC");// urutan terakhir
			
			if($urutan == $urutanMin)
			{
				$iconNaik = "&nbsp;";
			}
			if($urutan == $urutanMax)
			{
				$iconTurun = "&nbsp;";
			}
		}
		//$jmlForm = $CEmpl->jmlMstformByIde($row['ide'], "idform");
		$btnChange = "";
		$animate = "";
		$expand = "&nbsp;&nbsp;&nbsp;&nbsp;";
		if($adminEmpl == "Y")
		{
			$btnChange = "btnChange();";
		}	
		/*if($jmlForm > 0)
		{
			$expand = "<img src=\"../../picture/listVesselPanah_serongKananBawah.png\" title=\"Form List\"/>";
			$animate = "animatedcollapse.toggle('div".$i."');";
		}*/
		$onClickTr = $animate.$btnChange."trActive('".$i."','".$row['idform']."','".$row['ide']."','".ucwords(strtolower($CPublic->konversiQuotes($row['namedoc'])))."');document.getElementById('hrefOpenFile".$row['idform']."').click();";
		if($adminEmpl == "Y")
		{
			$btnChange = "btnChange();";
			$onClickTr = $animate.$btnChange."trActive('".$i."','".$row['idform']."','".$row['ide']."','".ucwords(strtolower($CPublic->konversiQuotes($row['namedoc'])))."');";
		}
		$hrefOpenFile = "<a id=\"hrefOpenFile".$row['idform']."\" href=\"downloadFile.php?ideFile=".$row['idform']."&halaman=form\"></a>";
?>
<!-- START == TR List MANUAL/PROCEDURE ====================================================================================== -->
    <tr id="<?php echo $i ?>" onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" height="26">
        <td colspan="2" class="tdMyFolder">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
            	<td width="3%">
                	<?php echo $iconNaik;?>
                </td>
                <td rowspan="2" style="font-size:13px;color:#009;font-weight:bold;font-family:Tahoma;" width="5%" align="center" onClick="<?php echo $onClickTr;?>">
                    <?php echo $i.$hrefOpenFile;?>.&nbsp;
                </td>
                <td rowspan="2" width="99%" title="<?php echo ucwords(strtolower($row['namedoc']));?>" onClick="<?php echo $onClickTr;?>">
                    <span style="font-family:Arial Narrow;font-size:14px;color:#333;">
                    <?php echo $CPublic->potongKarakter(ucwords(strtolower($row['namedoc'])),$limit); ?>
                    </span>
                </td>
                <td rowspan="2" width="1%" onClick="<?php echo $onClickTr;?>"><?php echo $expand;?></td>
            </tr>
            <tr>
            	<td width="3%">
                	<?php echo $iconTurun;?>
                </td>
            </tr>
        </table>
        </td>
    </tr>
    <tr><td colspan="2">
    </td></tr>
<?php 
$i++; } ?>
<input type="hidden" id="maxTr" value="<?php echo $i;?>"/>
</table>
<?php
if($aksiGet == "deleteForm" || $aksiGet == "deleteDoc")
{
	$jenis = "Policy";
	if($halamanGet == "PR")
	{
		$jenis = "Procedure";
	}
	if($aksiGet == "deleteDoc")
	{
		$report = "*".$jenis." \"".$nameDoc."\" has been Delete.";
	}
	if($aksiGet == "deleteForm")
	{
		//$report = "*Form \"".$nmForm."\" has been Delete.";
		$report = "Form has been Delete.";
	}
?>
	<script language="javascript">
		parent.refreshFrameLuar('<?php echo $halamanGet;?>');
		parent.report('<?php echo $report;?>');
		<?php 
			if($aksiGet == "deleteDoc")
			{
		?>
				parent.refreshRightFrame();
		<?php 
			} 
		?>
	</script>	
<?php
}

$setAllCollapse = "";
for($i = 1; $i <= $jmlDoc; $i++)
{
	$setAllCollapse.= "animatedcollapse.addDiv('div".$i."', 'fade=1,speed=500,height=auto,overflow-y=scroll');";
}

echo "<script>".$setAllCollapse."</script>";
?>
</body>
