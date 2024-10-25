<!DOCTYPE HTML>
<?php
require_once("../../config.php");
require_once("../configSpj.php");
	
	$monthNow = date("m");
	$yearNow = date("Y");
	$whereNya = "AND datefrom >= '".$yearNow.$monthNow."01' AND datefrom <= '".$yearNow.$monthNow."31'";

	if($aksiGet == "search")
	{
		$sDate = str_replace("-","",$_GET['sDate']) ;
		$eDate = str_replace("-","",$_GET['eDate']) ;

		$tglSdate = substr($sDate,2,2);
		$blnSdate = substr($sDate,0,2);
		$thnSdate = substr($sDate,4,8);
		$sDateNya = $thnSdate.$blnSdate.$tglSdate;

		$tglEdate = substr($eDate,2,2);
		$blnEdate = substr($eDate,0,2);
		$thnEdate = substr($eDate,4,8);
		$eDateNya = $thnEdate.$blnEdate.$tglEdate;

		$whereNya = "AND datefrom >= '".$sDateNya."' AND datefrom <= '".$eDateNya."'";
	}

	$sql = " SELECT B.formid,B.ownername,B.spjno,B.datefrom,B.dateto,B.extend,B.destination,B.necessary,B.vehicle,A.reportid 
			 FROM report A LEFT JOIN form B ON A.formid = B.formid
			 WHERE A.status = 'Checked' AND A.deletests = '0' ".$whereNya;
	$query = $CKoneksiSpj->mysqlQuery($sql);
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../css/cssSpj.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>

<script language="javascript">
$(document).ready(function(){
	parent.doneWait();
});
</script>

<body onLoad="loadScroll('transList');" onUnload="saveScroll('transList');">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:2px;" >
	<thead style="margin-top:50px;background-color:#8A8A8A;color:#F9F9F9;font-family:sans-serif;font-weight:bold;font-size:14px;left:0px;top:0px;height:30px;">
		<tr style="height:30px;">
			<td align="center" style="width:3%;">No</td>
			<td align="center" style="width:12%;">No SPJ</td>
			<td align="center" style="width:10%;">Date</td>
			<td align="center" style="width:13%;">Name</td>
			<td align="center" style="width:10%;">Destination</td>
			<td align="center" style="width:15%;">Necessity</td>
			<td align="center" style="width:10%;">Divisi</td>
			<td align="center" style="width:15%;">Follower</td>
			<td align="center" style="width:12%;">Vehicle</td>			
		</tr>
	</thead>
	<tbody>
		<?php
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
						<td align=\"left\" class=\"tabelBorderTopNull\">&nbsp".$row['spjno']."</td>
						<td align=\"center\" class=\"tabelBorderTopNull\">".$startDate." / ".$endDate."</td>
						<td align=\"left\" class=\"tabelBorderTopNull\">&nbsp".$row['ownername']."</td>
						<td align=\"center\" class=\"tabelBorderTopNull\">".$row['destination']."</td>
						<td align=\"left\" class=\"tabelBorderTopNull\">&nbsp".$row['necessary']."</td>
						<td align=\"center\" class=\"tabelBorderTopNull\">".$nmDiv."</td>
						<td align=\"center\" class=\"tabelBorderTopNull\">".$CSpj->menuFollower($row['formid'], $db)."</td>
						<td align=\"left\" class=\"tabelBorderTopNull\">&nbsp".$row['vehicle']."</td>

					  </tr>";
				$no++;
			}
		?>
	</tbody>

</table>


</body>
</HTML>	