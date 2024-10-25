<!DOCTYPE HTML>
<?php
require_once('../../config.php');
require_once('../configAtk.php');
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/main.js"></script>
<script>
function submitFile()
{
	var nmUnit = formQty.nmUnit.value;
	var aktif = document.getElementsByName('aktif');
	var aktifValid = false;
	//alert(aktif);
	if(nmUnit.replace(/ /g,"") == "") // file upload tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = "Unit Name still empty";
		document.getElementById('nmUnit').focus();
		return false;
	}
	
	var i = 0;
    while (!aktifValid && i < aktif.length) {
        if (aktif[i].checked) aktifValid = true;
        i++;        
    }
	if (!aktifValid)
	{ 
		document.getElementById('errorMsg').innerHTML = "Active type has not choosen yet";
		return false;
	}
	
	var qtyAdaTidak = document.getElementById('qtyAdaTidak').value;
	if(qtyAdaTidak == "ada") //jika file ada di database
	{
		document.getElementById('errorMsg').innerHTML = "Item name already exists!";
		document.getElementById('nmUnit').focus();
		return false;
	}
	
	var answer  = confirm("Are you sure want to save?");
	if(answer)
	{
		formQty.submit();
	}
	else
	{	return false;	}
}

function hapus(id)
{
	var answer  = confirm("Are you sure want to delete ?");
	if(answer)
	{
		document.getElementById(id).click();
	}
	else
	{	return false;	}
}

function report(reportText)
{
	document.getElementById('report').innerHTML = reportText;
	
	setTimeout(function()
	{
		document.getElementById('report').innerHTML = "";
	},10000);
}

function clear()
{
	document.getElementById('nmUnit').value = "";
	document.getElementById('aksi').value = "new";
}

function ajaxGetFile(namaFile, aksi, idHalaman)
{
	var mypostrequest=new ajaxRequest()
	mypostrequest.onreadystatechange=function()
	{
		if (mypostrequest.readyState==4)
		{
			if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1)
			{
				document.getElementById(idHalaman).innerHTML=mypostrequest.responseText;
			}
		}
	}
	
	if(aksi == "cekQtyAdaTidak")
	{
		var unitId = document.getElementById('unitId').value;
		var nmUnit = document.getElementById('nmUnit').value.replace(/&/g,"%26");
		var parameters="halaman="+aksi+"&nmUnit="+nmUnit+"&unitId="+unitId;
	}
	
	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}
</script>
<?php
$unitIdGet = $_GET['unitid'];

$judul = "";
$dis = "disabled";
$defaulActive = "checked";
$btnCancel = "<button type=\"button\" id=\"btnCancel\" class=\"btnStandarDisabled\" onClick=\"document.getElementById('cancelEdit').click();\" title=\"Cancel Edit Master\" disabled>
					<table id=\"tblCancel\" height=\"24\">
						<tr>
							<td align=\"center\" width=\"20\"><img src=\"../picture/arrow-return-180-left.png\"></td>
							<td align=\"left\">Cancel&nbsp;</td>
						</tr>
					</table>
				</button>";

if($aksiGet == "")
{
	$aksiGet = "new";
}
if($aksiGet == "edit")
{
	$judul = "Edit";
	$nmUnit = $CReqAtk->detilMstUnit("unitname", $unitIdGet);
	$dis = "";
	$defaulActive = "";
	$btnCancel = "<button type=\"button\" id=\"btnCancel\" class=\"btnStandar\" onClick=\"document.getElementById('cancelEdit').click();\" title=\"Cancel Edit Master\" >
						<table id=\"tblCancel\" height=\"24\">
							<tr>
								<td align=\"center\" width=\"20\"><img src=\"../picture/arrow-return-180-left.png\"></td>
								<td align=\"left\">Cancel&nbsp;</td>
							</tr>
						</table>
					</button>";
	
	$readOnlyGet = $_GET['readOnly'];
	
	$active = $CReqAtk->detilMstUnit("active", $unitIdGet);
	$cAct = "";
	$cNAct = "";
	if($active == "Y")
	{
		$cAct = "checked";
	}
	if($active == "N")
	{
		$cNAct = "checked";
	}
}

if($aksiGet == "delete")
{
	$unitIdGet = $_GET['unitId'];
	$nmUnit = $CReqAtk->detilMstUnit("unitname", $unitIdGet);
	//echo $unitIdGet." | ".$nmUnit;
	$query = $CKoneksiAtk->mysqlQuery("DELETE FROM mstunit WHERE unitid=".$unitIdGet."");
	$CHistory->updateLogReqAtk($userIdLogin, "Hapus Master Unit (unitid = <b>".$unitIdGet."</b>, nama unit = <b>".$nmUnit."</b>)");
}

if($aksiPost == "new")
{
	$nmUnit = mysql_real_escape_string($_POST['nmUnit']);
	$aktif = $_POST['aktif'];
	//echo "submit new".$nmUnit;
	$query = $CKoneksiAtk->mysqlQuery("INSERT INTO mstunit (unitname,active) VALUES ('".$nmUnit."','".$aktif."');");
	$lastInsertId = mysql_insert_id();
	
	$CHistory->updateLogReqAtk($userIdLogin, "Buat Master Unit baru (unitid = <b>".$lastInsertId."</b>, nama unit = <b>".$nmUnit."</b>)");
}

if($aksiPost == "edit")
{
	$unitIdPost = $_POST['unitId'];
	$nmUnit = mysql_real_escape_string($_POST['nmUnit']);
	$nmUnitBefore = $_POST['nmUnitBefore'];
	$aktif = $_POST['aktif'];
	
	//echo $unitIdPost." | ".$nmUnit." | ".$nmUnitBefore;
	$query = $CKoneksiAtk->mysqlQuery("UPDATE mstunit SET unitname='".$nmUnit."', active='".$aktif."' WHERE unitid=".$unitIdPost."");
	$CHistory->updateLogReqAtk($userIdLogin, "Edit Master Unit (unitid = <b>".$unitIdPost."</b>, nama unit sebelumnya = <b>".$nmUnitBefore."</b>, nama unit sekarang = <b>".$nmUnit."</b>)");
}
?>

<body>
<center>
<form action="halQtyMaster.php" name="formQty" id="formQty" method="post" enctype="multipart/form-data">
<table cellpadding="0" cellspacing="5" width="99%"  border="0">
    <tr>
        <td width="40%">
        <!--<div id="qtyList" style="height:123;overflow:scroll;overflow-x: hidden;top: expression(offsetParent.scrollTop);">-->
        <div id="qtyList" style="height:123px;overflow-y: scroll;">
            <table cellpadding="0" cellspacing="5" width="100%">
            <?php
                $i=1;
                $sql = $CKoneksiAtk->mysqlQuery("SELECT * FROM mstunit ORDER BY unitname ASC");
                while($r = $CKoneksiAtk->mysqlFetch($sql))
                {
					$btnDel = "<img src=\"../../picture/Button-Cross-red-32.png\" width=\"20\" onClick=\"hapus('deleteMaster".$i."');\" onMouseOver=\"this.style.backgroundColor='#FF888B';\" onMouseOut=\"this.style.backgroundColor='transparent';\" style=\"vertical-align:middle;\" title=\"Delete Cart Item\"/>";
					
					$readOnly = "";
					$cekTransMstUnit = $CReqAtk->cekTransMstUnit($r['unitname']);
					$cekMstUnit = $CReqAtk->cekMstUnitOnMstItem($r['unitname']);
					//echo $cekMstUnit;
					if($cekTransMstUnit > 0 || $cekMstUnit > 0)
					{
						$btnDel = "";
						$readOnly = "readonly";
					}
					
                    $onClickTr = "document.getElementById('idHref".$i."').click();enableCancel();";
					
                    $href = "<a id=\"idHref".$i."\" href=\"halQtyMaster.php?aksi=edit&unitid=".$r['unitid']."&readOnly=".$readOnly."\"/>";
                    $delete = "<a id=\"deleteMaster".$i."\" href=\"halQtyMaster.php?aksi=delete&unitId=".$r['unitid']."\"/>";
            ?>
            <tr height="26">
                <td class="tdMyFolder" onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td  style="font-size:13px;color:#009;font-weight:bold;font-family:Tahoma;" width="12%" align="center" onClick="">
                            <?php echo $i.$href.$delete;?></td>
                        <td align="left" width="83%" title="<?php echo $r['unitname'];?>" onClick="<?php echo $onClickTr;?>">
                            <span style="font-family:Arial Narrow;font-size:14px;color:#333;">
                            <?php echo $CPublic->potongKarakter($r['unitname'],100); ?>
                            </span>
                        </td>
                        <td rowspan="3" width="5%">
                            <?php echo $btnDel;?>
                        </td>
                    </tr>
                </table>
                </td>
            </tr>
            <?php $i++;} ?>
            </table>
        </div>
        </td>
        <td valign="top" width="60%">
            <table cellpadding="0" cellspacing="5" width="100%">
            <tr valign="top">
                <td height="28" width="22%" style="font-family:Tahoma;font-size:12px;color:#333;">Unit Name</td>
                <td>
                <input type="text" class="elementDefault" id="nmUnit" name="nmUnit" style="width:99%;height:15px;" value="<?php echo $nmUnit;?>" onFocus="ajaxGetFile(this.value, 'cekQtyAdaTidak', 'tdQtyAdaTidak');" onKeyUp="ajaxGetFile(this.value, 'cekQtyAdaTidak', 'tdQtyAdaTidak');" <?php echo $readOnlyGet;?>>
                </td>

            </tr>
            <tr>
                <td></td>
                <td style="font-family:Tahoma;font-size:12px;color:#333;">
                    <input type="radio" id="aktif" name="aktif" value="Y" <?php echo $defaulActive.$cAct;?>/> active
                    <input type="radio" id="aktif" name="aktif" value="N" <?php echo $cNAct;?>/> not-active
                </td>
            </tr>
            <tr>
                <td colspan="2" height="20" align="center" valign="middle">&nbsp; 
                <span id="errorMsg" class="errorMsg"></span>
                <span id="report" class="report"></span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" id="btnSave" class="btnStandar" onClick="submitFile(); return false;" title="Cancel Edit Master">
                        <table id="tblAdd" height="24">
                            <tr>
                                <td align="center" width="20"><img src="../picture/disk-black.png"></td>
                                <td align="left">Save&nbsp;</td>
                            </tr>
                        </table>
                    </button>&nbsp;
                    <?php echo $btnCancel;?>
                </td>
            </tr>
            
            </table>
        </td>
    </tr>
    
    <tr>
    	<td></td>
        <td height="20" id="tdQtyAdaTidak">
            <input type="hidden" id="qtyAdaTidak" name="qtyAdaTidak" value="kosong">
        </td>
    </tr>
    <tr>
        <td>
            <input type="text" id="aksi" name="aksi" value="<?php echo $aksiGet;?>"/>
            <input type="text" id="unitId" name="unitId" value="<?php echo $unitIdGet;?>"/>
            <input type="hidden" id="nmUnitBefore" name="nmUnitBefore" value="<?php echo $nmUnit;?>">
            <a id="cancelEdit" href="halQtyMaster.php?aksi=new"/>
        </td>
        <td id="tdFileAdaTidak">
            &nbsp;<input type="hidden" id="qtyAdaTidak" name="qtyAdaTidak" value="kosong">
        </td>
    </tr>
</table>
</form>
</center>
</body>
<?php
if($aksiPost == "new" || $aksiPost == "edit" || $aksiGet == "delete")
{
	if($aksiPost == "new")
	{
		$report = "Qty type succesfully Added";
	}
	if($aksiPost == "edit")
	{
		$report = "Qty type succesfully Edit";
	}
	if($aksiGet == "delete")
	{
		$report = "Qty type succesfully Delete";
	}
?>
	<script language="javascript">
		//parent.tutup();
		report('<?php echo $report;?>')
		clear();
	</script>	
<?php
}
?>
</HTML>