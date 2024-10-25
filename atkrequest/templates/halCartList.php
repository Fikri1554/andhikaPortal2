<!DOCTYPE HTML>
<?php
require_once("../../config.php");
require_once("../configAtk.php");

$class = "class=\"btnStandarKecil\"";
$dis = "";
if($adminAtk == "Y")
{
	$class = "class=\"btnStandarKecilDis\"";
	$dis = "disabled";
}

if($aksiGet == "delete")
{
	$itemIdGet = $_GET['itemId'];

	$query = $CKoneksiAtk->mysqlQuery("SELECT reqnew FROM cart WHERE itemid = '".$itemIdGet."'");
	$row = $CKoneksiAtk->mysqlFetch($query);
	
	$CKoneksiAtk->mysqlQuery("DELETE FROM cart WHERE ownerid=".$userIdLogin." AND itemid=".$itemIdGet.";");
	if($row['reqnew'] == "Y")
	{
		$CHistory->updateLogReqAtk($userIdLogin, "Membatalkan order Request New Item yang telah diproses Administrator(itemid = <b>".$itemIdGet."</b>)");
	}
}

if($aksiPost == "hitungQty")
{
	$itemIdPost = $_POST['itemId'];
	$qtyCountPost = $_POST['qtyCount'];
	
	$query = $CKoneksiAtk->mysqlQuery("UPDATE cart SET cartqty=".$qtyCountPost." WHERE ownerid=".$userIdLogin." AND itemid=".$itemIdPost.";");
}

if($halamanPost == "submit")
{
	$date = str_replace("-","/",$CPublic->waktuSek());
	$tgl =  substr($date,8,2);
	$bln =  substr($date,5,2);
	$thn =  substr($date,0,4);
	$waktu =  substr($date,11,8);
	$notes = mysql_real_escape_string($_POST['notes']);
	
	$CKoneksiAtk->mysqlQuery("INSERT INTO trans (ownerid, tgl, bln, thn, waktu, notes) VALUES (".$userIdLogin.", '".$tgl."', '".$bln."', '".$thn."', '".$waktu."', '".$notes."');");
	$lastInsertId = mysql_insert_id();
	
	$CHistory->updateLogReqAtk($userIdLogin, "Submit Cart to Transaction (transid = <b>".$lastInsertId."</b>)");
	
	$sqlCart = $CKoneksiAtk->mysqlQuery("SELECT itemid,cartqty FROM cart WHERE ownerid=".$userIdLogin.";");
	while($rCart = $CKoneksiAtk->mysqlFetch($sqlCart))
	{
		$CKoneksiAtk->mysqlQuery("INSERT INTO transdtl (transid,itemid,transqty) VALUES (".$lastInsertId.",".$rCart['itemid'].",".$rCart['cartqty'].");");
		
		$CKoneksiAtk->mysqlQuery("DELETE FROM cart WHERE ownerid=".$userIdLogin." AND itemid=".$rCart['itemid'].";");
	}
	
// ========= NOTIFIKASI ===================================================================================================	
	//notif email ke Admin
	$emailKe = $CReqAtk->emailKe($db);
	if($emailKe != "")
	{	
		$CNotif->emailSubmitTrans($CReqAtk, $lastInsertId, $emailKe, $db, $link);	
	}
	
	//variabel notif desktop ke admin
	$user =$CReqAtk->detilLoginAtk($userIdLogin,"userfullnm",$db);
	$jmlItem = $CReqAtk->jmlTrans($lastInsertId);
	$s = "";
	if($jmlItem > 1)
	{
		$s = "s";
	}
	$notes = $user." has ordered ".$jmlItem." item".$s." on ATK Request. Please, check Andhika Portal.";
	//Notif Desktop ke admin
	$CReqAtk->varNotifDesktop("", $koneksiOdbc, $koneksiOdbcId, $CPublic, $CLogin, $CNotif, $notes, $db);
	 
	/*$user =$CReqAtk->detilLoginAtk($userIdLogin,"userfullnm",$db);
	$jmlItem = $CReqAtk->jmlTrans($lastInsertId);
	$s = "";
	if($jmlItem > 1)
	{
		$s = "s";
	}
	
	$notesDt = $CReqAtk->notesDt($CPublic->waktuSek()); 
	$notes = $user." has ordered ".$jmlItem." item".$s." on ATK Request"; 
	$addUsrdt = $CReqAtk->addUsrdtNotif($userIdLogin, $CPublic->waktuSek(), $db);
		
	$queryAdmin = $CKoneksiAtk->mysqlQuery("SELECT userid FROM msuserjenis WHERE userjenis = 'admin';");
	while($rAdmin = $CKoneksiAtk->mysqlFetch($queryAdmin))
	{
		$adminId = $rAdmin['userid'];
		//Notif Desktop ke admin
		if($CLogin->notification($db, "notifdesktop", $adminId) == "Y")
		{
			$notesTo = $CReqAtk->detilLoginAtk($adminId, "empno", $db);
			$CNotif->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $notesTo, $addUsrdt);
			//echo "<br/><br/>".$notesDt."<br/>".$notes."<br/>".$notesTo."<br/>".$addUsrdt;
		}
	}*/
}
?>
<script type="text/javascript">
window.onscroll = 
function ()
{
	//document.getElementById('fix').style.top = (document.pageYOffset?document.pageYOffset:document.body.scrollTop);
}
</script>

<link href="../../css/main.css" rel="stylesheet" type="text/css"/>
<link href="../../css/archives.css" rel="stylesheet" type="text/css"/>
<link href="../../css/atkRequest.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../../js/animatedcollapse.js"></script>
<script type="text/javascript" src="../../js/masks.js"></script>
<script>
$(document).ready(function()
{
	parent.refreshHeigthDiv();
});

function init(qty, id, qtyReq)
{
	//fungsi hanya angka
	hpMask = new Mask("", "number");
	hpMask.attach(document.getElementById(qtyReq));
	
	document.getElementById('qtyCount').value = qty;
	
	qtyInput(id, qtyReq);
}

function qty(jumlah,qtyReq,id)
{
	var qtyNow = document.getElementById(qtyReq).value;

	if(qtyNow == "")
	{
		qtyNow = "0";
	}
	//alert(qtyNow);
	if(jumlah == "tambah")
	{
		if(qtyNow < 999)
		{
			var qtyJumlah = parseInt(qtyNow)+1 ;
		}
		if(qtyNow >= 999)
		{
			return false;
		}
	}
	if(jumlah == "kurang")
	{
		if(qtyNow == 0)
		{
			var qtyJumlah = qtyNow ;
		}
		if(qtyNow != 0)
		{
			var qtyJumlah = parseInt(qtyNow)-1 ;
		}
	}
	document.getElementById(qtyReq).value = qtyJumlah;
	document.getElementById('qtyCount').value = qtyJumlah;
	qtyInput(id,qtyReq);
}

function qtyInput(id,qtyReq)
{
	var qtyNow = document.getElementById(qtyReq).value;
	document.getElementById('itemId').value = id;
	if(qtyNow != "")
	{
		formCartQty.submit();
	}
}

function submitCart()
{
	var cek = formSubmit.cek.value;
	
	if(cek == 1)
	{
		document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;You don't have item in cart";
		return false;
	}
	else
	{
		parent.thickboxNote();
	}
	/*var answer = confirm('Are you sure want to submit cart to transaction ?');
	if(answer)
	{
		formSubmit.submit();
	}
	else
	{
		return false;
	}*/
}
function report()
{
	document.getElementById('report').innerHTML = "&nbsp;* Item Cart succesfully submit";
	
	setTimeout(function()
	{
		document.getElementById('report').innerHTML = "";
	},10000);
}

function submitOrder(notes)
{
	$('#notes').val(notes);
	formSubmit.submit();
}

window.onload = 
function() 
{
	loadScroll('halCartListv');
}
</script>
<body onUnload="saveScroll('halCartListv');">    
<table id="judul" cellpadding="0" cellspacing="0" width="100%" border="0" style="background-color:#666;color:#EFEFEF;font-family:Arial;font-weight:bold;font-size:13px;position:fixed;left:0px;top:0px;">
<tr>
    <td height="30" width="19%" align="right">Qty</td>
    <td width="48%" align="center">Cart List</td>
    <td width="33%" align="right" style="position:relative;">
    
        <!--<button type="submit" id="btnAdd" style="height:25px;width:83px;border-radius:4px;margin-top:1px;" <?php echo $class;?> onClick="submitCart(); return false;" title="Submit Cart" <?php echo $dis; ?>>-->
       <!--<button type="button" id="btnAdd" style="height:25px;width:83px;border-radius:4px;margin-right:3px;margin-top:1px;"  <?php echo $class;?> onClick="submitCart();" title="Submit Cart" <?php echo $dis; ?>>
            <table id="tblAdd" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr>
                    <td align="center"><img src="../../picture/Shopping-Cart-blue-32.png"/ height="18px" style="vertical-align:middle;">&nbsp;</td>
                    <td align="center">Submit</td>
                </tr>
            </table>
        </button>-->
        <div style="position:absolute;top:2px;right:2px;">
            <button <?php echo $class;?> id="btnAdd" onClick="submitCart();" title="Submit Cart" <?php echo $dis; ?>>
            <table id="tblAdd" height="22" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" width="20"><img src="../picture/truck.png" style="vertical-align:middle;"></td>
                    <td align="left">Submit&nbsp;</td>
                </tr>
            </table>
            </button>
        </div>
    </td>                
</tr>
</table>
            
<table width="100%" height="100%" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0" style="margin-top:30px;">
<form action="" id="formCartQty" name="formCartQty" method="post">
<input type="hidden" id="aksi" name="aksi" value="hitungQty"/>
<input type="hidden" id="itemId" name="itemId"/>
<input type="hidden" id="qtyCount" name="qtyCount"/>
</form>
<form action="" method="post" id="formSubmit" name="formSubmit">
<input type="hidden" id="halaman" name="halaman" value="submit"/>
<input type="hidden" id="notes" name="notes" value="submit"/>
	<!--<tr>
    	<td height="8px">
        <!--<div id="fix" style="position:absolute;top:0px;left:0px;background-color:#666;height:27px;width:100%;">-->
      <!--  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#666;">
        	<tr>
            	<td width="19%" align="right" style="color:#EFEFEF;font-family:Arial;font-size:13px;font-weight:bold;">Qty</td>
                <td width="48%" align="center" style="color:#EFEFEF;font-family:Arial;font-size:13px;font-weight:bold;">Cart List</td>
            	<td width="33%" align="right" valign="top">
                
                    <!--<button type="submit" id="btnAdd" style="height:25px;width:83px;border-radius:4px;margin-top:1px;" <?php echo $class;?> onClick="submitCart(); return false;" title="Submit Cart" <?php echo $dis; ?>>-->
                    
      <!--              <button type="button" id="btnAdd" style="height:25px;width:83px;border-radius:4px;margin-top:1px;" <?php echo $class;?> onClick="submitCart();" title="Submit Cart" <?php echo $dis; ?>>
                        <table id="tblAdd" cellpadding="0" cellspacing="0" width="100%" height="100%">
                            <tr>
                                <td align="center"><img src="../../picture/Shopping-Cart-blue-32.png"/ height="18px" style="vertical-align:middle;">&nbsp;</td>
                                <td align="center">Submit</td>
                            </tr>
                        </table>
                    </button>&nbsp;
                </td>                
            </tr>
        </table>-->
        <!--</div>
        </td>
    </tr>-->
    
    <div style="position:fixed;top:27px;">
    <span id="errorMsg" class="errorMsg"></span><span id="report" class="report"></span>
    </div>
    
    <tr>
    <td>
    <div id="divCart" style="height:350px;">
    <table width="100%">
<?php
	$i = 1;
	$query = $CKoneksiAtk->mysqlQuery("SELECT * FROM cart WHERE ownerid=".$userIdLogin."");
	while($row= $CKoneksiAtk->mysqlFetch($query))
	{
		$itemName = $row['itemname'];
?>
    <tr id="<?php //echo $i ?>" onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" height="26" title="<?php echo $itemName; ?>">
        <td colspan="2" class="tdMyFolder">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
            	<td width="2%">
                 	<img src="../picture/plus-button.png" onMouseOver="this.style.backgroundColor='#1582f5';" onMouseOut="this.style.backgroundColor='transparent';" onClick="qty('tambah','qtyReq<?php echo $i ?>','<?php echo $row['itemid'];?>');" title="Add Qty"/>
                    <img src="../picture/minus-button.png" onMouseOver="this.style.backgroundColor='#1582f5';" onMouseOut="this.style.backgroundColor='transparent';" onClick="qty('kurang','qtyReq<?php echo $i ?>','<?php echo $row['itemid'];?>');" title="Subtract Qty"/>
                </td>
                <td style="font-size:13px;color:#009;font-weight:bold;font-family:Tahoma;" width="17%" align="center">
                    <input type="text" id="qtyReq<?php echo $i ?>" class="elementDefault" style="width:15px;height:25;text-align:center;" onChange="init(this.value,'<?php echo $row['itemid'];?>','qtyReq<?php echo $i ?>');" value="<?php echo $row['cartqty'];?>" maxlength="3">
                </td>
                <td width="13%" align="left" style="font-size:12px;font-family:Arial Narrow;">
                    <?php echo $CReqAtk->detilAtkItem("qtytype", $row['itemid']);?>
                </td>
                <td width="64%" onClick="parent.imgThickbox('<?php echo $row['itemid'];?>','<?php echo $row['cartqty']." ".$CReqAtk->detilAtkItem("qtytype", $row['itemid']);?>');">
                    <span style="font-size:14px;color:#009;font-weight:bold;font-family:Arial Narrow;">
                    <?php echo $CPublic->potongKarakter($itemName, "22"); ?>
                    </span>
                </td>
                <td rowspan="2" width="4%">
                	<img src="../picture/cross.png" onClick="parent.deleteCart('<?php echo $row['itemid'];?>');" onMouseOver="this.style.backgroundColor='#FF888B';" onMouseOut="this.style.backgroundColor='transparent';" style="vertical-align:middle;" title="Delete Cart Item"/>
                </td>
            </tr>
        </table>
        </td>
    </tr>
    <?php $i++;} ?>
    </table>
    </div>
    </td>
    </tr>
<input type="hidden" id="cek" name="cek" value="<?php echo $i;?>"/>
</form>

</table>

<?php
if($aksiGet == "notifDekstop")
{
	$CNotif->notifDesktop($koneksiOdbc, $koneksiOdbcId);
	/*$notesDt = "01/29/2015 15:10:45"; // bln/tgl/thn
	$notes = "TES NOTIFIKASI";
	$notesFrom = "00000";
	$notesTo = "00879";
	$addUsrdt = ""; //SGI#15:30#15/01/2015
	$query = $koneksiOdbc->odbcExec($koneksiOdbcId, "INSERT INTO HRsys..tblRemindMe (notesdt, notes, notesfrom, notesto, addusrdt) VALUES ('".$notesDt."', '".$notes."', '".$notesFrom."', '".$notesTo."', '".$addUsrdt."');");*/
}
?>
</body>
<script language="javascript">
<?php
if($halamanPost == "submit")
{
	echo "report();";
}
?>
</script>
</HTML>