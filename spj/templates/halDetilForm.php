<!DOCTYPE HTML>
<?php
require_once("../../config.php");
require_once("../configSpj.php");

$formIdGet = $_GET['formId'];

$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM form WHERE formid = ".$formIdGet." AND deletests=0;");
$row = $CKoneksiSpj->mysqlFetch($query);
//echo "a ".$halamanGet;
$dateForm = $row['datefrom'];
$thn =  substr($dateForm,0,4);
$bln =  substr($dateForm,4,2);
$tgl =  substr($dateForm,6,2);
$formDate =  $tgl." ".$CPublic->detilBulanNamaAngka($bln, "ind")." ".$thn;

$dateTo = $row['dateto'];
$thnTo =  substr($dateTo,0,4);
$blnTo =  substr($dateTo,4,2);
$tglTo =  substr($dateTo,6,2);
$toDate =  $tglTo." ".$CPublic->detilBulanNamaAngka($blnTo, "ind")." ".$thnTo;

$cashAdv = "";
if($row['cashadvance'] > 0)
{
    $cashAdv = "(".$row['currency'].") ".number_format($row['cashadvance'],2);
}

$vslNya = "";
if($row['vessel_name'] != "" AND $row['vessel_code'] != "0")
{
    $vslNya = $row['vessel_name'];
}

if($aksiGet == "ubahStatus")
{
	$formIdGet = $_GET['formId'];
	$userFullNm = $CSpj->detilLoginSpj($CSpj->detilForm($formIdGet, "ownerid"), "userfullnm", $db);
	$dest = $CSpj->detilForm($formIdGet, "destination");
	
	$CKoneksiSpj->mysqlQuery("UPDATE form SET status = 'Processed', updusrdt = '".$CPublic->userWhoAct()."' WHERE formid = ".$formIdGet.";");
	$CHistory->updateLogSpj($userIdLogin, "Read Form SPJ (formid = <b>".$formIdGet."</b>, ownerform = <b>".$userFullNm."</b>, tujuan dinas = <b>".$dest."</b>)");
}
?>

<link href="../css/cssSpj.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>

<script>
$(document).ready(function() {
    parent.doneWait();
});

//Automatically setting height of textarea to height of its contents
$(function() {
    $('textarea').each(function() {
        $(this).height($(this).prop('scrollHeight'));
    });
});
</script>

<table cellpadding="0" cellspacing="0" width="100%">
    <!-- header -->
    <?php 
if($row['createdby'] != 00000)
{	
?>
    <tr>
        <td align="left" class="fontMyFolderList" style="font-style:italic;font-size:10px;color:#00F;">
            Created By <?php echo $CSpj->detilLoginSpj($row['createdby'], "userfullnm", $db);?>
        </td>
    </tr>
    <tr>
        <td colspan="2" height="5"></td>
    </tr>
    <?php } ?>
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td height="2px"></td>
                </tr>
                <tr>
                    <td style="font-size:14px;">
                        <?php
			$spjNo = $row['spjno'];
			$nomor = "";
			if($spjNo != "")
			{
        		$nomor = "&nbsp;<u><b>Nomor : ".$row['spjno']."</b></u>";
			}
			
			echo $nomor;
		?>
                    </td>
                    <td align="right">
                        <?php // Menentukan Perusahaan -> Andhika atau Adnyana
			$img = "<img src=\"../picture/Tulisan Biru PT Andhika Lines.JPG\" height=\"22 px\"/>";
			$comp = $row['kdcmp'];
			if($comp == "02")
			{
				$img = "<img src=\"../picture/Tulisan Biru PT Adnyana.JPG\" height=\"22px\"/>";
			}
		?>
                        <?php echo "<img src=\"../company/".$CSpj->detilCmp($row['kdcmp'], "logo")."\" height=\"22px\"/>";?>&nbsp;

                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td height="5"></td>
    </tr>
    <?php
if($row['status'] == "Revise" || $row['status'] == "Cancel")
{
?>
    <tr>
        <td>
            <table class="spjTdMyFolder fontMyFolderList" cellpadding="0" cellspacing="0" width="100%">
                <tr height="18px">
                    <td width="1%"></td>
                    <td width="14%" align="left"><?php echo $row['status'];?> Request</td>
                    <td width="85%" align="left" style="color:#000080;">
                        <?php echo $CSpj->detilLoginSpjByEmpno($row['reasonempno'], "userfullnm", $db);?></td>
                </tr>
                <tr height="18px">
                    <td></td>
                    <td align="left" valign="top"><?php echo $row['status'];?> Reason</td>
                    <td align="left">
                        <textarea
                            style="color:#000080;font-family:Tahoma;font-size:12px;width:98%;border:none;overflow:hidden;"
                            readonly><?php echo $row['reason'];?></textarea>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td height="5"></td>
    </tr>
    <?php
}
?>
    <!-- tabel detil -->
    <tr>
        <td align="center">
            <table class="spjTdMyFolder fontMyFolderList" cellpadding="0" cellspacing="3" width="98%">
                <tr height="17px">
                    <td width="15%" align="left">
                        Nama
                    </td>
                    <td width="35%" align="left" style="color:#000080;">
                        <?php echo ucwords(strtolower($row['ownername']));?>
                    </td>
                    <td width="18%" align="left">
                        Keperluan
                    </td>
                    <td width="32%" align="left" style="color:#000080;">
                        <?php echo $row['necessary'];?>
                    </td>
                </tr>

                <tr height="17px">
                    <td align="left">
                        Jabatan
                    </td>
                    <td align="left" style="color:#000080;">
                        <?php echo ucwords(strtolower($CEmployee->detilJabatan($CEmployee->detilTblEmpGen($row['ownerempno'], "kdjabatan"), "nmjabatan")));?>
                    </td>
                    <td align="left">
                        Tanggal Berangkat
                    </td>
                    <td align="left" style="color:#000080;">
                        <?php echo $formDate;?>
                    </td>
                </tr>

                <tr height="17px">
                    <td align="left">
                        Golongan
                    </td>
                    <td align="left" style="color:#000080;">
                        <?php echo ucwords(strtolower($CEmployee->detilPangkat($CEmployee->detilTblEmpGen($row['ownerempno'], "kdpangkat"), "nmpangkat")));?>
                    </td>
                    <td align="left">
                        Tanggal Kembali
                    </td>
                    <td align="left" style="color:#000080;">
                        <?php echo $toDate;?>
                    </td>
                </tr>

                <tr height="17px">
                    <td align="left">
                        Tempat Tujuan
                    </td>
                    <td align="left" style="color:#000080;">
                        <?php echo $row['destination'];?>
                    </td>
                    <td align="left">
                        Kendaraan
                    </td align="left">
                    <td align="left" style="color:#000080;">
                        <?php echo $row['vehicle'];?>
                    </td>
                </tr>

                <tr height="17px">
                    <td align="left" valign="top">
                        Vessel
                    </td>
                    <td align="left" valign="top" style="color:#000080;">
                        <?php echo $vslNya;?>
                    </td>
                    <td align="left" valign="top">
                        Cash Advance
                    </td>
                    <td align="left" valign="top" style="color:#000080;">
                        <?php echo $cashAdv;?>
                    </td>
                </tr>

                <tr height="17px">
                    <td align="left" valign="top">
                        Catatan
                    </td>
                    <td align="left" valign="top">
                        <textarea
                            style="color:#000080;font-family:Tahoma;font-size:12px;width:240px;border:none;overflow:hidden;"
                            readonly><?php echo $row['note'];?></textarea>
                    </td>
                    <td align="left" valign="top">
                        Pengikut <br>
                        <?php 
            $cBtnAdd = "class = \"spjBtnStandar\"";
            if ($row['status'] == "Completed") 
            {
                $cBtnAdd = 'class = "spjBtnStandarDis" disabled = "disabled"';
            }
				if($userJenisSpj == "admin" && $halamanGet == "spjFormAll" &&$row['aprvempno'] != 00000 && $row['knowempno'] != 00000)
				{
					echo '<button type="button" id="btnGen" '.$cBtnAdd.' onclick="parent.openFollowNya(\''.$formIdGet.'\'); return false;" style="margin:5px 0px 0px 0px; width:40px;height:20px;" title="Membuat / edit Tembusan">
						<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
						  <tr>
							<td align="center">Add</td>
						  </tr>
						</table>
					</button>';
				}
			?>
                    </td>
                    <td align="left" style="color:#000080;" valign="top">
                        <?php
			echo $CSpj->menuFollower($formIdGet, $db);
		?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" height="2"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td height="5"></td>
    </tr>
    <!-- Data Approval -->
    <tr>
        <td align="center">
            <table class="fontMyFolderList" style="font-size:13px;" cellpadding="0" cellspacing="3" width="98%">
                <tr align="left">
                    <td width="14%">
                        Dikeluarkan di
                    </td>
                    <td width="86%" style="color:#000080;">
                        Jakarta
                    </td>
                </tr>
                <tr align="left">
                    <td>
                        Pada Tanggal
                    </td>
                    <td style="color:#000080;">
                        <?php
			$tglSpj = "<i>Waiting . . .</i>";
			if($row['spjdate'] != "")
			{
				$tglSpjDb = $row['spjdate'];
				$thnSpj =  substr($tglSpjDb,0,4);
				$blnSpj =  substr($tglSpjDb,4,2);
				$tglSpj =  substr($tglSpjDb,6,2);
				$tglSpj =  $tglSpj." ".$CPublic->detilBulanNamaAngka($blnSpj, "ind")." ".$thnSpj;
			}
			
			echo $tglSpj;
		?>
                    </td>
                </tr>
                <tr>
                    <td height="5" style="border-bottom:solid 1px #CCC;"></td>
                    <td></td>
                </tr>
                <tr>
                    <td height="3" colspan="2"></td>
                </tr>

                <tr align="left">
                    <td>
                        Menyetujui
                    </td>
                    <td style="color:#000080;">
                        <?php
			$ownerEmpNo = $CSpj->detilForm($formIdGet, "ownerempno"); // Employee Number pemilik SPJ Form
			//kode divisi owner form
			//$kdDiv = $CEmployee->detilTblEmpGen($ownerEmpNo, "kddiv");
			
			$kadivEmpNo = $CEmployee->detilDiv($CEmployee->detilTblEmpGen($ownerEmpNo, "kddiv"), "divhead");// Kadiv Employee Number dari pemilik SPJ Form

			if($row['aprvempno'] == "00000" && $ownerEmpNo != $kadivEmpNo && $row['aprvbyadm'] == "N")// Jika belum Approved
			{
				$html = "[Kepala Divisi / perwakilan]";
			}
			if($row['aprvempno'] == "00000" && $ownerEmpNo == $kadivEmpNo && $row['aprvbyadm'] == "N")// Jika belum Approved
			{
				$html = "[Atasan / perwakilan]";
			}
			if($row['aprvempno'] != "00000" && $row['aprvbyadm'] == "N")// Jika sudah Approved
			{
				$html = $CSpj->detilLoginSpjByEmpno($row['aprvempno'], "userfullnm", $db);
			}
			if($row['aprvbyadm'] == "Y")// Jika Approved dari Administrator
			{
				// $html = "<i>Approved by Administrator</i>";
                $html = $CSpj->detilLoginSpjByEmpno($row['aprvempno'], "userfullnm", $db);
			}
			echo $html;
		?>

                    </td>
                </tr>
                <tr align="left">
                    <td>
                        Status
                    </td>
                    <td style="color:#000080;">
                        <?php
			$statAprv = "<i>Waiting . . .</i>";
			if($row['aprvempno'] != 00000)
			{
				$statAprv = "<i>APPROVED</i>";
			}
			
			
			echo $statAprv;
		?>
                    </td>
                </tr>
                <tr>
                    <td height="5" style="border-bottom:solid 1px #CCC;"></td>
                    <td></td>
                </tr>
                <tr>
                    <td height="2" colspan="2"></td>
                </tr>

                <tr align="left">
                    <td>
                        Mengetahui
                    </td>
                    <td style="color:#000080;">
                        <?php
			$know = "[Kepala Divisi HR / perwakilan]";
			if($row['knowempno'] != 00000 && $row['knowbyadm'] == "N")//jika Sudah Acknowledge
			{
				$know = $CSpj->detilLoginSpjByEmpno($CSpj->detilForm($formIdGet, "knowEmpno"), "userfullnm", $db);
                if($row['kadivempno'] == "00625")
                {
                    $know = $CSpj->detilLoginSpjByEmpno($CEmployee->detilDiv("050", "divhead"), "userfullnm", $db);
                }
			}
			if($row['knowempno'] != 00000 && $row['knowbyadm'] == "Y")
			{
                $know = $CSpj->detilLoginSpjByEmpno($CSpj->detilForm($formIdGet, "knowEmpno"), "userfullnm", $db);
				// $know = "<i>Acknowledged by Administrator</i>";
                if($row['kadivempno'] == "00625")
                {
                    $know = $CSpj->detilLoginSpjByEmpno($CEmployee->detilDiv("050", "divhead"), "userfullnm", $db);
                }
            }

			echo $know;
		?>
                    </td>
                </tr>
                <tr align="left">
                    <td>
                        Status
                    </td>
                    <td style="color:#000080;">
                        <?php
			$statKnow = "<i>Waiting . . .</i>";
			if($row['knowempno'] != 00000)
			{
				$statKnow = "<i>ACKNOWLEDGED</i>";
			}
			
			echo $statKnow;
		?>
                    </td>
                </tr>
                <tr>
                    <td height="5" style="border-bottom:solid 1px #CCC;"></td>
                    <td></td>
                </tr>

                <?php if($row['aprvempno'] != 00000 && $row['knowempno'] != 00000)//tampil jika FORM sudah Approved dan Acknowledge
	{
	?>
                <tr align="left">
                    <td valign="top">
                        <?php
			$status = $CSpj->detilForm($formIdGet, "status");
			
			$tembusan = "Tembusan";
			if($userJenisSpj == "admin" && $halamanGet == "spjFormAll")
			{
				$tembusan = '<button type="button" id="btnGen" '.$cBtnAdd.' onclick="parent.openThickboxWindow(\''.$formIdGet.'\'); return false;" style="width:78px;height:23px;" title="Membuat / edit Tembusan">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                  <tr>
                    <td align="center">Tembusan</td>
                  </tr>
                </table>
            </button>';
			}
			echo $tembusan;
		?>

                    </td>
                    <td style="color:#000080;">
                        <?php
            $listTembusan = $CSpj->listTembusan($formIdGet);
			
			$tipe = "new";
			if($listTembusan != 0)
			{
				$tipe = "edit";
				$listTembusan = $listTembusan;
			}
			if($listTembusan == 0)
			{
				$listTembusan = "&nbsp;&nbsp;-";
			}
			
			echo $listTembusan;
        ?>
                        <input type="hidden" id="jmlCopy" name="jmlCopy"
                            value="<?php echo $CSpj->jmlTembusan($formIdGet);?>" />
                        <!-- keperluan untuk validasi tembusan ada/belum -->
                        <input type="hidden" id="tipe" name="tipe" value="<?php echo $tipe?>" />
                    </td>
                </tr>
                <tr>
                    <td height="5" style="border-bottom:solid 1px #CCC;"></td>
                    <td></td>
                </tr>
                <?php } ?>
            </table>
        </td>
    </tr>
</table>

</HTML>