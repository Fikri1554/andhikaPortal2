<?php
require_once("../config.php");

if($halamanPost == "updateTime")
{
	$idActPost = $_POST['idActivity'];
	$ownerId = $_POST['ownerId'];
	$cekPost = $_POST['cek'];
	$fromPost = $_POST['from'];
	$toPost = $_POST['to'];
	
	$yN = "N";
	if($cekPost == "true")
	{
		$yN = "Y";
	}
	
	
	if($idActPost != "")
	{
		$owner = $CDailyAct->detilAct($idActPost, "ownername");
		$COtherReport->lockActivity($idActPost, $yN, $CHistory, $userIdLogin, $owner);
	}
	
	$ubahAktif = "<script>document.getElementById('ubahAktif').click();</script>";
	$ubahTdkAktif = "<script>document.getElementById('ubahTdkAktif').click();</script>";
	if($COtherReport->belumLock($ownerId, $fromPost, $toPost) > 0)
	{
		$button = $ubahAktif;
	}
	if($COtherReport->belumLock($ownerId, $fromPost, $toPost) < 1)
	{
		$button = $ubahTdkAktif;
	}
	
	echo $COtherReport->pheonwj($ownerId, $fromPost, $toPost).$button;
}

if($halamanPost == "userTime")
{
	$i = $_POST['i'];
	$userIdPost = $_POST['userId'];
	$ownerName = $CLogin->detilLogin($userIdPost, "userfullnm");
	$fromDate = $_POST['from'];
	$toDate = $_POST['to'];
	$userIdLogin = $_POST['userIdLogin'];
	//echo $userIdPost." ".$fromPost." ".$toPost;
	$html = "";
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET lockedit = 'Y' WHERE ownerid = ".$userIdPost." AND bosapprove = 'N' AND project = 'pheonwj'AND deletests = 0
AND DATE(CONCAT(tahun,'/',bulan,'/', tanggal)) BETWEEN '".$fromDate."' AND '".$toDate."'");
	
	$CHistory->updateLog2($userIdLogin, "Lock Daily Activity for PHE ONWJ Project(ownername = <b>".$ownerName."</b>, from = <b>".$fromDate."</b>, until = <b>".$toDate."</b>)");

	sleep(1);
	
	$html.= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">";
	
	$j = 1;
	$queryAct = $CKoneksi->mysqlQuery("SELECT * FROM tblactivity 
					WHERE ownerid = ".$userIdPost." AND 
					DATE(CONCAT(tahun,'/',bulan,'/', tanggal)) BETWEEN '".$fromDate."' AND '".$toDate."' 
					AND project='pheonwj' AND deletests=0 ORDER BY idactivity ASC;");
	while($rowAct = $CKoneksi->mysqlFetch($queryAct))
	{
		$aprv = $rowAct['bosapprove'];
		$lock = $rowAct['lockedit'];
		$checked = "";
		$dis = "";
		$bg = "bgcolor=\"#FFDDDD\"";
		if($lock == "Y")
		{
			$checked = "checked";
		}
		if($aprv == "Y")
		{
			$checked = "checked";
			$dis = "disabled";
		}
		if($checked == "checked")
		{
			$bg = "bgcolor=\"#DDFFDD\"";
		}
		
		// ------- BORDER ------
		if($j == 1)
		{
			$type1 = "class=\"borderAll\"";
			$type2 = "class=\"borderLeftNull\"";
			$type3 = "class=\"borderLeftRightNull\"";
		}
		if($j != 1)
		{
			$type1 = "class=\"borderTopNull\"";
			$type2 = "class=\"borderTopLeftNull\"";
			$type3 = "class=\"borderBottom\"";
		}
	
	$html.= "				<tr>
                    	<td width=\"5%\"></td>
                        <td>
                        <table id=\"tbl".$i.$j."\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" ".$bg.">
                        	<tr valign=\"top\">
                            	<td ".$type1." align=\"center\" style=\"color:#000080;font-weight:bold;font-family:Tahoma;font-size:13px;\" width=\"3%\">
                                	".$j."
                                </td>
                                <td ".$type3." width=\"1%\"></td>
                                <td ".$type2." align=\"left\" width=\"82%\">".$rowAct['relatedinfo']."
                                </td>
                                <td ".$type2." align=\"center\" width=\"10%\">".$COtherReport->selisihJam($rowAct['fromtime'], $rowAct['totime'])."</td>
                                <td ".$type2." align=\"center\" width=\"4%\">
                                	<input type=\"checkbox\" ".$checked." ".$dis." onClick=\"ajaxJam(this.checked,'".$rowAct['idactivity']."', '".$rowAct['ownerid']."', 'updateTime', 'data".$i."');ubahBg('tbl".$i.$j."',this.checked);$('#cekboxAktif').val(".$i.");\"/>
                                </td>
                            </tr>
                        </table>
                        </td>
                    </tr>";
	$j++;}
					
	$html.= "		</table>";
	
	echo $html;
}

if($halamanPost == "updateTotal")
{
	$fromDate = $_POST['from'];
	$toDate = $_POST['to'];
	
	$query = $CKoneksi->mysqlQuery("SELECT userfullnm,userid FROM login WHERE deletests = 0 AND active = 'Y' ORDER BY userfullnm;");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$jam = $COtherReport->pheonwj($row['userid'], $fromDate, $toDate);
		$jamKerja = $jamKerja + $jam;
	}
	echo "All Total : ".$jamKerja." hours";
}
?>