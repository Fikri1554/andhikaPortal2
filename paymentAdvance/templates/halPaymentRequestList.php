<?php
	require_once("../configPaymentAdvance.php");

	$aksi = $_GET['aksi'];

	$batchNo = "";

	if($aksi == "display")
	{
		$batchNo = date('Ym');
	}

	if($aksi == "search")
	{
		$thnBln = $_GET['thnBln'];
		$dayNya = $_GET['dayNya'];

		if($dayNya == "all")
		{
			$batchNo = $thnBln;
		}else{
			$batchNo = $thnBln.$dayNya;
		}
	}

?>
<!DOCTYPE HTML>
<!-- <script type="text/javascript" src="../../js/jquery-1.4.3.js"></script> -->
<script type="text/javascript" src="../js/jquery-1.8.0.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script language="javascript">
$(window).scroll(function() {
    $('#judul').css('left', '-' + $(window).scrollLeft() + 'px');
});

function onlickNya(idTr, id, color) {
    parent.showBtnNya();
    var ceklastTrId = document.getElementById("txtLastTrId").value;
    var ceklastTrcColor = document.getElementById("txtLastTrColor").value;
    //$('[id^=idTr_]').css('background-color','#FFFFFF');	
    if (ceklastTrId != "") {
        $('[id=idTr_' + ceklastTrId + ']').css('background-color', ceklastTrcColor);
    }

    document.getElementById('idTr_' + idTr).onmouseout = '';
    document.getElementById('idTr_' + idTr).onmouseover = '';
    document.getElementById('idTr_' + idTr).style.backgroundColor = '#B0DAFF';

    document.getElementById("txtLastTrId").value = idTr;
    document.getElementById("txtLastTrColor").value = color;

    parent.document.getElementById('txtIdEdit').value = id;
}

function onclickUploadFile(idTr, id, color) {
    parent.enabledBtn('btnUploadFile');
    var ceklastTrId = document.getElementById("txtLastTrId").value;
    var ceklastTrcColor = document.getElementById("txtLastTrColor").value;
    // $('[id^=idTr_]').css('background-color','#FFFFFF');
    if (ceklastTrId != "") {
        $('[id=idTr_' + ceklastTrId + ']').css('background-color', ceklastTrcColor);
    }

    document.getElementById('idTr_' + idTr).onmouseout = '';
    document.getElementById('idTr_' + idTr).onmouseover = '';
    document.getElementById('idTr_' + idTr).style.backgroundColor = '#B0DAFF';

    document.getElementById("txtLastTrId").value = idTr;
    document.getElementById("txtLastTrColor").value = color;

    parent.document.getElementById('txtIdEdit').value = id;

    parent.disabledBtn('btnPayReqEdit');
    parent.disabledBtn('btnPayReqDelete');
    parent.disabledBtn('btnSubmitReq');
    parent.disabledBtn('btnSettlement');
}

function onclickSettlement(idTr, id, color) {
    parent.enabledBtn('btnSettlement');
    var ceklastTrId = document.getElementById("txtLastTrId").value;
    var ceklastTrcColor = document.getElementById("txtLastTrColor").value;
    // $('[id^=idTr_]').css('background-color','#FFFFFF');
    if (ceklastTrId != "") {
        $('[id^=idTr_' + ceklastTrId + ']').css('background-color', ceklastTrcColor);
    }

    document.getElementById('idTr_' + idTr).onmouseout = '';
    document.getElementById('idTr_' + idTr).onmouseover = '';
    document.getElementById('idTr_' + idTr).style.backgroundColor = '#B0DAFF';

    document.getElementById("txtLastTrId").value = idTr;
    document.getElementById("txtLastTrColor").value = color;

    parent.document.getElementById('txtIdEdit').value = id;

    parent.disabledBtn('btnPayReqEdit');
    parent.disabledBtn('btnPayReqDelete');
    parent.disabledBtn('btnSubmitReq');
    parent.disabledBtn('btnUploadFile');
}
</script>
<link href="../css/table.css" rel="stylesheet" type="text/css" />

<body onUnload="saveScroll('halPaymentRequestList')">
    <input type="hidden" id="txtLastTrId" value="">
    <input type="hidden" id="txtLastTrColor" value="">

    <div class="loader" id="loaderImg" style="visibility:hidden;"></div>
    <div style="width:100%;overflow-x:auto;white-space:nowrap;height:450px;">
        <table id="judul" width="2450" cellpadding="0" cellspacing="0"
            style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;left:0px;top:0px;">
            <thead>
                <tr align="center">
                    <td width="80" colspan="2" style="height:30px;vertical-align:middle;text-align:center;">NO</td>
                    <td width="155" style="height:30px;vertical-align:middle;text-align:center;">REQ. NAME</td>
                    <td width="100" style="height:30px;vertical-align:middle;text-align:center;">BARCODE</td>
                    <td width="250" style="height:30px;vertical-align:middle;text-align:center;">COMPANY</td>
                    <td width="300" style="height:30px;vertical-align:middle;text-align:center;">DIVISI</td>
                    <td width="100" style="height:30px;vertical-align:middle;text-align:center;">INV. DATE</td>
                    <td width="100" style="height:30px;vertical-align:middle;text-align:center;">DUE DATE</td>
                    <td width="150" style="height:30px;vertical-align:middle;text-align:center;">INV. NUMBER</td>
                    <td width="130" style="height:30px;vertical-align:middle;text-align:center;">AMOUNT</td>
                    <td width="200" style="height:30px;vertical-align:middle;text-align:center;">REMARK</td>
                    <td width="100" style="height:30px;vertical-align:middle;text-align:center;">TRANS NO</td>
                    <td width="100" style="height:30px;vertical-align:middle;text-align:center;">
                        SETTLEMENT<br>TRANS&nbsp;NO</td>
                    <td width="100" style="height:30px;vertical-align:middle;text-align:center;">ENTRY DATE</td>
                    <td width="100" style="height:30px;vertical-align:middle;text-align:center;">ENTRY USER</td>
                    <td width="100" style="height:30px;vertical-align:middle;text-align:center;">CONFIRM<br>DATE</td>
                    <td width="100" style="height:30px;vertical-align:middle;text-align:center;">TRANSFER<br>DATE</td>
                    <td width="100" style="height:30px;vertical-align:middle;text-align:center;">SETTLEMENT<br>DATE</td>
                    <td width="200" style="height:30px;vertical-align:middle;text-align:center;">REMARK BUKTI</td>
                    <td width="200" style="height:30px;vertical-align:middle;text-align:center;">REJECT</td>
                </tr>
            </thead>
            <?php
	$trNya = "";
	$no = 1;
	$userId = $userIdSession;
	$cekAllData = $CPaymentAdv->cekAksesBtn($userId,"btn_payment_request_allData");
	$cekBtnUpload = $CPaymentAdv->cekAksesBtn($userId,"btn_payment_request_upload");
	$kdDiv = $CEmployee->detilTblEmpGen($userEmpNo, "kddiv");
	$nmDiv = $CEmployee->detilDiv($kdDiv, "nmdiv");

	$whereNya = "WHERE batchno LIKE '%".$batchNo."%' AND st_delete = '0'";

	if($userJenisPaymentAdv == "user" AND $cekAllData == "N")
	{
		if($userId == "00107" OR $userId == "00106")// rima
		{
			$whereNya .= " AND divisi = 'BOD SECRETARY' ";
		}else{
			$whereNya .= " AND divisi = '".$nmDiv."' ";
		}
	}

	$query = $CkoneksiPaymentAdv->mysqlQuery("SELECT * FROM datapayment ".$whereNya, $CkoneksiPaymentAdv->bukaKoneksi());

	while($row = $CkoneksiPaymentAdv->mysqlFetch($query))
	{
		$onclickNya = "onClick=\"onlickNya('".$no."','".$row['id']."','#FFFFFF');\"";
		$imgNya = "";
		$fileNya = "";
		$remarkReject = "";
		$fontColor = "";
		$rowColor = "background-color:#FFFFFF;cursor:default;";
		$remarkBukti = "";
		$fileBukti = "";
		$colorMouseOver = "#D9EDFF";
		$colorMouseOut = "#FFFFFF";
		$userEntry = "-";

		if($row['st_submit'] == "Y")
		{
			$onclickNya = "";
			$rowColor = "background-color:#86D18C;cursor:default;";
			$colorMouseOut = "#86D18C";
		}
		if($row['st_confirm'] == "Y")
		{
			$onclickNya = "";
			$rowColor = "background-color:#d79c73;cursor:default;";
			$imgNya = "<img src=\"../picture/hourglass--pencil.png\" width=\"14\" title=\"CONFIRMED\">";
			$colorMouseOut = "#d79c73";
		}
		if($row['st_check'] == "Y")
		{
			$onclickNya = "";
			$imgNya = "<img src=\"../picture/tick.png\" width=\"14\" title=\"CHECK\">";
		}
		if($row['st_approve'] == "Y")
		{
			$onclickNya = "";
			$imgNya = "<img src=\"../picture/document-task.png\" width=\"14\" title=\"APPROVE\">";
		}
		if($row['st_release'] == "Y")
		{
			$onclickNya = "";
			$imgNya = "<img src=\"../picture/thumb-up.png\" width=\"14\" title=\"RELEASE\">";
		}
		if($row['transno'] > 0)
		{
			$onclickNya = "";
			$imgNya = "<img src=\"../picture/document--arrow.png\" width=\"14\" title=\"PREPARE\">";
		}
		if($row['voucher_status'] == "Y")
		{
			$imgNya = "<img src=\"../picture/document-sticky-note.png\" width=\"14\" title=\"VOUCHER\">";

			if($row['st_bukti'] == "Y" )
			{
				$rowColor = "background-color:#F37C08;cursor:default;";
				$onclickNya = "";
				$remarkBukti = $row['bukti_remark'];
				$colorMouseOut = "#F37C08";

				if($row['bukti_file'] != "" )
				{
					$fileBukti = " <a href=\"../templates/fileUploadBukti/".$row['bukti_file']."\" target=\"_blank\" title=\"Bukti Transfer\">";
					$fileBukti .= "<img src=\"../picture/Presentation-blue-32.png\" width=\"12\" title=\"Bukti Transfer\"></a>";
				}				
			}else{
				$rowColor = "background-color:#CFCE83;";
				$onclickNya = "onClick=\"onclickUploadFile('".$no."','".$row['id']."','#CFCE83');\"";
				$colorMouseOut = "#CFCE83";

				if($userJenisPaymentAdv == "user" AND $cekBtnUpload == "N")//jika jenis user
				{
					$rowColor = "background-color:#CFCE83;cursor:default;";
					$onclickNya = "";
				}
			}
		}

		if($row['file_upload'] != "")
		{
			$fileNya = "<a href=\"../templates/fileUpload/".$row['file_upload']."\" target=\"_blank\" title=\"View File\">";
			$fileNya .= "<img src=\"../picture/document-text-image.png\" width=\"12\" title=\"View File\"></a>";
		}

		if($row['reject_status'] == "Y" AND $row['st_submit'] == "N")
		{
			$remarkReject = $row['reject_remark'];
			$fontColor = "color:red;";
		}

		if($row['doc_type'] == "advance")
		{
			if($row['st_bukti'] == "Y" AND $row['st_settlement'] == "N")
			{
				$rowColor = "background-color:#F37C08;cursor:pointer;";
				$colorMouseOut = "#F37C08";
				if($row['doc_type'] == "advance")
				{
					$onclickNya = "onClick=\"onclickSettlement('".$no."','".$row['id']."','#F37C08');\"";
				}else{
					$onclickNya = "";
				}
			}

			if($row['st_settlement'] == "Y" AND $row['st_settlementCheck'] == "Y")
			{
				$rowColor = "background-color:#F37C08;cursor:pointer;";
				if($row['settlement_voucher_status'] == "Y" AND $row['settlement_transferToAcct'] == "Y")
				{
					$imgNya = "<img src=\"../picture/Lock-Lock-icon.png\" width=\"14\" title=\"COMPLETED\">";
				}
				$onclickNya = "onClick=\"onclickSettlement('".$no."','".$row['id']."','#F37C08');\"";
			}
		}else{
			if($row['st_bukti'] == "Y")
			{
				$rowColor = "background-color:#FFACAC;cursor:default;";
				$imgNya = "<img src=\"../picture/Lock-Lock-icon.png\" width=\"14\" title=\"COMPLETED\">";
				$colorMouseOut = "#FFACAC";
			}
		}

		if($row['add_userId'] != "00000")
		{
			$userEntry = $CPaymentAdv->getUserNamePortal($row['add_userId'],"userfullnm");
		}

		$transNo = "";
		if($row['transno'] > 0)
		{
			$transNo = $CPaymentAdv->getFormatNo($row['transno'],6);
		}

		$settlementTransNo = "";
		if($row['settlement_transno'] > 0)
		{
			$settlementTransNo = $CPaymentAdv->getFormatNo($row['settlement_transno'],6);
		}

		$trNya .= "<tr id=\"idTr_".$no."\" onMouseOver=\"this.style.backgroundColor='".$colorMouseOver."';\" onMouseOut=\"this.style.backgroundColor='".$colorMouseOut."';\" style=\"cursor:pointer;padding-bottom:1px;".$fontColor.$rowColor."\" ".$onclickNya.">";
			$trNya .= "<td width=\"60\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;\" align=\"center\">".$imgNya.$fileNya.$fileBukti."</td>";
			$trNya .= "<td width=\"20\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\" align=\"center\">".$no."</td>";
			$trNya .= "<td width=\"155\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\">".$row['request_name']."</td>";
			$trNya .= "<td width=\"100\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\" align=\"center\">".$row['barcode']."</td>";
			$trNya .= "<td width=\"250\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\">".$row['company_name']."</td>";
			$trNya .= "<td width=\"300\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\">".$row['divisi']."</td>";
			$trNya .= "<td width=\"100\" align=\"center\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\">".$CPublic->convTglNonDB($row['invoice_date'])."</td>";
			$trNya .= "<td width=\"100\" align=\"center\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\">".$CPublic->convTglNonDB($row['invoice_due_date'])."</td>";
			$trNya .= "<td width=\"150\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\">".$row['mailinvno']."</td>";
			$trNya .= "<td width=\"130\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\" align=\"right\">(".$row['currency'].") ".number_format($row['amount'],2)."</td>";
			$trNya .= "<td width=\"200\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\">".$row['remark']."</td>";
			$trNya .= "<td width=\"100\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\" align=\"center\">".$transNo."</td>";
			$trNya .= "<td width=\"100\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\" align=\"center\">".$settlementTransNo."</td>";
			$trNya .= "<td width=\"100\" align=\"center\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\">".$CPublic->convTglNonDB($row['entry_date'])."</td>";
			$trNya .= "<td width=\"100\" align=\"left\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\">".$userEntry."</td>";
			$trNya .= "<td width=\"100\" align=\"center\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\">".$CPublic->convTglNonDB($row['confirm_userDate'])."</td>";
			$trNya .= "<td width=\"100\" align=\"center\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\">".$CPublic->convTglNonDB($row['bukti_date'])."</td>";
			$trNya .= "<td width=\"100\" align=\"center\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\">".$CPublic->convTglNonDB($row['settlement_voucher_datepaid'])."</td>";
			$trNya .= "<td width=\"200\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\">".$remarkBukti."</td>";
			$trNya .= "<td width=\"200\" class=\"tabelBorderTopLeftNull\" style=\"padding:2px;font:11px sans-serif;color:#333;\">".$remarkReject."</td>";
		$trNya .= "</tr>";
		$no++;
	}
	echo $trNya;
?>

        </table>
    </div>
</body>

</HTML>