<?php
class CReqAtk
{	
	function CReqAtk($koneksiMysql)
	{
		$this->koneksi = $koneksiMysql;
	}
	
	function detilLoginAtk($userId, $field, $DB)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM ".$DB.".login WHERE userid='".$userId."' AND ACTIVE='Y' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		$isi = $row[$field];
		
		return $isi;
	}
	
	function userJenis($userId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT userjenis FROM msuserjenis WHERE userid=".$userId."");
		$row = $this->koneksi->mysqlFetch($query);
		
		$userJenis = "N";
		if($row['userjenis'] == "admin")
		{
			$userJenis = "Y";
		}
		
		return $userJenis;
	}
	
	function detilMstUnit($field, $unitid)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM mstunit WHERE unitid='".$unitid."'");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilAtkItem($field, $itemid)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM item WHERE itemid='".$itemid."'");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilStockByItemId($field, $itemid)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM stock WHERE itemid='".$itemid."'");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function lastStock($field, $itemId, $bln, $thn)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM stock WHERE itemid=".$itemId." AND stockmonth=".$bln." AND stockyear=".$thn." ORDER BY stockid DESC LIMIT 1");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function stockSaatIni($itemId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT stockqty FROM stock WHERE itemid=".$itemId." ORDER BY stockid DESC LIMIT 1");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row["stockqty"];
	}
	
	function blnDisplay($CPublic)
	{
		$blnDisplay = "";
		$tglServer = $CPublic->tglServer();
		$bln =  substr($tglServer,4,2);
		
		$count = intval($bln)-1;
		$prevMonth = $CPublic->zerofill($count, 2);
		$blnDisplay = $CPublic->bulanSetengah($prevMonth, "eng");
		if($bln == "01")
		{
			$blnDisplay = $CPublic->bulanSetengah(12, "eng");
		}
		
		return $blnDisplay ;
	}
	
	function blnSeb($CPublic, $bln)
	{
		$count = intval($bln)-1;
		$prevMonth = $CPublic->zerofill($count, 2);
		$blnDisplay = $CPublic->bulanSetengah($prevMonth, "eng");
		if($bln == "01")
		{
			$blnDisplay = $CPublic->bulanSetengah(12, "eng");
		}
		
		return $blnDisplay ;
	}
	
	function optionMonth($CPublic)
	{
		$tglServer = $CPublic->tglServer();
		$bln =  substr($tglServer,4,2);
		$thn =  substr($tglServer,0,4);
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT stockmonth,stockyear FROM stock ORDER BY CONCAT(stockyear,stockmonth) DESC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$month = $row['stockmonth'];
			$year = $row['stockyear'];
			
			$sel = "";
			if($thn.$bln == $year.$month)
			{
				$sel = "selected";
			}
			
			$motntTxt = $CPublic->detilBulanNamaAngka($month, "eng");
			$option.= "<option value=\"".$year.$month."\" ".$sel."> ".$motntTxt." ".$year." </option>";
		}
		
		return $option;
	}
	
	function yearMonthNow($CPublic)
	{
		$tglServer = $CPublic->tglServer();
		$bln =  substr($tglServer,4,2);
		$thn =  substr($tglServer,0,4);
		
		return $thn.$bln;
	}
	
	function monthBeforeNow($CPublic)
	{
		$tglServer = $CPublic->tglServer();
		$bln =  substr($tglServer,4,2);
		
		if($bln == 1)
		{
			$blnBefore = "12";
		}
		else
		{
			$blnBefore = $bln-1;
		}
		
		return ucfirst(strtolower($CPublic->detilBulanNamaAngka($blnBefore, "eng")));
	}
	
	function cekItem($field, $ownerId, $itemId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM cart WHERE ownerid=".$ownerId." AND itemid = '".$itemId."'");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function atkTrans($field, $transId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM trans WHERE transid = '".$transId."'");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function jmlTrans($transId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT detilid FROM transdtl WHERE transid = '".$transId."'");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
	
	function atkTransDtl($field, $detilId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM transdtl WHERE detilid = '".$detilId."'");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function transDtItemName($transId)
	{
		$i=1;
		$itemNm = "";
		
		$query = $this->koneksi->mysqlQuery("SELECT itemid FROM transdtl WHERE transid=".$transId.";");
		$jmlTrans = $this->koneksi->mysqlNRows($query);
		while($rTrans = $this->koneksi->mysqlFetch($query))
		{
			$koma = ", ";
			if($jmlTrans == $i)
			{
				$koma = "";
			}
			$itemNm.= $this->detilAtkItem("itemname", $rTrans['itemid']).$koma;
			$i++;
		}
		
		return $itemNm;
	}
	
	function menuThn($userJenis, $userId)
	{
		$sql = "";
		if($userJenis == "N")
		{
			$sql = "WHERE ownerid = ".$userId."";
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT thn FROM trans ".$sql." ORDER BY thn ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.= "<option value=\"".$row['thn']."\"> ".$row['thn']." </option>";
		}
		//html = "<option> ".$userJenis." </option>";;
		return $html;
	}
	
	function menuBln($userJenis, $userId)
	{
		$sql = "";
		if($userJenis == "N")
		{
			$sql = "WHERE ownerid = ".$userId."";
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT bln FROM trans ".$sql." ORDER BY bln ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.= "<option value=\"".$row['bln']."\"> ".$row['bln']." </option>";
		}
		
		return $html;
	}
	
	function menuTgl($userJenis, $userId)
	{
		$sql = "";
		if($userJenis == "N")
		{
			$sql = "WHERE ownerid = ".$userId."";
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT tgl FROM trans ".$sql." ORDER BY tgl ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.= "<option value=\"".$row['tgl']."\"> ".$row['tgl']." </option>";
		}
		
		return $html;
	}
	
	function menuStatus($userJenis, $userId)
	{
		$sql = "";
		if($userJenis == "N")
		{
			$sql = "WHERE ownerid = ".$userId."";
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT status FROM trans ".$sql." ORDER BY status ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.= "<option value=\"".$row['status']."\"> ".$row['status']." </option>";
		}
		
		return $html;
	}
	
	function menuName()
	{
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT ownerid FROM trans");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$ownerId = $row['ownerid'];
			$html.= "<option value=\"".$ownerId."\"> ".$this->detilLoginAtk($ownerId, "userfullnm", "andhikaPortalTes")." </option>";
		}
		
		return $html;
	}
	
	function menuThnReq($userJenis, $userId)
	{
		$sql = "";
		if($userJenis == "N")
		{
			$sql = "WHERE ownerid = ".$userId."";
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT thn FROM reqnew ".$sql." ORDER BY thn ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.= "<option value=\"".$row['thn']."\"> ".$row['thn']." </option>";
		}
		//html = "<option> ".$userJenis." </option>";;
		return $html;
	}
	
	function menuBlnReq($userJenis, $userId)
	{
		$sql = "";
		if($userJenis == "N")
		{
			$sql = "WHERE ownerid = ".$userId."";
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT bln FROM reqnew ".$sql." ORDER BY bln ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.= "<option value=\"".$row['bln']."\"> ".$row['bln']." </option>";
		}
		
		return $html;
	}
	
	function menuTglReq($userJenis, $userId)
	{
		$sql = "";
		if($userJenis == "N")
		{
			$sql = "WHERE ownerid = ".$userId."";
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT tgl FROM reqnew ".$sql." ORDER BY tgl ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.= "<option value=\"".$row['tgl']."\"> ".$row['tgl']." </option>";
		}
		
		return $html;
	}
	
	function menuStatusReq($userJenis, $userId)
	{
		$sql = "";
		if($userJenis == "N")
		{
			$sql = "WHERE ownerid = ".$userId."";
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT status FROM reqnew ".$sql." ORDER BY status ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.= "<option value=\"".$row['status']."\"> ".$row['status']." </option>";
		}
		
		return $html;
	}
	
	function menuNameReq()
	{
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT ownerid FROM reqnew");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$ownerId = $row['ownerid'];
			$html.= "<option value=\"".$ownerId."\"> ".$this->detilLoginAtk($ownerId, "userfullnm", "andhikaPortalTes")." </option>";
		}
		
		return $html;
	}
	
	function cekMstUnitOnMstItem($qtyType)//cek Mst Unit sudah dipakai di Mst Item atau belum
	{
		$query = $this->koneksi->mysqlQuery("SELECT itemid FROM item WHERE qtytype = '".$qtyType."';");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
	
	function cekTransMstUnit($qtyType)//cek Mst Unit sudah dipakai di Mst Item atau belum, jika dipakai sudah dipakai di trans atau belum
	{
		$i=0;
		$query = $this->koneksi->mysqlQuery("SELECT itemid FROM item WHERE qtytype = '".$qtyType."';");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$itemId = $row['itemid'];
			$cekTransItem= $this->cekTransItem($itemId);
			$i = $i + $cekTransItem;
		}
		
		return $i;
	}
	
	function cekTransItem($itemId) // cek item sudah pernah ada transaksi atau belum, jika ada, maka tidak bisa di edit
	{
		$i=0;
		$query = $this->koneksi->mysqlQuery("SELECT transid FROM transdtl WHERE itemid = ".$itemId.";");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$transId = $row['transid'];
			$cekTransItem2= $this->cekTransItem2($transId);
			$i = $i + $cekTransItem2;
		}
		
		return $i;
	}
	
	function cekTransItem2($transId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT transid FROM trans WHERE transid = ".$transId." AND cancelsts=0;");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
	
	function cekCartItem($itemId) // cek item ada di cart atau tidak
	{
		$query = $this->koneksi->mysqlQuery("SELECT ownerid FROM cart WHERE itemid = ".$itemId.";");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
	
	function atkReq($field, $reqId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM reqnew WHERE reqid = '".$reqId."'");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function adminId()
	{
		$query = $this->koneksi->mysqlQuery("SELECT userid FROM msuserjenis WHERE userjenis = 'admin';");
		$row = $this->koneksi->mysqlFetch($query);
	
		return $row['userid'];
	}
	
	function emailKe($db)
	{
		$i=1;
		$email="";
		$query = $this->koneksi->mysqlQuery("SELECT userid FROM msuserjenis WHERE userjenis = 'admin';");
		$jml = $this->koneksi->mysqlNRows($query);
		while($row = $this->koneksi->mysqlFetch($query))
		{

			$separator = "";
			if($i != 1)
			{
				$separator = ", ";
			}
			if($this->detilLoginAtk($row['userid'], "notifemail", $db) == "Y")
			{
				$emailKe.=$separator.$this->detilLoginAtk($row['userid'], "useremail", $db)."@andhika.com";
			}
			$i++;
		}
		return $emailKe;
	}
	
	function detailNotifEmail($transId)
	{
		$html="";
		$i=1;
		$query = $this->koneksi->mysqlQuery("SELECT * FROM transdtl WHERE transid=".$transId.";");
		$jml = $this->koneksi->mysqlNRows($query);
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$px=0;
			if($i == $jml)
			{
				$px = 1;
			}
			$html.="<tr style=\"font-family:'Arial Narrow';font-size:12px;\" height=\"23\">
						<td width=\"35\" style=\"border-bottom-width:1px;border-top-width:0px;border-left-width:1px;border-right-width:1px;border-color:#CCC;border-style:solid;letter-spacing:0px;\" align=\"center\">
							".$i."
						</td>
						<td width=\"295\" height=\"20\" style=\"border-bottom-width:1px;border-top-width:0px;border-left-width:0;border-right-width:0;border-color:#CCC;border-style:solid;letter-spacing:0px;\" align=\"left\">
							&nbsp;".$this->detilAtkItem("itemname", $row['itemid'])."
						</td>
						<td width=\"70\" style=\"border-bottom-width:1px;border-top-width:0px;border-left-width:1px;border-right-width:1px;border-color:#CCC;border-style:solid;letter-spacing:0px;\" align=\"center\">
							".$row['transqty']." ".$this->detilAtkItem("qtytype", $row['itemid'])."
						</td>
					</tr>";
			$i++;
		}
		
		return $html;
	}
	
	function detailNotifEmailAprvTrans($transId)
	{
		$html="";
		$i=1;
		$query = $this->koneksi->mysqlQuery("SELECT * FROM transdtl WHERE transid=".$transId.";");
		$jml = $this->koneksi->mysqlNRows($query);
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$px=0;
			if($i == $jml)
			{
				$px = 1;
			}
			$html.="<tr style=\"font-family:'Arial Narrow';font-size:12px;\" height=\"23\">
						<td style=\"border-bottom-width:1px;border-top-width:0px;border-left-width:1px;border-right-width:1px;border-color:#CCC;border-style:solid;letter-spacing:0px;\" align=\"center\">
							".$i."
						</td>
						<td height=\"20\" style=\"border-bottom-width:1px;border-top-width:0px;border-left-width:0;border-right-width:0;border-color:#CCC;border-style:solid;letter-spacing:0px;\" align=\"left\">
							&nbsp;".$this->detilAtkItem("itemname", $row['itemid'])."
						</td>
						<td style=\"border-bottom-width:1px;border-top-width:0px;border-left-width:1px;border-right-width:1px;border-color:#CCC;border-style:solid;letter-spacing:0px;\" align=\"center\">
							".$row['transqty']." ".$this->detilAtkItem("qtytype", $row['itemid'])."
						</td>
					</tr>";
			$i++;
		}
		
		return $html;
	}
	
	function detailNotifEmailRequest($jenis, $reqId)
	{
		$itemName = "";
		$qty = "";
		$note = "";
		
		if($jenis == "req")
		{
			$itemName = $this->atkReq("reqname", $reqId);
			$qty = $this->atkReq("reqqty", $reqId)." ".$this->atkReq("qtytype", $reqId);
			$note = "<tr height=\"25\">
					<td valign=\"top\">
						&nbsp;<strong>Note</strong>
					</td>
					<td>
						".$this->atkReq("reqnote", $reqId)."
					</td>
				</tr>";
		}
		if($jenis == "give")
		{
			$itemId = $this->atkReq("giveitemid", $reqId);
			$itemName = $this->detilAtkItem("itemname", $itemId);
			$qty = $this->atkReq("giveqty", $reqId)." ".$this->detilAtkItem("qtytype", $itemId);
		}
		
		$html.="<tr height=\"25\">
					<td width=\"68\" >
						&nbsp;<strong>Unit Name</strong>
					</td>
					<td width=\"332\">
						".$itemName."
					</td>
				</tr>
				<tr height=\"25\">
					<td >
						&nbsp;<strong>Qty</strong>
					</td>
					<td>
						".$qty."
					</td>
				</tr>
				".$note."";
				
		return $html;
	}
	
	function cekLessStock($bln, $thn)
	{
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT itemid FROM stock WHERE stockmonth=".$bln." AND stockyear=".$thn." AND stockall < stockmin;");
		$jml = $this->koneksi->mysqlNRows($query);
	
		return $jml;
	}
	
	function detailNotifEmailLessStock($bln, $thn)
	{
		$html = "";
		$i=1;
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT itemid FROM stock WHERE stockmonth=".$bln." AND stockyear=".$thn." AND stockall < stockmin;");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.="<tr style=\"font-family:'Arial Narrow';font-size:12px;\" height=\"23\">
						<td style=\"border-bottom-width:1px;border-top-width:0px;border-left-width:1px;border-right-width:1px;border-color:#CCC;border-style:solid;letter-spacing:0px;\" align=\"center\">
							".$i."
						</td>
						<td height=\"20\" style=\"border-bottom-width:1px;border-top-width:0px;border-left-width:0;border-right-width:0;border-color:#CCC;border-style:solid;letter-spacing:0px;\" align=\"left\">
							&nbsp;".$this->lastStock("stockname", $row['itemid'], $bln, $thn)."
						</td>
						<td style=\"border-bottom-width:1px;border-top-width:0px;border-left-width:1px;border-right-width:0px;border-color:#CCC;border-style:solid;letter-spacing:0px;\" align=\"center\">
							".$this->lastStock("stockmin", $row['itemid'], $bln, $thn)." ".$this->detilAtkItem("qtytype", $row['itemid'])."
						</td>
						<td style=\"border-bottom-width:1px;border-top-width:0px;border-left-width:1px;border-right-width:1px;border-color:#CCC;border-style:solid;letter-spacing:0px;\" align=\"center\">
							".$this->lastStock("stockall", $row['itemid'], $bln, $thn)." ".$this->detilAtkItem("qtytype", $row['itemid'])."
						</td>
					</tr>";
			$i++;
		}
		
		return $html;
	}
	
	function notesDt($waktuSek)
	{
		$tgl =  substr($waktuSek,8,2);
		$bln =  substr($waktuSek,5,2);
		$thn =  substr($waktuSek,0,4);
		$waktu =  substr($waktuSek,11,8);
		
		return	$bln."/".$tgl."/".$thn." ".$waktu;
	}
	
	function adminEmpNo()
	{
		$adminId = "";
		$this->detilLoginAtk($userId, $field, $DB);
	}
	
	function varNotifDesktop($userId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CLogin, $CNotif, $notes, $db)
	{
		$notesDt = $this->notesDt($CPublic->waktuSek());
		$addUsrdt = $CPublic->userWhoActSqlServ();
		
		if($userId == "")// notif ke admin
		{	
			$query = $this->koneksi->mysqlQuery("SELECT userid FROM msuserjenis WHERE userjenis = 'admin';");
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$adminId = $row['userid'];
				//Notif Desktop ke admin
				if($CLogin->notification($db, "notifdesktop", $adminId) == "Y")
				{
					$notesToAdmin = $this->detilLoginAtk($adminId, "empno", $db);
					$CNotif->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $notesToAdmin, $addUsrdt);
				}
			}
		}
		if($userId != "")// notif ke user
		{
			if($CLogin->notification($db, "notifdesktop", $userId) == "Y")
			{
				$notesToUsr = $this->detilLoginAtk($userId, "empno", $db);
				$CNotif->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $notesToUsr, $addUsrdt);
			}
		}
	}
}
?>