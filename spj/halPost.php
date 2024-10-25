<?php
require_once("../config.php");
require_once("configSpj.php");

/*if($halamanPost == "tampilFol")
{
	$userId = $_POST['userId'];
	$jml = $_POST['jml'];
	echo '<span class="labelFollower">'.$userId.'</span><span class="labelFollower"><img src="../picture/cross-button.png" class="btnClose"/></span>
		<span style="float:left;margin:1px 0 1px 0;" id="divFol'.$jml.'"></span>';
	echo $jml;
}*/

if($halamanPost == "jmlFol")
{
	$jml = $_POST['jml'];
	
	for($i=1;$i<=$jml;$i++)
	{
		$html = '<select id="foll'.$i.'" name="foll'.$i.'" class="elementMenu" style="width:102%;" title="Pendamping perjalanan dinas">
                 	<option value="0">-- PLEASE SELECT --</option>
					'.$CSpj->menuUser($db).'
				</select>';
		echo $html;
	}
}

if($halamanPost == "jmlCopy")
{
	$jml = $_POST['jml'];
	$html.= '<table cellpadding="0" cellspacing="5" width="100%" class="spjFormInput" border="0">';
	for($i=1;$i<=$jml;$i++)
	{
		$html.= '<tr valign="top">
                	<td width="16%" height="28px" align="left">&nbsp;</td>
                    <td width="84%" align="left">
                    	<input type="text" class="elementDefault" style="width:99%;height:15px;" id="copy'.$i.'" name="copy'.$i.'" value=""/>
                    </td>
                </tr>';
		//echo $html;
	}
	$html.= '</table>';
	
	echo $html;
}

if($halamanPost == "copy")
{
	$formId = $_POST['formId'];
	$jml = $_POST['jml'];
		
	$html.= '<table cellpadding="0" cellspacing="5" width="100%" class="spjFormInput" border="0">';
	for($i=1;$i<=$jml;$i++)
	{
		$j = $i - 1;
		$query = $CKoneksiSpj->mysqlQuery("SELECT copycontent FROM copy WHERE formid = ".$formId." ORDER BY copyid ASC LIMIT ".$j.",1;");
		$row = $CKoneksiSpj->mysqlFetch($query);
		$html.= '<tr valign="top">
                	<td width="16%" height="28px" align="left">&nbsp;</td>
                    <td width="84%" align="left">
                    	<input type="text" class="elementDefault" style="width:99%;height:15px;" id="copy'.$i.'" name="copy'.$i.'" value="'.$row['copycontent'].'"/>
                    </td>
                </tr>';
	}
	$html.= '</table>';
		
	echo $html;
}

if($halamanPost == "follower")
{
	$formId = $_POST['formId'];
	$jml = $_POST['jml'];
	$db = $_POST['db'];
	
		
	for($i=1;$i<=$jml;$i++)
	{
		$j = $i - 1;
		$sql = $CKoneksiSpj->mysqlQuery("SELECT followerid FROM follower WHERE formid = ".$formId." LIMIT ".$j.",1;");
		$rowSql = $CKoneksiSpj->mysqlFetch($sql);
		$folId = $rowSql['followerid'];
		$html.= '<select id="foll'.$i.'" name="foll'.$i.'" class="elementMenu" style="width:102%;" title="Pendamping perjalanan dinas">
                 	<option value="0">-- SELECT FOLLOWER --</option>';
					
		$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM ".$db.".login WHERE active = 'Y' AND deletests = 0 ORDER BY userfullnm ASC;");
		while($row = $CKoneksiSpj->mysqlFetch($query))
		{
			$sel = "";
			if($folId == $row['userid'])
			{
				$sel = "selected=\"selected\"";
			}
			$html.= 	'<option value="'.$row['userid'].'" '.$sel.'>'.$row['userfullnm'].'</option>';
		}
		$html.= '</select>';
	}
		
	echo $html;
}

if($halamanPost == "Tunj" || $halamanPost == "Pjp" || $halamanPost == "Trans" || $halamanPost == "Akom" || $halamanPost == "Consm" || $halamanPost == "Other")
{
	$jml = $_POST['jml'];
	$vars = $_POST['vars'];
	$j = $jml-1;
	
	for($i=1;$i<=$j;$i++)
	{
		$id = $i+1;
		
		$cur= $vars['cur'.$halamanPost.$id];
		$cost= $vars['cost'.$halamanPost.$id];
		$ket= $vars['ket'.$halamanPost.$id];
		
		$html.= $CSpj->fieldTunj($halamanPost, $id, $halamanPost.$id, $cur, $cost, $ket);
	}
	
	echo '<table cellspacing="2" cellpadding="0" width="100%">'.$html.'</table>';
	//echo $html;
}

if($halamanPost == "tembusan")
{
	$jmlCopyPost = $_POST['jmlCopy'];
	$vars = $_POST['vars'];
	
	for($i=1;$i<=$jmlCopyPost;$i++)
	{
		$btn = '<button type="button" class="spjBtnStandar" onClick="ajaxTembusan(\'kurang\', \''.$i.'\');" style="width:30px;height:26px;border-radius:3px;">
					<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
					<tr>
						<td align="left"><img src="../picture/cross.png" height="15px"/></td>
					</tr>
					</table>
				</button>';
		if($i == 1)
		{
			$btn = '';
		}
		$html.= '<tr>
					<td width="7%">
						'.$btn.'
					</td>
					<td width="93%"><input type="text" class="elementDefault" style="width:97%;height:15px;" id="copy'.$i.'" name="copy'.$i.'" value="'.$vars['copy'.$i].'"/></td>
				</tr>';
	}
	echo $html;
}

if($halamanPost == "countUpdate")
{
	$reportId = $_POST['reportId'];
	
	// grand total count & update
	$idrGrandTotal = $CSpj->grandTotal("idrtotal", $reportId);
	$usdGrandTotal = $CSpj->grandTotal("usdtotal", $reportId);
	$CKoneksiSpj->mysqlQuery("UPDATE report SET idrgrandtotal = ".$idrGrandTotal.", usdgrandtotal = ".$usdGrandTotal." WHERE reportid = ".$reportId." AND deletests = 0;");
	
	// uang kembali count & update
	$idrDp = $CSpj->detilReport($reportId, "idrdp");
	if($CSpj->detilReport($reportId, "idrdp") == "")
	{
		$idrDp = 0;
	}
	$idrKembali = $idrDp - $idrGrandTotal;
	$usdDp = $CSpj->detilReport($reportId, "usddp");
	if($CSpj->detilReport($reportId, "usddp") == "")
	{
		$usdDp = 0;
	}
	$usdKembali = $usdDp - $usdGrandTotal;
	$CKoneksiSpj->mysqlQuery("UPDATE report SET idrtotalkembali = ".$idrKembali.", usdtotalkembali = ".$usdKembali." WHERE reportid = ".$reportId." AND deletests = 0;");
}

if($halamanPost == "cekDuplctd")
{
	$reportId = $_POST['reportId'];
	
	echo $CSpj->duplctdReport($CSpj->detilReport($reportId, "formid"));
}

if($halamanPost == "cekSelfAprv")
{
	$query = $CKoneksiSpj->mysqlQuery("SELECT selfaprv FROM msuser WHERE userid = 00007;");
	$row = $CKoneksiSpj->mysqlFetch($query);
	
	echo $row['selfaprv'];
}

if($halamanPost == "cariTunjangan")
{
	$curency = $_POST['curency'];
	// $gol = $_POST['gol'];
	$idName = $_POST['idName'];
	$tunjangan = "0";

	$gol = $CEmployee->gol($CSpj->detilLoginSpj($idName, "empno", $db));

	if($curency != "" AND $gol != "")
	{
		$tunjangan = number_format((float)$CSpj->tunjangan($gol, $CSpj->detilCurrency($curency, "currencyname")),2, '.', ',');
	}
	
	echo '<input type="text" class="elementDefault" id="costTunj1" name="costTunj1" style="width:89%;height:16px;text-align:right;"  onFocus="setup(\'costTunj1\');" onKeyUp="setup(\'costTunj1\');" title="Jumlah tunjangan" value="'.$tunjangan.'" readonly="true"/>';
}

if($halamanPost == "cariTransport")
{
	$curency = $_POST['curency'];
	$idName = $_POST['idName'];
	$tunTrans = "0";

	$gol = $CEmployee->gol($CSpj->detilLoginSpj($idName, "empno", $db));

	if($curency != "" AND $gol != "")
	{
		if($curency == "01" OR $curency == "02")
		{
			$tunTrans = number_format((float)$CSpj->tunjangan($gol, "transport_".$CSpj->detilCurrency($curency, "currencyname")),2, '.', ',');
		}
		
	}
	
	echo '<input type="text" class="elementDefault" id="costTrans1" name="costTrans1" style="width:89%;height:16px;text-align:right;"  onFocus="setup(\'costTrans1\');" onKeyUp="setup(\'costTrans1\');" title="Jumlah biaya transportasi" value="'.$tunTrans.'"/>';
}

if($halamanPost == "cariConsumtion")
{
	$dataOut = array();
	$curency = $_POST['curency'];
	$idName = $_POST['idName'];
	$tunCons = "0";
	$dataOut['tunCons'] = "0";
	$dataOut['ketCons'] = "";

	$gol = $CEmployee->gol($CSpj->detilLoginSpj($idName, "empno", $db));

	if($curency != "" AND $gol != "")
	{
		$cekTun = $CSpj->tunjanganConsumtion($gol,$CSpj->detilCurrency($curency, "currencyname"));
		$tunCons = number_format((float)$CSpj->tunjanganConsumtion($gol,$CSpj->detilCurrency($curency, "currencyname")),2, '.', ',');

		$dataOut['tunCons'] = $tunCons;
		if($cekTun > 0)
		{
			$dataOut['ketCons'] = "Makan Pagi, Siang dan Malam";
		}
	}
	
	print json_encode($dataOut);
}

if($halamanPost == "spjNo")
{
	$ownerIdPost = $_POST['ownerId'];
	
	$html.= '<select id="spjNo" name="spjNo" class="elementMenu" style="width:102%;" title="Related SPJ">
				<option value="">-- SELECT SPJ NUMBER --</option>';

				// $query = $CKoneksiSpj->mysqlQuery("SELECT * FROM form WHERE status = 'Completed' AND ownerid = ".$ownerIdPost." AND deletests = 0 ORDER BY formnum DESC;");
				$query = $CKoneksiSpj->mysqlQuery("SELECT a.formid,a.spjno FROM form a LEFT JOIN report b ON a.formid = b.formid WHERE a.ownerid = '".$ownerIdPost."' AND a.STATUS =  'Completed' AND a.deletests = '0' AND b.formid is null ORDER BY a.formnum DESC");
				while($row = $CKoneksiSpj->mysqlFetch($query))
				{
					$html.= '<option value="'.$row['formid'].'">'.$row['spjno'].'</option>';
				}

	$html.= '</select>';
	
	echo $html;	
}

if($halamanPost == "getDataAllowance")
{
	$html = "";
	$field = "";
	$kursNya = "";
	$db = $_POST['db'];
	$userId = $_POST['userId'];
	$stData = $_POST['stData'];
	$ownerEmpno = $CSpj->detilLoginSpj($userId,"empno", $db);
	$gol = $CEmployee->gol($ownerEmpno);

	if($stData == "dalam")
	{
		$field = "idr as uangSaku,tiketpesawat_dalam as tiket,transport_idr as transport,akomodasi_hotel as akomodasi,konsumsipagi_idr as makanPagi,konsumsisiang_idr as makanSiang,konsumsimalam_idr as makanMalam, (idr+transport_idr+konsumsipagi_idr+konsumsisiang_idr+konsumsimalam_idr) as totalNya";
		$kursNya = "Rp. ";
	}else{
		$field = "usd as uangSaku,tiketpesawat_luar as tiket,transport_usd as transport,akomodasi_hotel as akomodasi,konsumsipagi_usd as makanPagi,konsumsisiang_usd as makanSiang,konsumsimalam_usd as makanMalam, (usd+transport_usd+konsumsipagi_usd+konsumsisiang_usd+konsumsimalam_usd) as totalNya";
		$kursNya = "$ ";
	}
	
	$sql = "SELECT b.".$field." FROM goltunjangan a LEFT JOIN tunjangan b ON a.tunjanganid = b.tunjanganid WHERE a.gol = '$gol'";
	$query = $CKoneksiSpj->mysqlQuery($sql);
	while($row = $CKoneksiSpj->mysqlFetch($query))
	{
		$html .= "
			<table border = \"1\">
					<tr style=\"background-color:#abb974;\">
						<td align=\"center\" style=\"width:70px;\">Tiket Pesawat</td>
						<td align=\"center\" style=\"width:80px;\">Transportasi</td>
						<td align=\"center\" style=\"width:60px;\">Akomodasi Hotel</td>
						<td align=\"center\" style=\"width:80px;\">Konsumsi Pagi</td>
						<td align=\"center\" style=\"width:80px;\">Konsumsi Siang</td>
						<td align=\"center\" style=\"width:80px;\">Konsumsi Malam</td>
						<td align=\"center\" style=\"width:80px;\">Uang Saku</td>
					</tr>
					<tr style=\"font-size:11px;\">
						<td align=\"center\">".$row['tiket']."</td>
						<td align=\"right\">".number_format($row['transport'],2,',','.')."</td>
						<td align=\"center\">".$row['akomodasi']."</td>
						<td align=\"right\">".number_format($row['makanPagi'],2,',','.')."</td>
						<td align=\"right\">".number_format($row['makanSiang'],2,',','.')."</td>
						<td align=\"right\">".number_format($row['makanMalam'],2,',','.')."</td>
						<td align=\"right\">".number_format($row['uangSaku'],2,',','.')."</td>
					</tr
					<tr>
						<td align=\"right\" colspan=\"7\">Total : ".$kursNya.number_format($row['totalNya'],2,',','.')."</td>
					</tr>
					</table>
				";
	}
	print json_encode($html);
}
if($halamanPost == "followByTable")
{
	$formId = $_POST['id'];
	$html = "";
	$no = 1;
	
	$sql = "SELECT * FROM follower WHERE  formid  = '".$formId."'";
	$query = $CKoneksiSpj->mysqlQuery($sql);
	while($row = $CKoneksiSpj->mysqlFetch($query))
	{
		$html .= "<tr>
						<td align=\"center\">".$no."</td>
						<td>&nbsp&nbsp ".$CSpj->detilLoginSpj($row['followerid'], "userfullnm", $db)."</td>
						<td align=\"center\">
							<button type=\"button\" id=\"btnGen\" class=\"spjBtnStandar\" onclick=\"removeFollowNya('".$row['formid']."','".$row['folid']."');\" style=\"margin:5px 0px 0px 0px; width:80px;height:23px;\" title=\"Remove Follower\">
								<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
								  <tr>
									<td align=\"center\">Remove</td>
								  </tr>
								</table>
							</button>
						</td>
				  </tr>";
		$no ++;
	}
	
	print json_encode($html);
}

if($halamanPost == "removeFollowerNya")
{
	$folid = $_POST['folid'];
	$stDel = "";
	try {
			$sql = "DELETE FROM follower WHERE  folid  = '".$folid."'";
			$query = $CKoneksiSpj->mysqlQuery($sql);
			$stDel = "sukses";
	} catch (Exception $ex) {
		$stDel = "gagal =>".$ex;
	}
	print json_encode($stDel);
}

if($halamanPost == "checkFollowerNya")
{
	$idForm = $_POST['idForm'];
	$idUsr = $_POST['idUsr'];
	$stCheck = "";
	
	$sql = "SELECT * FROM follower WHERE  formid  = '".$idForm."' AND followerid = '".$idUsr."'";
	
	$query = $CKoneksiSpj->mysqlQuery($sql);
	$valChk = $CKoneksiSpj->mysqlNRows($query);
	
	if($valChk > 0)
	{
		$stCheck = "ada";
	}else{
		$stCheck = "kosong";
	}
	print json_encode($stCheck);
}
if($halamanPost == "actionAddExtend")
{
	$formId = $_POST['formId'];
	$extend = $_POST['extend'];
	$stUpdate = "";

	try {
		$CKoneksiSpj->mysqlQuery("UPDATE form SET extend = '".$extend."' WHERE formid = '".$formId."'");
		$stUpdate = "Extend tanggal SPJ Berhasil..!!";
	} catch (Exception $ex) {
		$stUpdate = "gagal =>".$ex;
	}
	print json_encode($stUpdate);
}

if($halamanPost == "actionExportSummary")
{
	$sDate = $_POST['sDateSearch'];
	$eDate = $_POST['eDateSearch'];
	$lblTglPeriode = "";
	if ($sDate == "" && $eDate == "")
	{
		$monthNow = date("m");
		$yearNow = date("Y");
		$lblTglPeriode = date("M")." ".$yearNow;
		$whereNya = "AND datefrom >= '".$yearNow.$monthNow."01' AND datefrom <= '".$yearNow.$monthNow."31'";
	}else{
		$sd = str_replace("-","",$sDate) ;
		$ed = str_replace("-","",$eDate) ;

		$tglSdate = substr($sd,2,2);
		$blnSdate = substr($sd,0,2);
		$thnSdate = substr($sd,4,8);
		$sDateNya = $thnSdate.$blnSdate.$tglSdate;

		$tglEdate = substr($ed,2,2);
		$blnEdate = substr($ed,0,2);
		$thnEdate = substr($ed,4,8);
		$eDateNya = $thnEdate.$blnEdate.$tglEdate;

		$lblTglPeriode = $tglSdate."-".$blnSdate."-".$thnSdate." s/d ".$tglEdate."-".$blnEdate."-".$thnEdate;
		$whereNya = "AND datefrom >= '".$sDateNya."' AND datefrom <= '".$eDateNya."'";
	}
	ob_start();
	
	$sql = " SELECT B.formid,B.ownername,B.spjno,B.datefrom,B.dateto,B.extend,B.destination,B.necessary,B.vehicle,A.reportid 
			 FROM report A LEFT JOIN form B ON A.formid = B.formid
			 WHERE A.status = 'Checked' AND A.deletests = '0' ".$whereNya;
	$query = $CKoneksiSpj->mysqlQuery($sql);

	header("Content-Type: application/vnd.ms-excel");
	echo "<table width=\"100%\">";
		echo "<tr>
				<td colspan=\"5\" align=\"center\">
					<label style=\"font-size: 28pt;font-weight: bold;\">SUMMARY REPORT</label>
				</td>
			</tr>";
		echo "<tr>
				<td colspan=\"5\" align=\"center\">
					<label>".$lblTglPeriode."</label>
				</td>
			</tr>";
	echo "</table>";

	echo "<table border=\"1\">";
	echo "	<tr>
				<td align=\"center\" style=\"width:3%;\">No</td>
				<td align=\"center\" style=\"width:12%;\">No SPJ</td>
				<td align=\"center\" style=\"width:10%;\">Date</td>
				<td align=\"center\" style=\"width:13%;\">Name</td>
				<td align=\"center\" style=\"width:10%;\">Destination</td>
				<td align=\"center\" style=\"width:15%;\">Necessity</td>
				<td align=\"center\" style=\"width:10%;\">Divisi</td>
				<td align=\"center\" style=\"width:15%;\">Follower</td>
				<td align=\"center\" style=\"width:12%;\">Vehicle</td>			
			</tr>
		";
	$no = 1;
	while($row = $CKoneksiSpj->mysqlFetch($query))
	{
		$rowColor = $CPublic->rowColorCustom($no, "#FFFFFF", "#F0F1FF");
		$kdDiv = $CEmployee->detilTblEmpGen($CSpj->detilForm($row['formid'], "ownerempno"), "kddiv");
		$nmDiv = $CEmployee->detilDiv($kdDiv, "nmdiv");

		$formDate = $CSpj->detilForm($row['formid'], "datefrom");
        $startDate = substr($formDate,6,2)."-".substr($formDate,4,2)."-".substr($formDate,0,4);

		$cekExtend = $CSpj->detilForm($row['formid'], "extend");
		$toDate = $CSpj->detilForm($row['formid'], "dateto");
		if($cekExtend != "0")
		{
			$toDate = $CSpj->dateAfterExtend($toDate, $cekExtend);
		}
        $endDate = substr($toDate,6,2)."-".substr($toDate,4,2)."-".substr($toDate,0,4);
        echo "<tr valign=\"center\" align=\"left\" bgcolor=\"".$rowColor."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='".$rowColor."'\" id=\"".$no."\" style=\"cursor:pointer;padding-bottom:1px;font-size:13px;font-family:arial nerrow;\" >

				<td align=\"center\" class=\"tabelBorderTopNull\">".$no."</td>
				<td align=\"left\" class=\"tabelBorderTopNull\">".$row['spjno']."</td>
				<td align=\"center\" class=\"tabelBorderTopNull\">".$startDate." / ".$endDate."</td>
				<td align=\"left\" class=\"tabelBorderTopNull\">".$row['ownername']."</td>
				<td align=\"center\" class=\"tabelBorderTopNull\">".$row['destination']."</td>
				<td align=\"left\" class=\"tabelBorderTopNull\">".$row['necessary']."</td>
				<td align=\"center\" class=\"tabelBorderTopNull\">".$nmDiv."</td>
				<td align=\"center\" class=\"tabelBorderTopNull\">".$CSpj->menuFollower($row['formid'], $db)."</td>
				<td align=\"left\" class=\"tabelBorderTopNull\">".$row['vehicle']."</td>

			  </tr>";
		$no++;
	}
	echo "</table>";

	header("Content-disposition: attachment; filename=summaryreport.xls");
	ob_end_flush();
}







?>