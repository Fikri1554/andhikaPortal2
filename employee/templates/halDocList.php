<?php
require_once("../../config.php");
$paramCariGet = $_GET['paramCari'];
?>
<script type="text/javascript">
window.onscroll =
    function() {
        document.getElementById('fix').style.top = (document.pageYOffset ? document.pageYOffset : document.body
            .scrollTop);
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

$jenisDoc = "Policy";
if($halamanGet == "PR")
{
	$jenisDoc = "Procedure";
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

if($aksiGet == "deleteDoc")
{
	$ideGet = $_GET['id'];
	$nameDoc = $CEmpl->detilMstdoc($ideGet,"namedoc");
	$nmFileBefore = $CEmpl->detilMstdoc($ideGet,"filedoc");
	
	//echo $ideGet." | ".$nameDoc." | ".$nmFileBefore." | ".$halamanGet;
	
	$pathBefore = $path."/employee/data/document/".$nmFileBefore;
	$pathDelete = $path."/employee/data/documentDel//Del-".$nmFileBefore;
	
	$CKoneksi->mysqlQuery("UPDATE empldoc SET deletests=1, delusrdt='".$CPublic->userWhoAct()."' WHERE ide=".$ideGet." AND deletests=0;");
	copy($pathBefore, $pathDelete); //kopikan file ke folder documentDel/
	unlink($pathBefore); //delete file
	
	$CHistory->updateLogEmpl($userIdLogin, "Hapus Dokumen ".$jenisDoc." (ide = <b>".$ideGet."</b>, nama file = <b>".$nameDoc	."</b>)");
	
	$query = $CKoneksi->mysqlQuery("SELECT * FROM emplform WHERE ide=".$ideGet." AND deletests=0");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$idForm = $row ['idform'];
		$nmForm = $CEmpl->detilMstform($idForm,"filedoc");
		
		$pathFromBefore = $path."/employee/data/document/".$nmForm;
		$pathFormDelete = $path."/employee/data/documentDel//Del-".$nmForm;
		
		copy($pathFromBefore, $pathFormDelete); //kopikan file ke folder documentDel/
		unlink($pathFromBefore); //delete file
	
		$CKoneksi->mysqlQuery("UPDATE emplform SET deletests=1, delusrdt='".$CPublic->userWhoAct()."' WHERE idform=".$idForm." AND deletests=0;");
		$CHistory->updateLogEmpl($userIdLogin, "Hapus Form (ide = <b>".$idForm."</b>, nama file = <b>".$nmForm	."</b>)");
	}
}

if($aksiPost == "urutanNaik")
{
	$ide = $_POST['ide'];
	$urutanSeb = $_POST['urutanSeb'];
	$urutanSek = $_POST['urutanSek'];
	
	//echo $ide." | ".$urutanSeb." | ".$urutanSek." | ";
	
	$CKoneksi->mysqlQuery("UPDATE empldoc SET urutan=".$urutanSek." WHERE urutan=".$urutanSeb." AND jenis='".$halamanGet."' AND deletests=0;");// urutan sebelum, update ke urutan sekarang
	$CKoneksi->mysqlQuery("UPDATE empldoc SET urutan=".$urutanSeb." WHERE ide=".$ide." AND jenis='".$halamanGet."' AND deletests=0;"); // urutan sekarang, update ke urutan sebelum
}

if($aksiPost == "urutanTurun")
{
	$ide = $_POST['ide'];
	$urutanSek = $_POST['urutanSek'];
	$urutanSet = $_POST['urutanSet'];
	
	//echo $ide." | ".$urutanSek." | ".$urutanSet." | ";
	
	$CKoneksi->mysqlQuery("UPDATE empldoc SET urutan=".$urutanSek." WHERE urutan=".$urutanSet." AND jenis='".$halamanGet."' AND deletests=0;");// urutan setelah, update ke urutan sekarang
	$CKoneksi->mysqlQuery("UPDATE empldoc SET urutan=".$urutanSet." WHERE ide=".$ide." AND jenis='".$halamanGet."' AND deletests=0;"); // urutan sekarang, update ke urutan setelah
}

if($aksiPost == "urutanFormNaik")
{
	$idForm = $_POST['idForm'];
	$ide = $_POST['ide'];
	$urutanSeb = $_POST['urutanSeb'];
	$urutanSek = $_POST['urutanSek'];
	$openDiv = $_POST['openDiv'];
	
	//echo $idForm." | ".$urutanSeb." | ".$urutanSek." | ";
	
	$CKoneksi->mysqlQuery("UPDATE emplform SET urutan=".$urutanSek." WHERE urutan=".$urutanSeb." AND ide=".$ide." AND deletests=0;");// urutan sebelum, update ke urutan sekarang
	$CKoneksi->mysqlQuery("UPDATE emplform SET urutan=".$urutanSeb." WHERE idform=".$idForm." AND deletests=0;"); // urutan sekarang, update ke urutan sebelum
}

if($aksiPost == "urutanFormTurun")
{
	$idForm = $_POST['idForm'];
	$ide = $_POST['ide'];
	$urutanSek = $_POST['urutanSek'];
	$urutanSet = $_POST['urutanSet'];
	$openDiv = $_POST['openDiv'];
	
	//echo $idForm." | ".$urutanSek." | ".$urutanSet." | ";
	
	$CKoneksi->mysqlQuery("UPDATE emplform SET urutan=".$urutanSek." WHERE urutan=".$urutanSet." AND ide=".$ide." AND deletests=0;");// urutan setelah, update ke urutan sekarang
	$CKoneksi->mysqlQuery("UPDATE emplform SET urutan=".$urutanSet." WHERE idform=".$idForm." AND deletests=0;"); // urutan sekarang, update ke urutan setelah
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
animatedcollapse.ontoggle = function($, divobj, state) { //fires each time a DIV is expanded/contracted
    /*$: Access to jQuery
    divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
    state: "block" or "none", depending on state*/
}
animatedcollapse.init()
// === end of == Animated Collapsible DIV

function rightFrame(ide, name, halaman) {
    document.getElementById('ideOpen').value = ide;
    document.getElementById('nameOpen').value = name;
    parent.document.getElementById('btnZoom').style.display = 'block';

    setTimeout(function() {
        parent.document.getElementById('rightFrame').src = "";
        parent.document.getElementById('rightFrame').src = "templates/halOpenPdf.php?ide=" + ide;
    }, 250);
}

function rightFrame1(ide, halaman) {
    if (detectBrowser() == "MSIE" && ide != "") {
        setTimeout(function() {
            parent.document.getElementById('rightFrame').src = "";
            parent.document.getElementById('rightFrame').src = "templates/halOpenPdf.php?ide=" + ide;
        }, 450);
    }
}

function trActive(idTr) {
    var maxTr = document.getElementById('maxTr').value;
    for (i = 1; i < maxTr; i++) {
        document.getElementById(i).style.backgroundColor = '#FFFFFF';
        document.getElementById(i).onmousehover = function onmousehover() {
            this.style.backgroundColor = '#DDF0FF';
        }
        document.getElementById(i).onmouseout = function onmouseout() {
            this.style.backgroundColor = '#FFFFFF';
        }
    }
    document.getElementById(idTr).style.backgroundColor = '#DDF0FF';
    document.getElementById(idTr).onmouseout = function onmouseout() {
        this.style.backgroundColor = '#DDF0FF';
    }
}

function trFormActive(idTrForm, idMaxTr, iVariabel) {
    var maxTrForm = document.getElementById(idMaxTr).value;
    var maxTr = document.getElementById('maxTr').value;
    var idForm = document.getElementById('idFormOpen').value;
    var adminEmpl = document.getElementById('adminEmpl').value;

    /*for(i=1; i<maxTrForm; i++)
    {
    	document.getElementById('trForm'+iVariabel+i).style.backgroundColor = '#F4F4F4' ;
    	document.getElementById('trForm'+iVariabel+i).onmouseout = function onmouseout(){ this.style.backgroundColor='#F4F4F4'; }
    }*/
    for (i = 1; i < maxTr; i++) {
        var maxTrForm = document.getElementById('maxTrForm' + i).value;
        //alert(maxTrForm);
        if (maxTrForm > 1) {
            if (adminEmpl == "Y") {
                btnFormChange(i, '', idForm, 'dis');
            }
            for (j = 1; j < maxTrForm; j++) {
                document.getElementById('trForm' + i + j).style.backgroundColor = '#F4F4F4';
                document.getElementById('trForm' + i + j).onmouseout = function onmouseout() {
                    this.style.backgroundColor = '#F4F4F4';
                }
            }
        }
    }
    document.getElementById(idTrForm).style.backgroundColor = '#B3FFEA';
    document.getElementById(idTrForm).onmouseout = function onmouseout() {
        this.style.backgroundColor = '#B3FFEA';
    }
}

function btnChange() {
    document.getElementById('btnDel').disabled = false;
    document.getElementById('btnDel').className = 'btnStandarKecil';
    document.getElementById('btnDel').onmouseover = function onmouseover() {
        this.className = 'btnStandarKecilHover';
    }
    document.getElementById('btnDel').onmouseout = function onmouseout() {
        this.className = 'btnStandarKecil';
    }

    document.getElementById('tblDel').className = 'fontBtnKecil';
    document.getElementById('tblDel').onmouseover = function onmouseover() {
        this.className = 'fontBtnKecilHover';
    }
    document.getElementById('tblDel').onmouseout = function onmouseout() {
        this.className = 'fontBtnKecil';
    }

    document.getElementById('btnEdit').disabled = false;
    document.getElementById('btnEdit').className = 'btnStandarKecil';
    document.getElementById('btnEdit').onmouseover = function onmouseover() {
        this.className = 'btnStandarKecilHover';
    }
    document.getElementById('btnEdit').onmouseout = function onmouseout() {
        this.className = 'btnStandarKecil';
    }

    document.getElementById('tblEdit').className = 'fontBtnKecil';
    document.getElementById('tblEdit').onmouseover = function onmouseover() {
        this.className = 'fontBtnKecilHover';
    }
    document.getElementById('tblEdit').onmouseout = function onmouseout() {
        this.className = 'fontBtnKecil';
    }

    if (parent.document.getElementById('jenisDoc').value == "PR") {
        document.getElementById('btnAdd').disabled = false;
        document.getElementById('btnAdd').className = 'btnStandarKecil';
        document.getElementById('btnAdd').onmouseover = function onmouseover() {
            this.className = 'btnStandarKecilHover';
        }
        document.getElementById('btnAdd').onmouseout = function onmouseout() {
            this.className = 'btnStandarKecil';
        }

        document.getElementById('tblAdd').className = 'fontBtnKecil';
        document.getElementById('tblAdd').onmouseover = function onmouseover() {
            this.className = 'fontBtnKecilHover';
        }
        document.getElementById('tblAdd').onmouseout = function onmouseout() {
            this.className = 'fontBtnKecil';
        }
    }
}

function btnFormChange(i, name, idForm, enDis) {
    document.getElementById('idFormOpen').value = idForm;
    document.getElementById('nameFormOpen').value = name;

    var btnForm = "btnStandarKecil";
    var btnFormHover = "btnStandarKecilHover";
    var fontBtn = "fontBtnKecil";
    var fontBtnHover = "fontBtnKecilHover";
    var trueFalse = false;

    if (enDis == "dis") {
        var btnForm = "btnStandarKecilDis";
        var btnFormHover = "btnStandarKecilDis";
        var fontBtn = "fontBtnKecilDis";
        var fontBtnHover = "fontBtnKecilDis";
        var trueFalse = true;
    }

    setTimeout(function() {
        document.getElementById('btnDlForm' + i).disabled = trueFalse;
        document.getElementById('btnDlForm' + i).className = btnForm;
        document.getElementById('btnDlForm' + i).onmouseover = function onmouseover() {
            this.className = btnFormHover;
        }
        document.getElementById('btnDlForm' + i).onmouseout = function onmouseout() {
            this.className = btnForm;
        }

        document.getElementById('tblDlForm' + i).className = fontBtn;
        document.getElementById('tblDlForm' + i).onmouseover = function onmouseover() {
            this.className = fontBtnHover;
        }
        document.getElementById('tblDlForm' + i).onmouseout = function onmouseout() {
            this.className = fontBtn;
        }

        document.getElementById('btnDelForm' + i).disabled = trueFalse;
        document.getElementById('btnDelForm' + i).className = btnForm;
        document.getElementById('btnDelForm' + i).onmouseover = function onmouseover() {
            this.className = btnFormHover;
        }
        document.getElementById('btnDelForm' + i).onmouseout = function onmouseout() {
            this.className = btnForm;
        }

        document.getElementById('tblDelForm' + i).className = fontBtn;
        document.getElementById('tblDelForm' + i).onmouseover = function onmouseover() {
            this.className = fontBtnHover;
        }
        document.getElementById('tblDelForm' + i).onmouseout = function onmouseout() {
            this.className = fontBtn;
        }

        document.getElementById('btnEditForm' + i).disabled = trueFalse;
        document.getElementById('btnEditForm' + i).className = btnForm;
        document.getElementById('btnEditForm' + i).onmouseover = function onmouseover() {
            this.className = btnFormHover;
        }
        document.getElementById('btnEditForm' + i).onmouseout = function onmouseout() {
            this.className = btnForm;
        }

        document.getElementById('tblEditForm' + i).className = fontBtn;
        document.getElementById('tblEditForm' + i).onmouseover = function onmouseover() {
            this.className = fontBtnHover;
        }
        document.getElementById('tblEditForm' + i).onmouseout = function onmouseout() {
            this.className = fontBtn;
        }
    }, 250);
}

function downloadForm() {
    var idFormOpen = document.getElementById('idFormOpen').value;
    setTimeout(function() {
        document.getElementById('hrefOpenFile' + idFormOpen).click();
        //rightFrame1(document.getElementById('ideOpen').value,'<?php echo $halamanGet;?>');
    }, 250);
}

function urutanNaik(ide, urutanSek, urutanSeb) {
    formUrutan.ide.value = ide;
    formUrutan.urutanSeb.value = urutanSeb;
    formUrutan.urutanSek.value = urutanSek;
    formUrutan.aksi.value = "urutanNaik";
    formUrutan.submit();
}

function urutanTurun(ide, urutanSek, urutanSet) {
    formUrutan.ide.value = ide;
    formUrutan.urutanSek.value = urutanSek;
    formUrutan.urutanSet.value = urutanSet;
    formUrutan.aksi.value = "urutanTurun";
    formUrutan.submit();
}

function urutanFormNaik(idForm, ide, urutanSek, urutanSeb, openDiv) {
    formUrutan.idForm.value = idForm;
    formUrutan.ide.value = ide;
    formUrutan.urutanSeb.value = urutanSeb;
    formUrutan.urutanSek.value = urutanSek;
    formUrutan.openDiv.value = openDiv;
    formUrutan.aksi.value = "urutanFormNaik";
    formUrutan.submit();
}

function urutanFormTurun(idForm, ide, urutanSek, urutanSet, openDiv) {
    formUrutan.idForm.value = idForm;
    formUrutan.ide.value = ide;
    formUrutan.urutanSek.value = urutanSek;
    formUrutan.urutanSet.value = urutanSet;
    formUrutan.openDiv.value = openDiv;
    formUrutan.aksi.value = "urutanFormTurun";
    formUrutan.submit();
}

function bukaDiv() {
    var openDiv = formUrutan.openDiv.value;
    if (openDiv != "") {
        document.getElementById(openDiv).style.display = "block";
    }
}
</script>

<body onLoad="bukaDiv();loadScroll('docList');" onUnload="saveScroll('docList')">

    <table width="99%" bgcolor="#FFFFFF" cellspacing="1">

        <input type="hidden" id="adminEmpl" value="<?php echo $adminEmpl;?>" />
        <input type="hidden" id="ideOpen" value="" />
        <input type="hidden" id="nameOpen" value="" />
        <input type="hidden" id="idFormOpen" value="" />
        <input type="hidden" id="nameFormOpen" value="" />
        <form name="formUrutan" action="?halaman=<?php echo $halamanGet;?>" method="post">
            <input type="hidden" name="idForm" />
            <input type="hidden" name="ide" />
            <input type="hidden" name="urutanSeb" />
            <input type="hidden" name="urutanSek" />
            <input type="hidden" name="urutanSet" />
            <input type="hidden" name="aksi" />
            <input type="hidden" name="openDiv" value="<?php echo $openDiv;?>" />
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
                            <div id="fix" style="position:absolute;top:0px;left:0px;background-color:#666;height:28;">
                                &nbsp;
                                <button id="btnDel" style="width:73px;" class="btnStandarKecilDis"
                                    onMouseOver="this.className='btnStandarKecilDis';"
                                    onMouseOut="this.className='btnStandarKecilDis';"
                                    onClick="parent.deleteFile(document.getElementById('ideOpen').value,document.getElementById('nameOpen').value,'deleteDoc','<?php echo $halamanGet;?>'); return false;"
                                    title="Delete Document" disabled>
                                    <table id="tblDel" cellpadding="0" cellspacing="0" width="100%" height="100%"
                                        class="fontBtnKecilDis" onMouseOver="this.className='fontBtnKecilDis'"
                                        onMouseOut="this.className='fontBtnKecilDis'">
                                        <tr>
                                            <td align="center"><img src="../../picture/Button-Cross-blue-32.png"
                                                    height="18" style="vertical-align:bottom;" /> </td>
                                            <td align="center">Delete</td>
                                        </tr>
                                    </table>
                                </button>&nbsp;
                                <button id="btnEdit" style="width:60px;" class="btnStandarKecilDis"
                                    onMouseOver="this.className='btnStandarKecilDis';"
                                    onMouseOut="this.className='btnStandarKecilDis';"
                                    onClick="parent.openThickboxWindow(document.getElementById('ideOpen').value,'edit'); return false;"
                                    title="Delete Document" disabled>
                                    <table id="tblEdit" cellpadding="0" cellspacing="0" width="100%" height="100%"
                                        class="fontBtnKecilDis" onMouseOver="this.className='fontBtnKecilDis'"
                                        onMouseOut="this.className='fontBtnKecilDis'">
                                        <tr>
                                            <td align="center"><img src="../../picture/Auction-blue-32.png" /
                                                    height="18px" style="vertical-align:middle;"> </td>
                                            <td align="center">Edit</td>
                                        </tr>
                                    </table>
                                </button>&nbsp;
                                <?php 
						if($halamanGet == "PR")
						{
					?>
                                <button id="btnAdd" style="width:92px;" class="btnStandarKecilDis"
                                    onMouseOver="this.className='btnStandarKecilDis';"
                                    onMouseOut="this.className='btnStandarKecilDis';"
                                    onClick="parent.openThickboxWindow(document.getElementById('ideOpen').value,'newForm'); return false;"
                                    title="Delete Document" disabled>
                                    <table id="tblAdd" cellpadding="0" cellspacing="0" width="100%" height="100%"
                                        class="fontBtnKecilDis" onMouseOver="this.className='fontBtnKecilDis'"
                                        onMouseOut="this.className='fontBtnKecilDis'">
                                        <tr>
                                            <td align="center"><img src="../../picture/Button-Add-blue-32.png" /
                                                    height="18px" style="vertical-align:middle;"></td>
                                            <td align="center">Add Form</td>
                                        </tr>
                                    </table>
                                </button>&nbsp;
                                <?php } ?>
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
	$query = $CKoneksi->mysqlQuery("SELECT * FROM empldoc WHERE jenis='".$halamanGet."' AND deletests=0 ORDER BY urutan ASC");
	if($paramCariGet != "")
	{
		$query = $CKoneksi->mysqlQuery("SELECT * FROM empldoc WHERE namedoc LIKE '%".$paramCariGet."%' AND jenis='".$halamanGet."' AND deletests=0 ORDER BY urutan ASC");
		/*$jmlSearch = $CKoneksi->mysqlNRows($query);
		if($jmlSearch == 0 && $halamanGet == "PR")
		{
			$sql1 = $CKoneksi->mysqlQuery("SELECT * FROM emplform WHERE namedoc LIKE '%".$paramCariGet."%'AND deletests=0 ORDER BY urutan ASC");
			while($r = $CKoneksi->mysqlFetch($sql1))
			{
				$docCari = $r['namedoc'];
				echo $docCari." | ";
				$ide = $CEmpl->detilMstformByName($docCari,"ide");
				echo $ide."<br/>";
				$query = $CKoneksi->mysqlQuery("SELECT * FROM empldoc WHERE ide = ".$ide." AND deletests=0 ORDER BY urutan ASC");
			}
		}*/
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
			$urutan = $row['urutan'];
			$urutanSeb = $CEmpl->urutanSebSet("urutan",$halamanGet,"<",$urutan,"DESC");// urutan sebelum $urutan
			$urutanSet = $CEmpl->urutanSebSet("urutan",$halamanGet,">",$urutan,"ASC");// urutan setelah $urutan
			
			$iconNaik = "<img src=\"../../picture/urutanAtas.png\" width=\"20\" height=\"12\" onMouseOver=\"this.style.backgroundColor='#1582f5';\" onMouseOut=\"this.style.backgroundColor='transparent';\" onclick=\"urutanNaik('".$row['ide']."','".$urutan."','".$urutanSeb."'); return false();\"/>&nbsp;";
			$iconTurun = "<img src=\"../../picture/urutanBawah.png\" width=\"20\" height=\"12\" onMouseOver=\"this.style.backgroundColor='#1582f5';\" onMouseOut=\"this.style.backgroundColor='transparent';\" onclick=\"urutanTurun('".$row['ide']."','".$urutan."','".$urutanSet."'); return false();\"/>&nbsp;";
			
			$urutanMin = $CEmpl->urutanMinMax("urutan",$halamanGet,"ASC");// urutan pertama
			$urutanMax = $CEmpl->urutanMinMax("urutan",$halamanGet,"DESC");// urutan terakhir
			
			if($urutan == $urutanMin)
			{
				$iconNaik = "&nbsp;";
			}
			if($urutan == $urutanMax)
			{
				$iconTurun = "&nbsp;";
			}
		}
		$jmlForm = $CEmpl->jmlMstformByIde($row['ide'], "idform");
		$btnChange = "";
		$animate = "";
		$expand = "&nbsp;&nbsp;&nbsp;&nbsp;";
		if($adminEmpl == "Y")
		{
			$btnChange = "btnChange();";
		}	
		if($jmlForm > 0)
		{
			$expand = "<img src=\"../../picture/listVesselPanah_serongKananBawah.png\" title=\"Form List\"/>";
			$animate = "animatedcollapse.toggle('div".$i."');";
		}
		$onClickTr = $animate.$btnChange."trActive('".$i."'); rightFrame('".$row['ide']."','".ucwords(strtolower($CPublic->konversiQuotes($row['namedoc'])))."','".$halamanGet."');";
?>
        <!-- START == TR List MANUAL/PROCEDURE ====================================================================================== -->
        <tr id="<?php echo $i ?>" onMouseOver="this.style.backgroundColor='#DDF0FF';"
            onMouseOut="this.style.backgroundColor='#FFFFFF';" height="26">
            <td colspan="2" class="tdMyFolder">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td width="3%">
                            <?php echo $iconNaik;?>
                        </td>
                        <td rowspan="2" style="font-size:13px;color:#009;font-weight:bold;font-family:Tahoma;"
                            width="5%" align="center" onClick="<?php echo $onClickTr;?>">
                            <?php echo $i;?>.&nbsp;
                        </td>
                        <td rowspan="2" width="99%" title="<?php echo ucwords(strtolower($row['namedoc']));?>"
                            onClick="<?php echo $onClickTr;?>">
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
        <!-- END OF == TR List Manual/Procedure ===================================================================================== -->

        <!-- START ==  List FORM ==================================================================================================== -->

        <tr>
            <td colspan="2">
                <?php
		if($halamanGet == "PR")
		{
	?>
                <div id="<?php echo "div".$i;?>" style="display:none;width:auto;">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <?php
		if($adminEmpl == "Y" && $jmlForm > 0)
		{
	?>
                        <!-- START == Button Action FORM (hanya untuk procedure) =================================================================== -->
                        <tr>
                            <td width="6%">&nbsp;</td>
                            <td style="border-bottom:solid 1px #666;">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td>
                                            <button id="btnDlForm<?php echo $i;?>" class="btnStandarKecilDis"
                                                onMouseOver="this.className='btnStandarKecilDis';"
                                                onMouseOut="this.className='btnStandarKecilDis';" style="width:90px;"
                                                onClick="downloadForm();" title="Download Form" disabled>
                                                <table id="tblDlForm<?php echo $i;?>" class="fontBtnKecilDis"
                                                    onMouseOver="this.className='fontBtnKecilDis'"
                                                    onMouseOut="this.className='fontBtnKecilDis'" cellpadding="0"
                                                    cellspacing="0" border="0" width="100%">
                                                    <tr>
                                                        <td align="center">
                                                            <img src="../../picture/Gnome-Document-Save-32.png" /
                                                                height="18px" style="vertical-align:middle;">
                                                        </td>
                                                        <td align="center">
                                                            Download
                                                        </td>
                                                    </tr>
                                                </table>
                                            </button>&nbsp;
                                            <button id="btnDelForm<?php echo $i;?>" class="btnStandarKecilDis"
                                                onMouseOver="this.className='btnStandarKecilDis';"
                                                onMouseOut="this.className='btnStandarKecilDis';" style="width:70px;"
                                                onClick="parent.deleteFile(document.getElementById('idFormOpen').value,document.getElementById('nameFormOpen').value,'deleteForm','<?php echo $halamanGet;?>'); return false;"
                                                title="Delete Form" disabled>
                                                <table id="tblDelForm<?php echo $i;?>" class="fontBtnKecilDis"
                                                    onMouseOver="this.className='fontBtnKecilDis'"
                                                    onMouseOut="this.className='fontBtnKecilDis'" cellpadding="0"
                                                    cellspacing="0" border="0" width="100%">
                                                    <tr>
                                                        <td align="center">
                                                            <img src="../../picture/Button-Cross-blue-32.png"
                                                                height="18" style="vertical-align:bottom;" />
                                                        </td>
                                                        <td align="center">
                                                            Delete
                                                        </td>
                                                    </tr>
                                                </table>
                                            </button>&nbsp;
                                            <button id="btnEditForm<?php echo $i;?>" class="btnStandarKecilDis"
                                                onMouseOver="this.className='btnStandarKecilDis';"
                                                onMouseOut="this.className='btnStandarKecilDis';" style="width:55px;"
                                                onClick="parent.openThickboxWindow(document.getElementById('idFormOpen').value, 'editForm'); return false;"
                                                title="Delete Form" disabled>
                                                <table id="tblEditForm<?php echo $i;?>" class="fontBtnKecilDis"
                                                    onMouseOver="this.className='fontBtnKecilDis'"
                                                    onMouseOut="this.className='fontBtnKecilDis'" cellpadding="0"
                                                    cellspacing="0" border="0" width="100%">
                                                    <tr>
                                                        <td align="center">
                                                            <img src="../../picture/Auction-blue-32.png" / height="18px"
                                                                style="vertical-align:middle;">
                                                        </td>
                                                        <td align="center">
                                                            Edit
                                                        </td>
                                                    </tr>
                                                </table>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!-- END OF == Button Action FORM (hanya untuk procedure) ================================================================== -->
                        <?php
		}
		}
		$j=1;
		$queryForm = $CKoneksi->mysqlQuery("SELECT * FROM emplform WHERE ide=".$row['ide']." AND deletests=0 ORDER BY urutan ASC");
		/*if($paramCariGet != "")
		{
			$queryForm = $CKoneksi->mysqlQuery("SELECT * FROM emplform WHERE namedoc LIKE '%".$paramCariGet."%' AND ide=".$row['ide']." AND deletests=0 ORDER BY urutan ASC");
		}*/
		while($rowForm = $CKoneksi->mysqlFetch($queryForm))
		{
			if($adminEmpl == "Y")
			{
				$urutanForm = $rowForm['urutan'];
				$urutanFormSeb = $CEmpl->urutanFormSebSet($rowForm['ide'],"urutan","<",$urutanForm,"DESC");// urutan sebelum $urutan
				$urutanFormSet = $CEmpl->urutanFormSebSet($rowForm['ide'],"urutan",">",$urutanForm,"ASC");// urutan setelah $urutan
				
				$iconFormNaik = "<img src=\"../../picture/urutanAtas.png\" width=\"20\" height=\"12\" onMouseOver=\"this.style.backgroundColor='#1582f5';\" onMouseOut=\"this.style.backgroundColor='transparent';\" onclick=\"urutanFormNaik('".$rowForm['idform']."','".$rowForm['ide']."','".$urutanForm."','".$urutanFormSeb."','div".$i."');\"/>&nbsp;";
				$iconFormTurun = "<img src=\"../../picture/urutanBawah.png\" width=\"20\" height=\"12\" onMouseOver=\"this.style.backgroundColor='#1582f5';\" onMouseOut=\"this.style.backgroundColor='transparent';\" onclick=\"urutanFormTurun('".$rowForm['idform']."','".$rowForm['ide']."','".$urutanForm."','".$urutanFormSet."','div".$i."');\"/>&nbsp;";
				
				$urutanFormMin = $CEmpl->urutanFormMinMax($rowForm['ide'],"urutan","ASC");// urutan pertama
				$urutanFormMax = $CEmpl->urutanFormMinMax($rowForm['ide'],"urutan","DESC");// urutan terakhir
				
				//echo $urutanFormSeb." | ".$urutanForm." | ".$urutanFormSet." | ".$urutanFormMin." | ".$urutanFormMax;
				
				if($urutanForm == $urutanFormMin)
				{
					$iconFormNaik = "&nbsp;";
				}
				if($urutanForm == $urutanFormMax)
				{
					$iconFormTurun = "&nbsp;";
				}
			}
			
			$clickTrForm = "trFormActive('trForm".$i.$j."','maxTrForm".$i."','".$i."');document.getElementById('hrefOpenFile".$rowForm['idform']."').click(); rightFrame1(document.getElementById('ideOpen').value,'".$halamanGet."');";
			if($adminEmpl == "Y")
			{
				$clickTrForm = "trFormActive('trForm".$i.$j."','maxTrForm".$i."','".$i."');btnFormChange('".$i."','".ucwords(strtolower($CPublic->konversiQuotes($rowForm['namedoc'])))."','".$rowForm['idform']."','');";
			}
			$hrefOpenFile = "<a id=\"hrefOpenFile".$rowForm['idform']."\" href=\"downloadFile.php?ideFile=".$rowForm['idform']."&halaman=form\"></a>";
	?>
                        <!-- START == TR FORM List (hanya untuk procedure) ========================================================================= -->
                        <tr height="25">
                            <td width="6%">&nbsp;</td>
                            <td class="tdMyFolder" id="trForm<?php echo $i.$j;?>"
                                onMouseOver="this.style.backgroundColor='#B3FFEA'"
                                onMouseOut="this.style.backgroundColor='#F4F4F4'" style="background-color:#F4F4F4;">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td width="3%"><?php echo $iconFormNaik;?></td>
                                        <td width="13%" rowspan="2"
                                            style="font-size:12px;color:#006;font-weight:bold;font-family:Tahoma;"
                                            onClick="<?php echo $clickTrForm;?>">
                                            (<?php echo $j.$hrefOpenFile;?>)
                                        </td>
                                        <td width="84%" rowspan="2"
                                            title="<?php echo ucwords(strtolower($rowForm['namedoc']));?>"
                                            onClick="<?php echo $clickTrForm;?>">
                                            <span style="font-family:Arial Narrow;font-size:14px;qcolor:#333;">
                                                <?php echo $CPublic->potongKarakter(ucwords(strtolower($rowForm['namedoc'])), $limitForm);?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="3%"><?php echo $iconFormTurun;?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!-- END OF TR FORM List (hanya untuk procedure) =========================================================================== -->
                        <?php
		$j++;}
		echo "<input type=\"hidden\" id=\"maxTrForm".$i."\" value=\"".$j."\"/>";

		if($halamanGet == "PR")
		{
	?>
                    </table>
                </div>
                <?php }?>
            </td>
        </tr>

        <!-- END OF List FORM ======================================================================================================= -->
        <?php 
$i++; } ?>
        <input type="hidden" id="maxTr" value="<?php echo $i;?>" />
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
		//$report = "*".$jenis." \"".$nameDoc."\" has been Delete.";
		$report = "Document has been Delete.";
	}
	if($aksiGet == "deleteForm")
	{
		//$report = "*Form \"".$nmForm."\" has been Delete.";
		$report = "Form has been Delete.";
	}
?>
    <script language="javascript">
    parent.refreshFrame('<?php echo $halamanGet;?>');
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