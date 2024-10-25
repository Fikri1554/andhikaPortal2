<?php
require_once("../config.php");

// ========= -START- Cek Date card pada Tahun & Bulan yg dipilih =========
if($halamanPost == "cariDate")
{
	$createDatePost = $_POST['createDate'];
	
	$whereDate = "SUBSTR(date,1,7)= '".$createDatePost."' AND";
	if($createDatePost == 0000-00)
	{
		$whereDate = "";
	}
	
	$html.="<select class=\"elementMenu\" id=\"dateCard\" name=\"dateCard\" style=\"width:100px;\" title=\"Choose Card Date Created\" onchange=\"ajaxGetOwner(this.value, 'cariOwner', 'ajaxCariOwner');\">";
	$html.="<option value=\"00\">-- ALL --</option>";
	$query = $CKoneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(date,9,2)) AS tgl FROM tblkeluhan WHERE ".$whereDate." deletests=0 ORDER BY tgl DESC");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$sel = "";
		$queryD = $CKoneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(date,9,2)) AS tgl FROM tblkeluhan WHERE ".$whereDate." deletests=0 ORDER BY tgl DESC LIMIT 1");
		while($rowD =$CKoneksi->mysqlFetch($queryD))
		{
			$tgl = $rowD['tgl'];
			if($row['tgl'] == $rowD['tgl'])
			{
				$sel = "selected=\"selected\"";
			}
		}
		$hari = $row['tgl'];
		$html.="<option value=\"".$hari."\" ".$sel.">".$hari."</option>";
	}
	$html.="</select>";
	
	$date = $createDatePost."-".$tgl;
	$whereOwn = "date= '".$date."' AND";
	if($createDatePost == 0000-00)
	{
		$whereOwn = "";
	}
	
	$htmlOwn.="<span id=\"ajaxCariOwner\"><select class=\"elementMenu\" id=\"ownerId\" name=\"ownerId\" style=\"width:250px;\" title=\"Choose Name\">";
	$htmlOwn.="<option value=\"00000\">-- ALL --</option>";
	$query = $CKoneksi->mysqlQuery("SELECT ownerid, ownername FROM tblkeluhan WHERE ".$whereOwn." deletests=0 GROUP BY ownerid HAVING count(*)>0 ORDER BY ownername ASC");
	while($row =$CKoneksi->mysqlFetch($query))
	{
		$sel = "";
		$queryM = $CKoneksi->mysqlQuery("SELECT ownerid FROM tblkeluhan WHERE ".$whereOwn." deletests=0 GROUP BY ownerid HAVING count(*)>0 ORDER BY ownername ASC LIMIT 1");
		while($rowM =$CKoneksi->mysqlFetch($queryM))
		{
			if($row['ownerid'] == $rowM['ownerid'])
			{
				$sel = "selected=\"selected\"";
			}
		}
		$htmlOwn.="<option value=\"".$row['ownerid']."\" ".$sel.">".$row['ownername']."</option>";
	}
	$htmlOwn.="</select></span>";
	
	echo $html."&nbsp;".$htmlOwn;
}
// ========= -END OF- Cek Date card pada Tahun & Bulan yg dipilih =========

// ========= -START- Cek owner card pada tanggal yg dipilih =========
if($halamanPost == "cariOwner")
{
	$createDatePost = $_POST['createDate'];
	$thnBlnPost = $_POST['thnBln'];
	$date = $thnBlnPost."-".$createDatePost ;
	$whereDate = "date = '".$date."' AND";
	if($createDatePost == 00)
	{
		$whereDate = "SUBSTR(date,1,7)= '".$thnBlnPost."' AND";
	}
	if($thnBlnPost == 0000-00)
	{
		$whereDate = "SUBSTR(date,9,2)='".$createDatePost."' AND";
	}
	if($createDatePost == 00 && $thnBlnPost == 0000-00)
	{
		$whereDate = "";
	}
	
	$html.="<select class=\"elementMenu\" id=\"ownerId\" name=\"ownerId\" style=\"width:250px;\" title=\"Choose Name\">";
	$html.="<option value=\"00000\">-- ALL --</option>";
	$query = $CKoneksi->mysqlQuery("SELECT ownerid, ownername FROM tblkeluhan WHERE ".$whereDate." deletests=0 GROUP BY ownerid HAVING count(*)>0 ORDER BY ownername ASC");
	while($row =$CKoneksi->mysqlFetch($query))
	{
		$sel = "";
		$queryM = $CKoneksi->mysqlQuery("SELECT ownerid FROM tblkeluhan WHERE ".$whereDate." deletests=0 GROUP BY ownerid HAVING count(*)>0 ORDER BY ownername ASC LIMIT 1");
		while($rowM =$CKoneksi->mysqlFetch($queryM))
		{
			if($row['ownerid'] == $rowM['ownerid'])
			{
				$sel = "selected=\"selected\"";
			}
		}
		$html.="<option value=\"".$row['ownerid']."\" ".$sel.">".$row['ownername']."</option>";
	}
	$html.="</select>";
	
	echo $html;
}
// ========= -END OF- Cek owner card pada tanggal yg dipilih =========
if($halamanPost == "unreadQuery")
{
	$thnBlnPost = $_POST['thnBln'];
	$dateCardPost = $_POST['dateCard'];
	$datePost = $thnBlnPost."-".$dateCardPost;
	$ownerIdPost = $_POST['ownerId'];
	
	$unread = $CQhse->unReadInbox($datePost, $ownerIdPost);
	echo "UNREAD : ".$unread." &nbsp;";
}

if($halamanPost == "unreadQueryRefresh")
{
	$dateCardPost = $_POST['dateCard'];
	$ownerIdPost = $_POST['ownerId'];
	
	$unread = $CQhse->unReadInbox($dateCardPost, $ownerIdPost);
	echo "UNREAD : ".$unread." &nbsp;";
}

if($halamanPost == "cekKomenAdaTidak")
{
	$komenPost = mysql_real_escape_string($_POST['komen']);
	$ownerIdPost = $_POST['ownerId'];
	$idKeluhanPost = $_POST['idKeluhan'];
	
	$query = $CKoneksi->mysqlQuery("SELECT idkeluhan FROM tblkeluhan WHERE idkeluhan = '".$idKeluhanPost."' AND ownerid = '".$ownerIdPost."' AND admcomment = '".$komenPost."' AND deletests=0");
	$jmlRow = $CKoneksi->mysqlNRows($query);
	
	$nilai = "tidak";
	if($jmlRow > 0)
	{
		$nilai = "ada";
	}
	echo "<input type=\"hidden\" id=\"adaTidak\" value=\"".$nilai."\"/>";
}
?>