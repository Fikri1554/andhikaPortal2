<!DOCTYPE HTML>
<?php
require_once("../../config.php");
require_once("../configSpj.php");

$reportReviseIdGet = $_GET['reportReviseId'];

$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM reportrevise WHERE reportreviseid = ".$reportReviseIdGet." AND deletests=0;");
$row = $CKoneksiSpj->mysqlFetch($query);
//echo "a ".$halamanGet;
$formId = $row['formid'];
$dateForm = $CSpj->detilForm($formId, "datefrom");
$thn =  substr($dateForm,0,4);
$bln =  substr($dateForm,4,2);
$tgl =  substr($dateForm,6,2);
$formDate =  $tgl." ".ucwords(strtolower($CPublic->detilBulanNamaAngka($bln, "ind")))." ".$thn;

$dateTo = $CSpj->detilForm($formId, "dateto");
$thnTo =  substr($dateTo,0,4);
$blnTo =  substr($dateTo,4,2);
$tglTo =  substr($dateTo,6,2);
$toDate =  $tglTo." ".ucwords(strtolower($CPublic->detilBulanNamaAngka($blnTo, "ind")))." ".$thnTo;

$revDate = $CSpj->detilReportRev($reportReviseIdGet, "tglrevise");
$thnRev =  substr($revDate,0,4);
$blnRev =  substr($revDate,4,2);
$tglRev =  substr($revDate,6,2);
$dispRevDate =  $tglRev." ".ucwords(strtolower($CPublic->detilBulanNamaAngka($blnRev, "ind")))." ".$thnRev;

$note = $CSpj->detilReportRev($reportReviseIdGet, "note");
$noteEcho = "-";
if($note != "")
{
	$noteEcho = $note;
}

$kdDiv = $CEmployee->detilTblEmpGen($CSpj->detilForm($formId, "ownerempno"), "kddiv");
$nmDiv = $CEmployee->detilDiv($kdDiv, "nmdiv");

$kdDept = $CEmployee->detilTblEmpGen($CSpj->detilForm($formId, "ownerempno"), "kddept");
$nmDept = $CEmployee->detilDept($kdDept, "nmdept");
?>

<link href="../css/cssSpj.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>

<script>
$(document).ready(function(){
	parent.doneWait();
});

//Automatically setting height of textarea to height of its contents
/*$(function() {
	 $('textarea').height($('textarea').prop('scrollHeight'));
});*/
$(function() {
	$('textarea').each(function() {
		$(this).height($(this).prop('scrollHeight'));
	});
});
</script>
<body onLoad="loadScroll('haldetilReport');" onUnload="saveScroll('halDetilReport')">
<input type="hidden" id="idTrSeb" name="idTrSeb">
<!--<input type="text" id="detilId" name="detilId">-->
<table cellpadding="0" cellspacing="0" width="800">
<!-- header -->
<tr> 
	<td width="82%">
    <table cellpadding="0" cellspacing="0" width="100%">
    <tr><td height="2px"></td></tr>
    <tr>
    	<td class="fontMyFolderList" style="font-size:14px;">&nbsp;</td>
        <td align="right">
        <?php // Menentukan Perusahaan
			$kdComp = $CSpj->detilForm($formId, "kdcmp");
			echo "<img src=\"../company/".$CSpj->detilCmp($kdComp, "logo")."\" height=\"22px\"/>";
		?>&nbsp;    
        </td>
    </tr>
    </table>
    </td>
    <td width="18%">&nbsp;</td>
</tr>
<tr><td height="5"></td></tr>
<tr> 
	<td>
    <table class="fontMyFolderList" cellpadding="0" cellspacing="0" width="100%">
    	<tr height="18px">
        	<td width="1%"></td>
            <td width="14%" align="left">Revise Date</td>
            <td width="85%" align="left" style="color:#000080;"><?php echo $dispRevDate;?></td>
        </tr>
    	<tr height="18px">
        	<td width="1%"></td>
            <td width="14%" align="left">Revise Request</td>
            <td width="85%" align="left" style="color:#000080;"><?php echo $CSpj->detilLoginSpjByEmpno($row['reasonempno'], "userfullnm", $db);?></td>
        </tr>
        <tr height="18px">
        	<td></td>
            <td align="left" valign="top">Revise Reason</td>
            <td align="left">
            	<textarea style="color:#000080;font-family:Tahoma;font-size:12px;width:98%;border:none;overflow:hidden;" readonly><?php echo $row['reason'];?></textarea>
            </td>
        </tr>
    </table>
    </td>
    <td>&nbsp;</td>
</tr>
<tr><td height="1" class="spjTdMyFolder" style="border-color:#B22222;" colspan="2"></td></tr>
<tr><td height="1" class="spjTdMyFolder" style="border-color:#B22222;" colspan="2"></td></tr>
<tr><td height="3" colspan="2"></td></tr>
<!-- tabel detil -->
<tr>
	<td align="center">
    <table class="fontMyFolderList" cellpadding="0" cellspacing="3" width="98%">
    <tr height="17px" valign="top">
    	<td width="14%" align="left">
        	Nama Karyawan
        </td>
        <td width="48%" align="left" style="color:#000080;">
        	<?php echo ucwords(strtolower($CSpj->detilForm($formId, "ownername")));?>
        </td>
        <td width="17%" align="left">
        	Tujuan
        </td>
        <td width="21%" align="left" style="color:#000080;">
			<?php echo $CSpj->detilForm($formId, "destination");?>
        </td>
    </tr>
    
    <tr height="17px" valign="top">
    	<td align="left">
        	Jabatan / Gol.</td>
        <td align="left" style="color:#000080;">
        	<?php echo ucwords(strtolower($CEmployee->detilJabatan($CEmployee->detilTblEmpGen($CSpj->detilForm($formId, "ownerempno"), "kdjabatan"), "nmjabatan")))." / ".ucwords(strtolower($CEmployee->detilPangkat($CEmployee->detilTblEmpGen($CSpj->detilForm($formId, "ownerempno"), "kdpangkat"), "nmpangkat")));?>
        </td>
        <td align="left">
        	Tanggal Berangkat
        </td>
        <td align="left" style="color:#000080;">
        	<?php echo $formDate;?>
        </td>
    </tr>
    
    <tr height="17px" valign="top">
    	<td align="left">Div. / Dept.</td>
        <td align="left" style="color:#000080;">
        	<?php echo $nmDiv." / ".$nmDept;?>
        </td>
        <td align="left">
        	Tanggal Kembali
        </td>
        <td align="left" style="color:#000080;">
        	<?php echo $toDate;?>
        </td>
    </tr>
    
    <tr height="17px" valign="top">
    	<td align="left">Nomor SPJ</td>
        <td align="left" style="color:#000080;">
        	<?php echo $CSpj->detilForm($formId, "spjno");?>
        </td>
        <td align="left">
        	Kendaraan
        </td align="left">
        <td align="left" style="color:#000080;">
        	<?php echo $CSpj->detilForm($formId, "vehicle");?>
        </td>
    </tr>

    <tr><td colspan="4" height="2"></td></tr>
    </table>
    </td>
    <td>&nbsp;</td>
</tr>
<tr><td height="1" class="spjTdMyFolder" colspan="2"></td></tr>
<tr><td height="4" colspan="2"></td></tr>
<!-- Data Detail -->

<tr>
<td align="center" colspan="2">
	<table class="fontMyFolderList" style="font-size:11px;" cellpadding="0" cellspacing="0" width="99%">
    <?php
		$style = 'style="border-color:#999;"';
		$style2 = ' style="border-color:#999;background-color:#008080;color:white;"';
		
		$otherCur1 = $CSpj->detilReportRev($reportReviseIdGet, "othercur1");
		$otherCur2 = $CSpj->detilReportRev($reportReviseIdGet, "othercur2");	
		$other1 = $CSpj->detilCurrency($otherCur1, "currencyname");
		$other2 = $CSpj->detilCurrency($otherCur2, "currencyname");
		
		$colOther1 = 0;
		$colOther2 = 0;
		
		if($otherCur1 != 0 || $otherCur1 != '00')
		{
			$colOther1 = 1;
			$tdCur1 = '<td width="7%" align="center" class="borderTopLeftNull" '.$style2.'>'.$other1.'</td>';
		}
		if($otherCur2 != 0 || $otherCur2 != '00')
		{
			$colOther2 = 1;
			$tdCur2 = '<td width="7%" align="center" class="borderTopLeftNull" '.$style2.'>'.$other2.'</td>';
		}
		$colspanTotal = 2 + $colOther1 + $colOther2 ;
	?>
    <tr style="background-color:#EFEFEF;font-size:11px;" height="20px">
    	<td width="7%" rowspan="2" align="center" class="borderAll" <?php echo $style; ?>>Tanggal</td>
    	<td width="7%" rowspan="2" align="center" class="borderLeftNull" <?php echo $style; ?>>Nama</td>
        <td width="11%" rowspan="2" align="center" class="borderLeftNull" <?php echo $style; ?>>Lokasi</td>
        <td colspan="5" align="center" class="borderLeftNull" <?php echo $style; ?>>Deskripsi Pengeluaran</td>
        <td colspan="<?php echo $colspanTotal; ?>" align="center" class="borderLeftNull" <?php echo $style2; ?>>Total</td>
    </tr>
    <tr style="background-color:#EFEFEF;">
    	<td width="10%" align="center" class="borderTopLeftNull" <?php echo $style; ?>>Tunjangan<br/>Harian</td>
        <td width="11%" align="center" class="borderTopLeftNull" <?php echo $style; ?>>Transportasi</td>
        <td width="11%" align="center" class="borderTopLeftNull" <?php echo $style; ?>>Akomodasi</td>
        <td width="11%" align="center" class="borderTopLeftNull" <?php echo $style; ?>>Konsumsi</td>
        <td width="11%" align="center" class="borderTopLeftNull" <?php echo $style; ?>>Lainnya</td>
        <td width="8%" align="center" class="borderTopLeftNull" <?php echo $style2; ?>>IDR</td>
        <td width="6%" align="center" class="borderTopLeftNull" <?php echo $style2; ?>>USD</td>
        <?php echo $tdCur1.$tdCur2 ; ?>
    </tr>
<!-- isi detail -->
	<!--<tr height="20" style="font-size:9px;">
    	<td align="center" class="borderTopNull" <?php echo $style; ?>>22/05/05</td>
        <td align="center" class="borderTopLeftNull" <?php echo $style; ?>>Balikpapan</td>
        <td align="center" class="borderTopLeftNull" <?php echo $style; ?>>Rp. 250,000</td>
        <td align="center" class="borderTopLeftNull" <?php echo $style; ?>>Rp. 250,000</td>
        <td align="center" class="borderTopLeftNull" <?php echo $style; ?>>Voucher Taxi<br/>Berangkat</td>
        <td align="center" class="borderTopLeftNull" <?php echo $style; ?>>Rp. 100,000<br/>Sewa Mobil</td>
        <td align="center" class="borderTopLeftNull" <?php echo $style; ?>>Rp. 200,000<br/>kopi</td>
        <td align="center" class="borderTopLeftNull" <?php echo $style; ?>>Rp. 300,000<br/>Parkir</td>
        <td align="center" class="borderTopLeftNull" <?php echo $style; ?>>1,100,000</td>
        <td align="center" class="borderTopLeftNull" <?php echo $style; ?>>-</td>
    </tr>-->
<?php
	$i = 1;
	$sql = $CKoneksiSpj->mysqlQuery("SELECT * FROM reportdetilrevise WHERE reportreviseid = ".$reportReviseIdGet." AND deletests = 0 ORDER BY tgldetil ASC, urutan ASC;");
	while($rows = $CKoneksiSpj->mysqlFetch($sql))
	{
		$date = $rows['tgldetil'];
		$tglRows = substr($date,6,2);
		$blnRows = substr($date,4,2);
		$thnRows = substr($date,2,2);
		$rowsDate = $tglRows."/".$blnRows."/".$thnRows;
		$idPengikut = $CSpj->detilReportDetil($rows['detilid'],"pengikut");
		$usrFollow = $CSpj->detilLoginSpj($idPengikut,"userfullnm", $db);
		
		//tunjangan
		$tunj = "";
		$curTunj = "";
		$costTunj = "";
		$ketTunj = "";
		if($rows['curtunj'] != "00")
		{
			$curTunj = $CSpj->detilCurrency($rows['curtunj'], "currency")." ";
		}
		if($rows['costtunj'] != "0.00")
		{
			$costTunj = number_format((float)$rows['costtunj'],2, '.', ',');
			if($rows['curtunj'] == "01")
			{
				$costTunj = number_format($rows['costtunj']);
			}
		}
		if($rows['kettunj'] != "")
		{
			$br = "";
			if($rows['costtunj'] != "")
			{
				$br = "<br/>";
			}
			$ketTunj = $br.$rows['kettunj'];
		}
		$tunj = $curTunj.$costTunj.$ketTunj;
		
		//pjp2u
		/*$pjp = "";
		$curPjp = "";
		$costPjp = "";
		$ketPjp = "";
		if($rows['curpjp'] != "")
		{
			if($rows['curpjp'] == "idr"){	$curPjp = "Rp ";	}
			if($rows['curpjp'] == "usd"){	$curPjp = "$ ";		}
		}
		if($rows['costpjp'] != "")
		{
			$costPjp = number_format($rows['costpjp']);
		}
		if($rows['ketpjp'] != "")
		{
			$br = "";
			if($rows['costpjp'] != "")
			{
				$br = "<br/>";
			}
			$ketPjp = $br.$rows['ketpjp'];
		}
		$pjp = $curPjp.$costPjp.$ketPjp;*/
		
		//transportasi
		$trans = "";
		$curTrans = "";
		$costTrans = "";
		$ketTrans = "";
		if($rows['curtrans'] != "00")
		{
			$curTrans = $CSpj->detilCurrency($rows['curtrans'], "currency")." ";
		}
		if($rows['costtrans'] != "0.00")
		{
			$costTrans = number_format((float)$rows['costtrans'],2, '.', ',');
			if($rows['curtrans'] == "01")
			{
				$costTrans = number_format($rows['costtrans']);
			}
		}
		if($rows['kettrans'] != "")
		{
			$br = "";
			if($rows['costtrans'] != "")
			{
				$br = "<br/>";
			}
			$ketTrans = $br.$rows['kettrans'];
		}
		$trans = $curTrans.$costTrans.$ketTrans;
		
		//akomodasi
		$akomd = "";
		$curAkomd = "";
		$costAkomd = "";
		$ketAkomd = "";
		if($rows['curakomd'] != "00")
		{
			$curAkomd = $CSpj->detilCurrency($rows['curakomd'], "currency")." ";
		}
		if($rows['costakomd'] != 0.00)
		{
			$costAkomd = number_format((float)$rows['costakomd'],2, '.', ',');
			if($rows['curakomd'] == "01")
			{
				$costAkomd = number_format($rows['costakomd']);
			}
		}
		if($rows['ketakomd'] != "")
		{
			$br = "";
			if($rows['costakomd'] != "")
			{
				$br = "<br/>";
			}
			$ketAkomd = $br.$rows['ketakomd'];
		}
		$akomd = $curAkomd.$costAkomd.$ketAkomd;
		
		//konsumsi
		$konsm = "";
		$curKonsm = "";
		$costKonsm = "";
		$ketKonsm = "";
		if($rows['curkonsm'] != "00")
		{
			$curKonsm = $CSpj->detilCurrency($rows['curkonsm'], "currency")." ";
		}
		if($rows['costkonsm'] != 0.00)
		{
			$costKonsm = number_format((float)$rows['costkonsm'],2, '.', ',');
			if($rows['curkonsm'] == "01")
			{
				$costKonsm = number_format($rows['costkonsm']);
			}
		}
		if($rows['ketkonsm'] != "")
		{
			$br = "";
			if($rows['costkonsm'] != "")
			{
				$br = "<br/>";
			}
			$ketKonsm = $br.$rows['ketkonsm'];
		}
		$konsm = $curKonsm.$costKonsm.$ketKonsm;
		
		//lainnya
		$lain = "";
		$curLain = "";
		$costLain = "";
		$ketLain = "";
		if($rows['curlain']!= "00")
		{
			$curLain = $CSpj->detilCurrency($rows['curlain'], "currency")." ";
		}
		if($rows['costlain'] != 0.00)
		{
			$costLain = number_format((float)$rows['costlain'],2, '.', ',');
			if($rows['curlain'] == "01")
			{
				$costLain = number_format($rows['costlain']);
			}
		}
		if($rows['ketlain'] != "")
		{
			$br = "";
			if($rows['costlain'] != "")
			{
				$br = "<br/>";
			}
			$ketLain = $br.$rows['ketlain'];
		}
		$lain = $curLain.$costLain.$ketLain;
		
		$idrTotal = "";
		if($rows['idrtotal'] != 0.00 )
		{
			$idrTotal = number_format($rows['idrtotal']);
		}
		$usdTotal = "";
		if($rows['usdtotal'] != 0.00 )
		{
			$usdTotal = number_format((float)$rows['usdtotal'],2, '.', ',');
		}
		$cur1Total = "";
		if($rows['othercur1total'] != 0.00 )
		{
			$cur1Total = number_format((float)$rows['othercur1total'],2, '.', ',');
		}
		$cur2Total = "";
		if($rows['othercur2total'] != "" && $rows['othercur2total'] != 0 )
		{
			$cur2Total = number_format((float)$rows['othercur2total'],2, '.', ',');
		}
		
		if($otherCur1 != 0)
		{
			$tdCur1Db = '<td align="center" class="borderTopLeftNull wrapword" '.$style.'>'.$cur1Total.'</td>';
		}
		if($otherCur2 != 0)
		{
			$tdCur2Db = '<td align="center" class="borderTopLeftNull wrapword" '.$style.'>'.$cur2Total.'</td>';
		}
?>
    <tr height="20" style="font-size:11px;">
    	<td align="center" class="borderTopNull wrapword" <?php echo $style; ?>><?php echo $rowsDate;?></td>
    	<td align="center" class="borderTopNull wrapword" <?php echo $style; ?>><?php echo $usrFollow;?></td>
        <td align="center" class="borderTopLeftNull wrapword" <?php echo $style; ?>><?php echo $rows['lokasi'];?></td>
        <td align="center" class="borderTopLeftNull wrapword" <?php echo $style; ?>><?php echo $tunj;?></td>
        <td align="center" class="borderTopLeftNull wrapword" <?php echo $style; ?>><?php echo $trans;?></td>
        <td align="center" class="borderTopLeftNull wrapword" <?php echo $style; ?>><?php echo $akomd;?></td>
        <td align="center" class="borderTopLeftNull wrapword" <?php echo $style; ?>><?php echo $konsm;?></td>
        <td align="center" class="borderTopLeftNull wrapword" <?php echo $style; ?>><?php echo $lain;?></td>
        <td align="center" class="borderTopLeftNull wrapword" <?php echo $style; ?>><?php echo $idrTotal;?></td>
        <td align="center" class="borderTopLeftNull wrapword" <?php echo $style; ?>><?php echo $usdTotal;?></td>
        <?php echo $tdCur1Db.$tdCur2Db ; ?>
    </tr>
<?php	
	$i++;}
	
		$echoIdrTotal = "";
		if($CSpj->detilReportRev($reportReviseIdGet, "idrgrandtotal") != "" && $CSpj->detilReportRev($reportReviseIdGet, "idrgrandtotal") != 0 )
		{
			$echoIdrTotal = number_format($CSpj->detilReportRev($reportReviseIdGet, "idrgrandtotal"));
		}
		$echoUsdTotal = "";
		if($CSpj->detilReportRev($reportReviseIdGet, "usdgrandtotal") != "" && $CSpj->detilReportRev($reportReviseIdGet, "usdgrandtotal") != 0)
		{
			$echoUsdTotal = number_format((float)$CSpj->detilReport($reportReviseIdGet, "usdgrandtotal"),2, '.', ',');
		}
		
		$echoIdrDp = "";
		if($CSpj->detilReportRev($reportReviseIdGet, "idrdp") != "")
		{
			$echoIdrDp = number_format($CSpj->detilReportRev($reportReviseIdGet, "idrdp"));
		}
		$echoUsdDp = "";
		if($CSpj->detilReportRev($reportReviseIdGet, "usddp") != "" )
		{
			$echoUsdDp = number_format((float)$CSpj->detilReportRev($reportReviseIdGet, "usddp"),2, '.', ',');
		}
		
		$echoIdrKembali = "";
		if($CSpj->detilReportRev($reportReviseIdGet, "idrtotalkembali") != "")
		{
			$echoIdrKembali = number_format($CSpj->detilReportRev($reportReviseIdGet, "idrtotalkembali"));
		}
		$echoUsdKembali = "";
		if($CSpj->detilReportRev($reportReviseIdGet, "usdtotalkembali") != "" )
		{
			$echoUsdKembali = number_format((float)$CSpj->detilReportRev($reportReviseIdGet, "usdtotalkembali"),2, '.', ',');
		}
		$echoOtherCur1Total = "";
		if($CSpj->detilReportRev($reportReviseIdGet, "othercur1grandtotal") != "" && $CSpj->detilReportRev($reportReviseIdGet, "othercur1grandtotal") != 0)
		{
			$echoOtherCur1Total = number_format((float)$CSpj->detilReportRev($reportReviseIdGet, "othercur1grandtotal"),2, '.', ',');
		}
		$echoOtherCur2Total = "";
		if($CSpj->detilReportRev($reportReviseIdGet, "othercur2grandtotal") != "" && $CSpj->detilReportRev($reportReviseIdGet, "othercur2grandtotal") != 0)
		{
			$echoOtherCur2Total = number_format((float)$CSpj->detilReportRev($reportReviseIdGet, "othercur2grandtotal"),2, '.', ',');
		}
		
		if($otherCur1 != 0)
		{
			$tdCur1Total = '<td align="center" class="borderTopLeftNull" '.$style.'><b>'.$echoOtherCur1Total.'</b></td>';
			$tdCur1TotalAkhir = '<td align="center" '.$style.'><b>'.$echoOtherCur1Total.'</b></td>';
		}
		if($otherCur2 != 0)
		{
			$tdCur2Total = '<td align="center" class="borderTopLeftNull" '.$style.'><b>'.$echoOtherCur2Total.'</b></td>';
			$tdCur2TotalAkhir = '<td align="center" '.$style.'><b>'.$echoOtherCur2Total.'</b></td>';
		}
	
	$jmlRows = $CKoneksiSpj->mysqlNRows($sql);
	if($jmlRows == 0)
	{
		echo "<script>parent.btnAksi('submitOff');</script>";
?>
	<tr height="28" style="font-size:9px;">
    	<td colspan="2" align="right" class="borderTopRightNull" style="border-color:#999;color:#F00;font-size:12px;">
        	<img src="../../picture/exclamation-red.png" height="15"/>
        </td>
        <td colspan="7" align="left" class="borderTopLeftNull" style="border-color:#999;color:#F00;font-size:12px;">
       		&nbsp;EMPTY DETAIL REPORT ! Click 'New' button above to create new report detail.
        </td>
    </tr>
	<tr height="22" style="font-size:11px;">
    	<td colspan="4"></td>
    	<td align="left" colspan="3">&nbsp;Uang Muka Biaya Perjalanan Dinas</td>
        <td align="center"><?php echo $echoIdrDp;?></td>
        <td align="center"><?php echo $echoUsdDp;?></td>
    </tr>
<?php	
    }
	if($jmlRows != 0)
	{
		$idrDp = $CSpj->detilReportRev($reportReviseIdGet, "idrDp");
?>
	<tr height="24" style="font-size:11px;">
    	<td align="right" class="borderTopRightNull" <?php echo $style; ?> colspan="7"></td>
    	<td align="right" class="borderTopLeftNull" <?php echo $style; ?>>
        	<b>TOTAL&nbsp;</b>
        </td>
        <td align="center" class="borderTopLeftNull" <?php echo $style; ?>><b><?php echo $echoIdrTotal;?></b></td>
        <td align="center" class="borderTopLeftNull" <?php echo $style; ?>><b><?php echo $echoUsdTotal;?></b></td>
        <?php echo $tdCur1Total.$tdCur2Total ; ?>
    </tr>
    <tr height="22" style="font-size:11px;">
    	<td colspan="5"></td>
    	<td align="left" colspan="3">&nbsp;Uang Muka Biaya Perjalanan Dinas</td>
        <td align="center"><?php echo $echoIdrDp;?></td>
        <td align="center"><?php echo $echoUsdDp;?></td>
    </tr>
    <tr height="22" style="font-size:11px;">
    	<td colspan="5"></td>
    	<td align="left" colspan="3">&nbsp;Dikurangi : Total Pengeluaran Perjalanan Dinas</td>
        <td align="center" class="borderBottomJust" <?php echo $style; ?>><?php echo $echoIdrTotal;?></td>
        <td align="center" class="borderBottomJust" <?php echo $style; ?>><?php echo $echoUsdTotal;?></td>
    </tr>
    <tr height="28" style="font-size:11px;">
    	<td colspan="5"></td>
    	<td align="left" colspan="3">&nbsp;<b>Total Akhir</b></td>
        <td align="center"><b><?php echo $echoIdrKembali;?></b></td>
        <td align="center"><b><?php echo $echoUsdKembali;?></b></td>
        <?php echo $tdCur1TotalAkhir.$tdCur2TotalAkhir ; ?>
    </tr>
<?php
	}
?>
    </table>
</td>
</tr>
<tr><td height="15"></td></tr>
<!-- Data Approval -->
<tr>
<td align="center">
    <table class="fontMyFolderList" style="font-size:13px;" cellpadding="0" cellspacing="3" width="98%">
<?php
	if($row['status'] != "Draft")
	{
?>
    
    <tr align="left">
        <td width="14%">
            Diketahui Oleh
        </td>
        <td width="86%" style="color:#000080;">    
        <?php
			$know = "[Kepala Divisi / Atasan]";
			if($row['ackempno'] != 00000)//jika Sudah Acknowledge
			{
				$know = $CSpj->detilLoginSpjByEmpno($row['ackempno'], "userfullnm", $db);
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
			if($row['ackempno'] != 00000)
			{
				$statKnow = "<i>ACKNOWLEDGED</i>";
			}
			
			echo $statKnow;
		?>
        </td>
 	</tr>
    <tr><td height="5" style="border-bottom:solid 1px #CCC;"></td><td></td></tr>
 
	<tr align="left">
        <td>
            Diperiksa Oleh
        </td>
        <td style="color:#000080;">
        <?php

			$periksa = "[HR&GA Dept.]" ;
			if($row['periksaempno'] != 00000)//jika Sudah Acknowledge
			{
				$periksa = $CSpj->detilLoginSpjByEmpno($row['periksaempno'], "userfullnm", $db);
			}
			echo $periksa;
		?>
        	
        </td>
 	</tr>
    <tr align="left">
        <td>
            Status
        </td>
        <td style="color:#000080;">
        <?php
			$statPeriksa = "<i>Waiting . . .</i>";
			if($row['periksaempno'] != 00000)
			{
				$statPeriksa = "<i>CHECKED</i>";
			}
			
			echo $statPeriksa;
		?>
        </td>
 	</tr>
    <tr><td height="5" style="border-bottom:solid 1px #CCC;"></td><td></td></tr>
    <tr><td height="2" colspan="2"></td></tr>
    
    <!--<tr align="left">
        <td>
            Diproses Oleh
        </td>
        <td style="color:#000080;">
        <?php
			$proses = "[Kepala Divisi Finance]" ;
			if($row['prosesempno'] != 00000)//jika Sudah Acknowledge
			{
				$proses = $CSpj->detilLoginSpjByEmpno($row['prosesempno'], "userfullnm", $db);
			}

			echo $proses;
		?>
        	
        </td>
 	</tr>
    <tr align="left">
        <td>
            Status
        </td>
        <td style="color:#000080;">
        <?php
			$statProses = "<i>Waiting . . .</i>";
			if($row['prosesempno'] != 00000)
			{
				$statProses = "<i>PROCESSED</i>";
			}
			
			echo $statProses;
		?>
        </td>
 	</tr>
    
    <tr><td height="5" style="border-bottom:solid 1px #CCC;"></td><td></td></tr>
    <tr><td height="3" colspan="2"></td></tr>-->
<?php } ?>    
    <tr align="left">
        <td valign="top" width="14%">
            Catatan
        </td>
        <td style="color:#000080;"><textarea name="textarea" readonly style="color:#000080;font-family:Tahoma;font-size:12px;width:98%;border:none;overflow:hidden;"><?php echo $noteEcho?></textarea>
        	
        </td>
 	</tr>
    
  </table>
</td>
<td>&nbsp;</td>
</tr>
</table>
</body>
<script language="javascript">
<?php
if($aksiGet == "delDetil")
{
	echo "parent.report('Detil Report succesfully delete');";
}
?>
</script>
</HTML>