<?php
require_once("../config.php");
require_once("configAtk.php");

if($halamanPost == "cekFileAdaTidak")
{
	$itemIdPost = $_POST['itemId'];
	$nmUnitPost = $_POST['nmUnit'];
	
	//echo $itemIdPost.$nmUnitPost;
	$sqlId = "" ;
	if($itemIdPost != "")
	{
		$sqlId = "itemid!=".$itemIdPost." AND" ;
	}

	$query = $CKoneksiAtk->mysqlQuery("SELECT itemname FROM item WHERE ".$sqlId." itemname ='".$nmUnitPost."' AND deletests=0 LIMIT 1");
	$row = $CKoneksiAtk->mysqlFetch($query);
	
	$nilai = "kosong";
	if($row['itemname'] != "")
	{
		$nilai = "ada";
	}

	echo "&nbsp;<input type=\"hidden\" id=\"fileAdaTidak\" name=\"fileAdaTidak\" value=\"".$nilai."\">";
}

if($halamanPost == "cekQtyAdaTidak")
{
	$unitIdPost = $_POST['unitId'];
	$nmUnitPost = $_POST['nmUnit'];
	
	//echo $itemIdPost.$nmUnitPost;
	$sqlId = "" ;
	if($unitIdPost != "")
	{
		$sqlId = "unitid!=".$unitIdPost." AND" ;
	}

	$query = $CKoneksiAtk->mysqlQuery("SELECT unitname FROM mstunit WHERE ".$sqlId." unitname ='".$nmUnitPost."' LIMIT 1");
	$row = $CKoneksiAtk->mysqlFetch($query);
	
	$nilai = "kosong";
	if($row['unitname'] != "")
	{
		$nilai = "ada";
	}

	echo "<input type=\"hidden\" id=\"qtyAdaTidak\" name=\"qtyAdaTidak\" value=\"".$nilai."\">";
}

if($halamanPost == "cekStatusRead")
{
	$transIdPost = $_POST['transId'];
	
	$query = $CKoneksiAtk->mysqlQuery("SELECT status FROM trans WHERE transid =".$transIdPost.";");
	$row = $CKoneksiAtk->mysqlFetch($query);
	$nilai = $row['status'];
	
	echo "<input type=\"hidden\" id=\"statusRead\" name=\"statusRead\" value=\"".$nilai."\">";
}

if($halamanPost == "cekStatusReadReq")
{
	$reqIdPost = $_POST['reqId'];
	
	$query = $CKoneksiAtk->mysqlQuery("SELECT status FROM reqnew WHERE reqid =".$reqIdPost.";");
	$row = $CKoneksiAtk->mysqlFetch($query);
	$nilai = $row['status'];
	
	echo "<input type=\"hidden\" id=\"statusRead\" name=\"statusRead\" value=\"".$nilai."\">";
}

if($halamanPost == "bukaImg")
{
	$itemIdPost = $_POST['itemId'];
	
	//echo $itemNamePost."|";

	$query = $CKoneksiAtk->mysqlQuery("SELECT itemimg FROM item WHERE itemid ='".$itemIdPost."' AND deletests=0 LIMIT 1");
	$row = $CKoneksiAtk->mysqlFetch($query);
	
	if($itemIdPost == "0")
	{
		echo "";
	}
	if($itemIdPost != "0")
	{
		echo "<img src=\"../picture/".$row['itemimg']."\" width=\"120\" height=\"104\"/>";
	}
}

if($halamanPost == "bukaQty")
{
	$itemIdPost = $_POST['itemId'];
	
	//echo $itemNamePost."|";

	$query = $CKoneksiAtk->mysqlQuery("SELECT qtytype FROM item WHERE itemid ='".$itemIdPost."' AND deletests=0 LIMIT 1");
	$row = $CKoneksiAtk->mysqlFetch($query);
	
	echo "<input type=\"text\" class=\"elementDefault\" id=\"satuanQty\" name=\"satuanQty\" style=\"width:68%;height:28px;\" value=\"".$row['qtytype']."\"  readonly/>";
}

if($halamanPost == "bukaPrice")
{
	$itemIdPost = $_POST['itemId'];
	
	//echo $itemNamePost."|";

	$query = $CKoneksiAtk->mysqlQuery("SELECT stockprice FROM stock WHERE itemid =".$itemIdPost." ORDER BY stockid DESC LIMIT 1");
	$row = $CKoneksiAtk->mysqlFetch($query);
	
	$html = "<input type=\"text\" class=\"elementDefault\" id=\"price\" name=\"price\" style=\"width:99%;height:28px;\" onFocus=\"setup();\" onKeyUp=\"setup();\">";
	if($row['stockprice'] != "")
	{
		$html = "<input type=\"text\" class=\"elementDefault\" id=\"price\" name=\"price\" style=\"width:99%;height:28px;\" onFocus=\"setup();\" onKeyUp=\"setup();\" value=\"".number_format((float)$row['stockprice'], 0, '.', ',')."\" >";
	}
	
	echo $html;
}

if($halamanPost == "cekMonthAdaTidak")
{	
	$yearMonth = $CReqAtk->yearMonthNow($CPublic);
	$bln =  substr($yearMonth,4,2);
	$thn =  substr($yearMonth,0,4);
	
	$query = $CKoneksiAtk->mysqlQuery("SELECT stockid FROM stock WHERE stockyear=".$thn." AND stockmonth=".$bln."");
	$jml = $CKoneksiAtk->mysqlNRows($query);
	$nilai = "ada";
	if($jml < 1)
	{
		$nilai = "tidak";
	}
	
	echo "<input type=\"hidden\" id=\"cekMonth\" name=\"cekMonth\" value=\"".$nilai."\">";
}

// === START OF FILTER TAHUN TRANSACTION ================================================================================== 
if($halamanPost == "filterTahun")
{
	$thnPost = $_POST['thn'];
	$userJenis = $_POST['userJenis'];
	$userId = $_POST['userIdLogin'];
	
	if($thnPost != "0000" && $userJenis == "Y")
	{
		$where = "WHERE thn=".$thnPost." AND cancelsts=0";
	}
	if($thnPost == "0000" && $userJenis == "Y")
	{
		$where = "WHERE cancelsts=0";
	}
	if($thnPost != "0000" && $userJenis == "N")
	{
		$where = "WHERE thn=".$thnPost." AND ownerid=".$userId." AND cancelsts=0";
	}
	if($thnPost == "0000" && $userJenis == "N")
	{
		$where = "WHERE ownerid=".$userId." AND cancelsts=0";
	}
	
	$menu.="<select id=\"bln\" class=\"elementMenu\" style=\"width:69px;height:29px;\" title=\"Choose Month\" onchange=\"ajaxFilter(this.value, 'filterBln', 'idFilterBulan');\">";
	$menu.="<option value=\"00\">Month</option>";
		$queryBln = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT bln FROM trans ".$where." ORDER BY bln ASC");
		while($row = $CKoneksiAtk->mysqlFetch($queryBln))
		{
			$menu.= "<option value=".$row['bln']."> ".$row['bln']." </option>";
		}
	$menu.="</select>";
	
	$menu.="<span id=\"idFilterBulan\">";
	
	$menu.="&nbsp;<select id=\"tgl\" class=\"elementMenu\" style=\"width:62px;height:29px;\" title=\"Choose Date\" onchange=\"ajaxFilter(this.value, 'filterTgl', 'idFilterTanggal');\">";
	$menu.="<option value=\"00\">Date</option>";
		$queryTgl = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT tgl FROM trans ".$where." ORDER BY tgl ASC");
		while($rTgl = $CKoneksiAtk->mysqlFetch($queryTgl))
		{
			$menu.= "<option value=".$rTgl['tgl']."> ".$rTgl['tgl']." </option>";
		}
	$menu.="</select><span id=\"idFilterTanggal\">";
	
	$menu.="&nbsp;<select id=\"stat\" class=\"elementMenu\" style=\"width:93px;height:29px;\" title=\"Choose Status\" onchange=\"ajaxFilter(this.value, 'filterSts', 'idFilterStatus');\">";
	$menu.="<option value=\"all\">All Status</option>";
		$queryStat = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT status FROM trans ".$where." ORDER BY status ASC");
		while($rStat = $CKoneksiAtk->mysqlFetch($queryStat))
		{
			$menu.= "<option value=".$rStat['status']."> ".$rStat['status']." </option>";
		}
	$menu.="</select><span id=\"idFilterStatus\">";
	
	if($userJenis == "Y")
	{
		$menu.="&nbsp;<select id=\"nama\" class=\"elementMenu\" style=\"width:150px;height:29px;\" title=\"Choose Category\">";
		$menu.="<option value=\"00000\">All Name</option>";
			$queryNm = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT ownerid FROM trans ".$where." ORDER BY ownerid ASC");
			while($rNm = $CKoneksiAtk->mysqlFetch($queryNm))
			{
				$menu.= "<option value=".$rNm['ownerid']."> ".$CReqAtk->detilLoginAtk($rNm['ownerid'], "userfullnm", $db)." </option>";
			}
		$menu.="</select>";
	}
	
	$menu.="</span></span></span>";
	echo $menu;
}
// === END OF FILTER TAHUN TRANSACTION ==================================================================================

// === START OF FILTER BULAN TRANSACTION ================================================================================ 
if($halamanPost == "filterBln")
{
	$thnPost = $_POST['thn'];
	$userJenis = $_POST['userJenis'];
	$userId = $_POST['userIdLogin'];
	$blnPost = $_POST['bln'];
	$ajaxTahun = $_POST['ajaxTahun'];
	
	$condition = "";
	$cThn = "";
	$cBln = "";
	$cUsr = "";
	
	if($thnPost != "0000")
	{
		$cThn = "thn = ".$thnPost."";
	}
	if($blnPost != "00")
	{
		$cBln = "bln = ".$blnPost."";
		if($thnPost != "0000")
		{
			$cBln = "AND bln = ".$blnPost."";
		}
	}
	if($userJenis == "N")
	{
		$cUsr = "ownerid = ".$userId."";
		if($thnPost != "0000" || $blnPost != "00")
		{
			$cUsr = "AND ownerid = ".$userId."";
		}
	}
	$condition = "WHERE ".$cThn." ".$cBln." ".$cUsr." AND cancelsts=0";
	
	if($thnPost == "0000" && $blnPost == "00" && $userJenis == "Y")
	{
		$condition = "WHERE cancelsts=0";
	}
	
	$menuTgl.="<select id=\"tgl\" class=\"elementMenu\" style=\"width:62px;height:29px;\" title=\"Choose Date\" onchange=\"ajaxFilter(this.value, 'filterTgl', 'idFilterTanggal');\">";
	$menuTgl.="<option value=\"00\">Date</option>";
		$queryTgl = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT tgl FROM trans ".$condition." ORDER BY tgl ASC");
		while($rTgl = $CKoneksiAtk->mysqlFetch($queryTgl))
		{
			$menuTgl.= "<option value=".$rTgl['tgl']."> ".$rTgl['tgl']." </option>";
		}
	$menuTgl.="</select><span id=\"idFilterTanggal\">";
	
	$menuSts.="<select id=\"stat\" class=\"elementMenu\" style=\"width:93px;height:29px;\" title=\"Choose Status\" onchange=\"ajaxFilter(this.value, 'filterSts', 'idFilterStatus');\">";
	$menuSts.="<option value=\"all\">All Status</option>";
		$queryStat = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT status FROM trans ".$condition." ORDER BY status ASC");
		while($rStat = $CKoneksiAtk->mysqlFetch($queryStat))
		{
			$menuSts.= "<option value=".$rStat['status']."> ".$rStat['status']." </option>";
		}
	$menuSts.="</select><span id=\"idFilterStatus\">";
	
	if($userJenis == "Y")
	{
		$menuNm.="<select id=\"nama\" class=\"elementMenu\" style=\"width:150px;height:29px;\" title=\"Choose Category\">";
		$menuNm.="<option value=\"00000\">All Name</option>";
			$queryNm = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT ownerid FROM trans ".$condition." ORDER BY ownerid ASC");
			while($rNm = $CKoneksiAtk->mysqlFetch($queryNm))
			{
				$menuNm.= "<option value=".$rNm['ownerid']."> ".$CReqAtk->detilLoginAtk($rNm['ownerid'], "userfullnm", $db)." </option>";
			}
		$menuNm.="</select>";
	}
	
	$closeSpan = "</span></span>";
	
	$spasi = "";
	if($ajaxTahun == "on")
	{
		$spasi = "&nbsp;";
	}
	echo $spasi.$menuTgl." ".$menuSts." ".$menuNm.$closeSpan;
}
// === END OF FILTER BULAN TRANSACTION ================================================================================== 

// === START OF FILTER TANGGAL TRANSACTION ================================================================================ 
if($halamanPost == "filterTgl")
{
	$thnPost = $_POST['thn'];
	$blnPost = $_POST['bln'];
	$tglPost = $_POST['tgl'];
	$userJenis = $_POST['userJenis'];
	$userId = $_POST['userIdLogin'];
	$ajaxTahun = $_POST['ajaxTahun'];
	$ajaxBulan = $_POST['ajaxBulan'];
	
	$condition = "";
	$cThn = "";
	$cBln = "";
	$cTgl = "";
	$cUsr = "";
	
	if($thnPost != "0000")
	{
		$cThn = "thn = ".$thnPost."";
	}
	if($blnPost != "00")
	{
		$cBln = "bln = ".$blnPost."";
		if($thnPost != "0000")
		{
			$cBln = "AND bln = ".$blnPost."";
		}
	}
	if($tglPost != "00")
	{
		$cTgl = "tgl = ".$tglPost."";
		if($thnPost != "0000" || $blnPost != "00")
		{
			$cTgl = "AND tgl = ".$tglPost."";
		}
	}
	if($userJenis == "N")
	{
		$cUsr = "ownerid = ".$userId."";
		if($thnPost != "0000" || $blnPost != "00" || $tglPost != "00")
		{
			$cUsr = "AND ownerid = ".$userId."";
		}
	}
	$condition = "WHERE ".$cThn." ".$cBln." ".$cTgl." ".$cUsr." AND cancelsts=0";
	
	if($thnPost == "0000" && $blnPost == "00" && $tglPost == "00" && $userJenis == "Y")
	{
		$condition = "WHERE cancelsts=0";
	}
	
	$menuSts.="<select id=\"stat\" class=\"elementMenu\" style=\"width:93px;height:29px;\" title=\"Choose Status\" onchange=\"ajaxFilter(this.value, 'filterSts', 'idFilterStatus');\">";
	$menuSts.="<option value=\"all\">All Status</option>";
		$queryStat = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT status FROM trans ".$condition." ORDER BY status ASC");
		while($rStat = $CKoneksiAtk->mysqlFetch($queryStat))
		{
			$menuSts.= "<option value=".$rStat['status']."> ".$rStat['status']." </option>";
		}
	$menuSts.="</select><span id=\"idFilterStatus\">";
	
	if($userJenis == "Y")
	{
		$menuNm.="<select id=\"nama\" class=\"elementMenu\" style=\"width:150px;height:29px;\" title=\"Choose Category\">";
		$menuNm.="<option value=\"00000\">All Name</option>";
			$queryNm = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT ownerid FROM trans ".$condition." ORDER BY ownerid ASC");
			while($rNm = $CKoneksiAtk->mysqlFetch($queryNm))
			{
				$menuNm.= "<option value=".$rNm['ownerid']."> ".$CReqAtk->detilLoginAtk($rNm['ownerid'], "userfullnm", $db)." </option>";
			}
		$menuNm.="</select>";
	}
	$closeSpan = "</span>";
	
	$spasi = "";
	if($ajaxTahun == "on" || $ajaxBulan == "on")
	{
		$spasi = "&nbsp;";
	}
	echo $spasi.$menuSts." ".$menuNm.$closeSpan;
}
// === END OF FILTER TANGGAL TRANSACTION ==================================================================================

// === START OF FILTER STATUS TRANSACTION ================================================================================ 
if($halamanPost == "filterSts")
{
	$thnPost = $_POST['thn'];
	$blnPost = $_POST['bln'];
	$tglPost = $_POST['tgl'];
	$status = $_POST['status'];
	$ajaxTahun = $_POST['ajaxTahun'];
	$ajaxBulan = $_POST['ajaxBulan'];
	$ajaxTanggal = $_POST['ajaxTanggal'];
	
	$condition = "";
	$cThn = "";
	$cBln = "";
	$cTgl = "";
	$cSts = "";
	
	if($thnPost != "0000")
	{
		$cThn = "thn = ".$thnPost."";
	}
	if($blnPost != "00")
	{
		$cBln = "bln = ".$blnPost."";
		if($thnPost != "0000")
		{
			$cBln = "AND bln = ".$blnPost."";
		}
	}
	if($tglPost != "00")
	{
		$cTgl = "tgl = ".$tglPost."";
		if($thnPost != "0000" || $blnPost != "00")
		{
			$cTgl = "AND tgl = ".$tglPost."";
		}
	}
	if($status != "all")
	{
		$cSts = "status = '".$status."'";
		if($thnPost != "0000" || $blnPost != "00" || $tglPost != "00")
		{
			$cSts = "AND status = '".$status."'";
		}
	}

	$condition = "WHERE ".$cThn." ".$cBln." ".$cTgl." ".$cSts." AND cancelsts=0";
	
	if($thnPost == "0000" && $blnPost == "00" && $tglPost == "00" && $status == "all")
	{
		$condition = "WHERE cancelsts=0";
	}
	
	$menuNm.="<select id=\"nama\" class=\"elementMenu\" style=\"width:150px;height:29px;\" title=\"Choose Category\">";
	$menuNm.="<option value=\"00000\">All Name</option>";
		$queryNm = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT ownerid FROM trans ".$condition." ORDER BY ownerid ASC");
		while($rNm = $CKoneksiAtk->mysqlFetch($queryNm))
		{
			$menuNm.= "<option value=".$rNm['ownerid']."> ".$CReqAtk->detilLoginAtk($rNm['ownerid'], "userfullnm", $db)." </option>";
		}
	$menuNm.="</select>";
	
	$spasi = "";
	if($ajaxTahun == "on" || $ajaxBulan == "on" || $ajaxTanggal == "on")
	{
		$spasi = "&nbsp;";
	}
	echo $spasi.$menuNm;
}
// === END OF FILTER STATUS TRANSACTION ==================================================================================

if($halamanPost == "qtyType")
{
	$itemIdPost = $_POST['itemId'];
	echo $CReqAtk->detilAtkItem("qtytype", $itemIdPost);
}

if($halamanPost == "stock")
{
	$tglServer = $CPublic->tglServer();
	$bln =  substr($tglServer,4,2);
	$thn =  substr($tglServer,0,4);
	
	$itemIdPost = $_POST['itemId'];
	
	
	if($itemIdPost != "xxx")
	{
		$nilaiStock = $CReqAtk->lastStock("stockall", $itemIdPost, $bln, $thn);
		if($nilaiStock == "")
		{
			$nilaiStock = "0";
		}
	}
	if($itemIdPost == "xxx")
	{
		$nilaiStock = ". . .";
	}
	echo $nilaiStock;
}

// === START OF FILTER TAHUN REQUEST ================================================================================== 
if($halamanPost == "filterThnReq")
{
	$thnPost = $_POST['thn'];
	$userJenis = $_POST['userJenis'];
	$userId = $_POST['userIdLogin'];
	
	if($thnPost != "0000" && $userJenis == "Y")
	{
		$where = "WHERE thn=".$thnPost." AND cancelsts=0";
	}
	if($thnPost == "0000" && $userJenis == "Y")
	{
		$where = "WHERE cancelsts=0";
	}
	if($thnPost != "0000" && $userJenis == "N")
	{
		$where = "WHERE thn=".$thnPost." AND ownerid=".$userId." AND cancelsts=0";
	}
	if($thnPost == "0000" && $userJenis == "N")
	{
		$where = "WHERE ownerid=".$userId." AND cancelsts=0";
	}
	
	$menu.="<select id=\"bln\" class=\"elementMenu\" style=\"width:69px;height:29px;\" title=\"Choose Month\" onchange=\"ajaxFilter(this.value, 'filterBlnReq', 'idFltrBlnReq');\">";
	$menu.="<option value=\"00\">Month</option>";
		$queryBln = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT bln FROM reqnew ".$where." ORDER BY bln ASC");
		while($row = $CKoneksiAtk->mysqlFetch($queryBln))
		{
			$menu.= "<option value=".$row['bln']."> ".$row['bln']." </option>";
		}
	$menu.="</select>";
	
	$menu.="<span id=\"idFltrBlnReq\">";
	
	$menu.="&nbsp;<select id=\"tgl\" class=\"elementMenu\" style=\"width:62px;height:29px;\" title=\"Choose Date\" onchange=\"ajaxFilter(this.value, 'filterTglReq', 'idFltrTglReq');\">";
	$menu.="<option value=\"00\">Date</option>";
		$queryTgl = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT tgl FROM reqnew ".$where." ORDER BY tgl ASC");
		while($rTgl = $CKoneksiAtk->mysqlFetch($queryTgl))
		{
			$menu.= "<option value=".$rTgl['tgl']."> ".$rTgl['tgl']." </option>";
		}
	$menu.="</select><span id=\"idFltrTglReq\">";
	
	$menu.="&nbsp;<select id=\"stat\" class=\"elementMenu\" style=\"width:93px;height:29px;\" title=\"Choose Status\" onchange=\"ajaxFilter(this.value, 'filterStsReq', 'idFltrStsReq');\">";
	$menu.="<option value=\"all\">All Status</option>";
		$queryStat = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT status FROM reqnew ".$where." ORDER BY status ASC");
		while($rStat = $CKoneksiAtk->mysqlFetch($queryStat))
		{
			$menu.= "<option value=".$rStat['status']."> ".$rStat['status']." </option>";
		}
	$menu.="</select><span id=\"idFltrStsReq\">";
	
	if($userJenis == "Y")
	{
		$menu.="&nbsp;<select id=\"nama\" class=\"elementMenu\" style=\"width:150px;height:29px;\" title=\"Choose Category\">";
		$menu.="<option value=\"00000\">All Requestor</option>";
			$queryNm = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT ownerid FROM reqnew ".$condition." ORDER BY ownerid ASC");
			while($rNm = $CKoneksiAtk->mysqlFetch($queryNm))
			{
				$menu.= "<option value=".$rNm['ownerid']."> ".$CReqAtk->detilLoginAtk($rNm['ownerid'], "userfullnm", $db)." </option>";
			}
		$menu.="</select>";
	}
	
	$menu.="</span></span></span>";
	echo $menu;
	//echo "aa";
}
// === END OF FILTER TAHUN REQUEST ==================================================================================

// === START OF FILTER BULAN REQUEST ================================================================================ 
if($halamanPost == "filterBlnReq")
{
	$thnPost = $_POST['thn'];
	$userJenis = $_POST['userJenis'];
	$userId = $_POST['userIdLogin'];
	$blnPost = $_POST['bln'];
	$ajaxTahun = $_POST['ajaxTahun'];
	
	$condition = "";
	$cThn = "";
	$cBln = "";
	$cUsr = "";
	
	if($thnPost != "0000")
	{
		$cThn = "thn = ".$thnPost."";
	}
	if($blnPost != "00")
	{
		$cBln = "bln = ".$blnPost."";
		if($thnPost != "0000")
		{
			$cBln = "AND bln = ".$blnPost."";
		}
	}
	if($userJenis == "N")
	{
		$cUsr = "ownerid = ".$userId."";
		if($thnPost != "0000" || $blnPost != "00")
		{
			$cUsr = "AND ownerid = ".$userId."";
		}
	}
	$condition = "WHERE ".$cThn." ".$cBln." ".$cUsr." AND cancelsts=0";
	
	if($thnPost == "0000" && $blnPost == "00" && $userJenis == "Y")
	{
		$condition = "WHERE cancelsts=0";
	}
	
	$menuTgl.="<select id=\"tgl\" class=\"elementMenu\" style=\"width:62px;height:29px;\" title=\"Choose Date\" onchange=\"ajaxFilter(this.value, 'filterTglReq', 'idFltrTglReq');\">";
	$menuTgl.="<option value=\"00\">Date</option>";
		$queryTgl = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT tgl FROM reqnew ".$condition." ORDER BY tgl ASC");
		while($rTgl = $CKoneksiAtk->mysqlFetch($queryTgl))
		{
			$menuTgl.= "<option value=".$rTgl['tgl']."> ".$rTgl['tgl']." </option>";
		}
	$menuTgl.="</select><span id=\"idFltrTglReq\">";
	
	$menuSts.="<select id=\"stat\" class=\"elementMenu\" style=\"width:93px;height:29px;\" title=\"Choose Status\" onchange=\"ajaxFilter(this.value, 'filterStsReq', 'idFltrStsReq');\">";
	$menuSts.="<option value=\"all\">All Status</option>";
		$queryStat = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT status FROM reqnew ".$condition." ORDER BY status ASC");
		while($rStat = $CKoneksiAtk->mysqlFetch($queryStat))
		{
			$menuSts.= "<option value=".$rStat['status']."> ".$rStat['status']." </option>";
		}
	$menuSts.="</select><span id=\"idFltrStsReq\">";
	
	if($userJenis == "Y")
	{
		$menuNm.="<select id=\"nama\" class=\"elementMenu\" style=\"width:150px;height:29px;\" title=\"Choose Category\">";
		$menuNm.="<option value=\"00000\">All Requestor</option>";
			$queryNm = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT ownerid FROM reqnew ".$condition." ORDER BY ownerid ASC");
			while($rNm = $CKoneksiAtk->mysqlFetch($queryNm))
			{
				$menuNm.= "<option value=".$rNm['ownerid']."> ".$CReqAtk->detilLoginAtk($rNm['ownerid'], "userfullnm", $db)." </option>";
			}
		$menuNm.="</select>";
	}
	
	$closeSpan = "</span></span>";
	
	$spasi = "";
	if($ajaxTahun == "on")
	{
		$spasi = "&nbsp;";
	}
	echo $spasi.$menuTgl." ".$menuSts." ".$menuNm.$closeSpan;
}
// === END OF FILTER BULAN REQUEST ==================================================================================

// === START OF FILTER TANGGAL REQUEST ================================================================================ 
if($halamanPost == "filterTglReq")
{
	$thnPost = $_POST['thn'];
	$blnPost = $_POST['bln'];
	$tglPost = $_POST['tgl'];
	$userJenis = $_POST['userJenis'];
	$userId = $_POST['userIdLogin'];
	$ajaxTahun = $_POST['ajaxTahun'];
	$ajaxBulan = $_POST['ajaxBulan'];
	
	$condition = "";
	$cThn = "";
	$cBln = "";
	$cTgl = "";
	$cUsr = "";
	
	if($thnPost != "0000")
	{
		$cThn = "thn = ".$thnPost."";
	}
	if($blnPost != "00")
	{
		$cBln = "bln = ".$blnPost."";
		if($thnPost != "0000")
		{
			$cBln = "AND bln = ".$blnPost."";
		}
	}
	if($tglPost != "00")
	{
		$cTgl = "tgl = ".$tglPost."";
		if($thnPost != "0000" || $blnPost != "00")
		{
			$cTgl = "AND tgl = ".$tglPost."";
		}
	}
	if($userJenis == "N")
	{
		$cUsr = "ownerid = ".$userId."";
		if($thnPost != "0000" || $blnPost != "00" || $tglPost != "00")
		{
			$cUsr = "AND ownerid = ".$userId."";
		}
	}
	$condition = "WHERE ".$cThn." ".$cBln." ".$cTgl." ".$cUsr." AND cancelsts=0";
	
	if($thnPost == "0000" && $blnPost == "00" && $tglPost == "00" && $userJenis == "Y")
	{
		$condition = "WHERE cancelsts=0";
	}
	
	$menuSts.="<select id=\"stat\" class=\"elementMenu\" style=\"width:93px;height:29px;\" title=\"Choose Status\" onchange=\"ajaxFilter(this.value, 'filterStsReq', 'idFltrStsReq');\">";
	$menuSts.="<option value=\"all\">All Status</option>";
		$queryStat = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT status FROM reqnew ".$condition." ORDER BY status ASC");
		while($rStat = $CKoneksiAtk->mysqlFetch($queryStat))
		{
			$menuSts.= "<option value=".$rStat['status']."> ".$rStat['status']." </option>";
		}
	$menuSts.="</select><span id=\"idFltrStsReq\">";
	
	if($userJenis == "Y")
	{
		$menuNm.="<select id=\"nama\" class=\"elementMenu\" style=\"width:150px;height:29px;\" title=\"Choose Category\">";
		$menuNm.="<option value=\"00000\">All Requestor</option>";
			$queryNm = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT ownerid FROM reqnew ".$condition." ORDER BY ownerid ASC");
			while($rNm = $CKoneksiAtk->mysqlFetch($queryNm))
			{
				$menuNm.= "<option value=".$rNm['ownerid']."> ".$CReqAtk->detilLoginAtk($rNm['ownerid'], "userfullnm", $db)." </option>";
			}
		$menuNm.="</select>";
	}
	$closeSpan = "</span>";
	
	$spasi = "";
	if($ajaxTahun == "on" || $ajaxBulan == "on")
	{
		$spasi = "&nbsp;";
	}
	echo $spasi.$menuSts." ".$menuNm.$closeSpan;
}
// === END OF FILTER TANGGAL REQUEST ================================================================================== 

// === START OF FILTER STATUS REQUEST ================================================================================ 
if($halamanPost == "filterStsReq")
{
	$thnPost = $_POST['thn'];
	$blnPost = $_POST['bln'];
	$tglPost = $_POST['tgl'];
	$status = $_POST['status'];
	$ajaxTahun = $_POST['ajaxTahun'];
	$ajaxBulan = $_POST['ajaxBulan'];
	$ajaxTanggal = $_POST['ajaxTanggal'];
	
	$condition = "";
	$cThn = "";
	$cBln = "";
	$cTgl = "";
	$cSts = "";
	
	if($thnPost != "0000")
	{
		$cThn = "thn = ".$thnPost."";
	}
	if($blnPost != "00")
	{
		$cBln = "bln = ".$blnPost."";
		if($thnPost != "0000")
		{
			$cBln = "AND bln = ".$blnPost."";
		}
	}
	if($tglPost != "00")
	{
		$cTgl = "tgl = ".$tglPost."";
		if($thnPost != "0000" || $blnPost != "00")
		{
			$cTgl = "AND tgl = ".$tglPost."";
		}
	}
	if($status != "all")
	{
		$cSts = "status = '".$status."'";
		if($thnPost != "0000" || $blnPost != "00" || $tglPost != "00")
		{
			$cSts = "AND status = '".$status."'";
		}
	}

	$condition = "WHERE ".$cThn." ".$cBln." ".$cTgl." ".$cSts." AND cancelsts=0";
	
	if($thnPost == "0000" && $blnPost == "00" && $tglPost == "00" && $status == "all")
	{
		$condition = "WHERE cancelsts=0";
	}
	
	$menuNm.="<select id=\"nama\" class=\"elementMenu\" style=\"width:150px;height:29px;\" title=\"Choose Category\">";
	$menuNm.="<option value=\"00000\">All Requestor</option>";
		$queryNm = $CKoneksiAtk->mysqlQuery("SELECT DISTINCT ownerid FROM reqnew ".$condition." ORDER BY ownerid ASC");
		while($rNm = $CKoneksiAtk->mysqlFetch($queryNm))
		{
			$menuNm.= "<option value=".$rNm['ownerid']."> ".$CReqAtk->detilLoginAtk($rNm['ownerid'], "userfullnm", $db)." </option>";
		}
	$menuNm.="</select>";
	
	$spasi = "";
	if($ajaxTahun == "on" || $ajaxBulan == "on" || $ajaxTanggal == "on")
	{
		$spasi = "&nbsp;";
	}
	echo $spasi.$menuNm;
}

if($halamanPost == "monthBefore")
{
	$blnGet = $_POST['bln'];
	$bln =  substr($blnGet,4,2);
	
	echo $CReqAtk->blnSeb($CPublic, $bln);
}
// === END OF FILTER STATUS REQUEST ==================================================================================

if($halamanPost == "refundBtn")
{
	$tampil = $_POST['tampil'];
	$display = "";
	if($tampil == 'Y')
	{
		$display =  "<button id=\"btnApprv\" class=\"btnStandar\" onclick=\"\" style=\"width:88px;height:29px;\" title=\"Approve Transaction\">
				<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				  <tr>
					<td align=\"right\" width=\"22\"><img src=\"../picture/Thumbs-Up-32-Blue.png\" height=\"20\"/> </td>
					<td align=\"center\">re-Approve</td>
				  </tr>
				</table>
			</button>";
	}
	echo $display;
}
?>