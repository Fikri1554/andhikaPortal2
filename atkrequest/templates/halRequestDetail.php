<?php
require_once('../../config.php');
require_once('../configAtk.php');
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script src="../js/main.js"></script>

<script>

function submitFile()
{
	document.getElementById('errorMsg').innerHTML = "";
	var aksi = formGive.aksi.value;
	var itemId = formGive.itemId.value;
	var qty = formGive.qty.value;
	var stock = document.getElementById('idStock').innerHTML;

	if(itemId.replace(/ /g,"") == "") // paramDateAct tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = "Unit Name still empty";
		document.getElementById('itemId').focus();
		return false;
	}
	
	if(qty.replace(/ /g,"") == "") // qty tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = "Qty empty";
		document.getElementById('qty').focus();
		return false;
	}
	
	if(qty.replace(/ /g,"") == "0") // qty tidak boleh 0
	{
		document.getElementById('errorMsg').innerHTML = "Qty can't be 0";
		document.getElementById('qty').focus();
		return false;
	}
	
	if(parseInt(qty) > parseInt(stock))
	{
		document.getElementById('errorMsg').innerHTML = "Not enough stock !";
		document.getElementById('qty').focus();
		return false;
	}
	
	var answer  = confirm("Are you sure to Give request ?");
	if(answer)
	{
		formGive.submit();
	}
	else
	{ return false;	}
}

function ajaxItemDtOld(id, aksi, halaman)
{
	var mypostrequest=new ajaxRequest()
	mypostrequest.onreadystatechange=function()
	{
		if (mypostrequest.readyState==4)
		{
			if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1)
			{
				document.getElementById(halaman).innerHTML=mypostrequest.responseText;
			}
		}
	}
	
	var parameters="halaman="+aksi+"&itemId="+id;
	
	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}

function ajaxItemDt(id, aksi)
{
	if(aksi == "qtyType")
	{
		$.post( "../halPostFold.php", { halaman:aksi, itemId:id }, function( data )
		{
			$('#idType').html( data );
		});
	}
	if(aksi == "stock")
	{
		$.post( "../halPostFold.php", { halaman:aksi, itemId:id }, function( data )
		{
			$('#idStock').html( data );
			
			setTimeout(function()
			{
				if(data == "0")
				{
					$('#btnSubmit').attr("disabled","disabled");
					$('#btnSubmit').removeClass("btnStandar");
					$('#btnSubmit').addClass("btnStandarDis");
				}
				else
				{
					$('#btnSubmit').attr("disabled","");
					$('#btnSubmit').removeClass("btnStandarDis");
    				$('#btnSubmit').addClass("btnStandar");
				}
			}, 150);
		});
		
		
	}
}
</script>
<?php
$reqIdGet = $_GET['reqId'];

$status = $CReqAtk->atkReq("status", $reqIdGet);
$compDisp = "style=\"display:none;\"";
if($status == "Completed")
{
	$compDisp = "style=\"display:block;\"";
	
	$compDt = $CReqAtk->atkReq("compdate", $reqIdGet);
		$thnComp =  substr($compDt,6,4);
		$blnComp =  substr($compDt,10,2);
		$tglComp =  substr($compDt,12,2);
		$timeComp = substr($compDt,15,8);
	$compDate = $CPublic->bulanSetengah($blnComp, "eng")." ".$tglComp.", ".$thnComp." ".$timeComp;
	
	$giveItemId = $CReqAtk->atkReq("giveitemid", $reqIdGet);
	$giveNm = $CReqAtk->detilAtkItem("itemname", $giveItemId);
	$qtyType = $CReqAtk->detilAtkItem("qtytype", $giveItemId);
	$giveQty = $CReqAtk->atkReq("giveqty", $reqIdGet)." ".$qtyType;
}

if($halamanGet == "read")
{
	$status = $CReqAtk->atkReq("status", $reqIdGet);
	$owner = $CReqAtk->detilLoginAtk($CReqAtk->atkReq("ownerid", $reqIdGet), "userfullnm", $db);
	if($status == "Unread")
	{
		$CKoneksiAtk->mysqlQuery("UPDATE reqnew SET status='Processed' WHERE reqid=".$reqIdGet.";");
		$CHistory->updateLogReqAtk($userIdLogin, "Baca request(reqid = <b>".$reqIdGet."</b>), owner = <b>".$owner."</b>)");
	}
}

if($aksiPost == "give")
{
	$reqIdPost = $_POST['reqId'];
	$itemIdPost = $_POST['itemId'];
	$itemName = $CReqAtk->detilAtkItem("itemname", $itemIdPost);
	$giveQty = $_POST['qty'];
	$ownerId = $CReqAtk->atkReq("ownerid", $reqIdPost);
	$ownerName = $CReqAtk->detilLoginAtk($ownerId, "userfullnm", $db);
	
	$tglReq = $CReqAtk->atkReq("tgl", $reqIdPost);
	$blnReq = $CReqAtk->atkReq("bln", $reqIdPost);
	$thnReq = $CReqAtk->atkReq("thn", $reqIdPost);
	$dateReq = $CPublic->bulanSetengah($blnReq, "eng")." ".$tglReq.", ".$thnReq;
	
	$CKoneksiAtk->mysqlQuery("UPDATE reqnew SET giveitemid=".$itemIdPost.", status='Completed', giveqty=".$giveQty.", compdate='".$CPublic->userWhoAct()."' WHERE reqid=".$reqIdPost."");
	$CKoneksiAtk->mysqlQuery("INSERT INTO cart (ownerid,itemid,itemname,cartqty,reqnew) VALUES (".$ownerId.",".$itemIdPost.",'".$itemName."',".$giveQty.",'Y')");
	$CHistory->updateLogReqAtk($userIdLogin, "Give request(reqid = <b>".$reqIdGet."</b>), owner = <b>".$ownerName."</b>)");
	
	// Notif Email ke user
	if($CLogin->notification($db, "notifemail", $ownerId) == "Y") 
	{
		$emailKeUsr = $CReqAtk->detilLoginAtk($ownerId, "useremail", $db)."@andhika.com";
		$CNotif->emailAprvReq($CReqAtk, $reqIdPost, $dateReq, $emailKeUsr, $link);
	}
	//variabel notif desktop ke user
	$notes = "Your ATK Request New Item at ".$dateReq." has been Available.";
	//Notif Desktop ke user
	$CReqAtk->varNotifDesktop($ownerId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CLogin, $CNotif, $notes, $db);
}
?>

<body>
<table width="99%" cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
	<td colspan="2" bgcolor="#FFFFFF" valign="top" align="center">
	<table cellpadding="0" cellspacing="5" width="100%" class="formInput" border="0">
        
        <?php
		if($halamanGet == "give")
		{
		?>
        <form id="formGive" name="formGive" action="" method="post" enctype="multipart/form-data">
		<tr>
			<td colspan="3">- - - - - - - - - - - - - - - - - - - - - - - - - READY ACTION - - - - - - - - - - - - - - - - - - - - - - -</td>
			<td></td>
		</tr>
		<tr valign="middle">
			<td rowspan="3" align="center">
            	<button onClick="submitFile(); return false;" class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" style="width:80px;height:55px;" title="Submit to Requestor Cart" id="btnSubmit" disabled>
                    <table width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
                        <tr>
                            <td align="center"><img src="../../picture/Floppy-Disk-blue-32.png" height="25"/> </td> 
                        </tr>
                        <tr>
                            <td align="center">SUBMIT</td>
                        </tr>
                        </table>
                   </button>
            </td>
			<td>Unit Name</td>
			<td>
			<select id="itemId" name="itemId" class="elementMenu" style="width:99%;" onChange="ajaxItemDt(this.value, 'qtyType', 'idType');ajaxItemDt(this.value, 'stock', 'idStock');">
				<option value="xxx" selected>-- PLEASE SELECT -- </option>
				<?php
					$sqlName = $CKoneksiAtk->mysqlQuery("SELECT * FROM item WHERE deletests=0 ORDER BY itemname ASC");
					while($rName = $CKoneksiAtk->mysqlFetch($sqlName))
					{
						echo "<option value=\"".$rName['itemid']."\">".$rName['itemname']."</option>";
					}
				?>
			</select>
			</td>
			<td></td>
		</tr>
		<tr valign="top">
			<td>Qty</td>
			<td>
			<input type="text" class="elementDefault" id="qty" name="qty" style="width:10%;height:28px;" value="<?php echo $CReqAtk->atkReq("reqqty", $reqIdGet)?>">
            <span id="idType">. . .</span>
			</td>
			<td></td>
		</tr>
        <tr valign="top">
        	<td>Stock</td>
            <td>
            <span id="idStock">. . .</span> Available
            </td>
            <td>
            </td>
        </tr>
        <tr>
			<td colspan="3" height="20" align="center" valign="middle" style="border-top:dashed 1px #000000;">&nbsp; 
			<span id="errorMsg" class="errorMsg"></span>
			<span id="report" class="report"></span>
			</td>
            <td>
            	<input type="hidden" id="aksi" name="aksi" value="give"/>
            	<input type="hidden" id="reqId" name="reqId" value="<?php echo $reqIdGet;?>"/>
            </td>
		</tr>
        </form>
		<?php } ?>
        
<!-- Start of complete detail ========================================================================================= -->
        <tr id="trComplete" <?php echo $compDisp;?>>
        <td colspan="4">
       	<table width="100%" cellpadding="0" cellspacing="5" border="0" class="formInput">
        	<tr>
			<td colspan="3">- - - - - - - - - - - - - - - - - - - - - - -  COMPLETE DETAIL - - - - - - - - - - - - - - - - - - - - -</td>
			<td width="0%"></td>
		</tr>
		<tr valign="top">
			<td width="32%" rowspan="3" align="center">
            	<img src="../../picture/symbol_check.png" width="80"/>
            </td>
            <td width="14%"><strong>Date</strong></td>
			<td width="53%" style="color: #00F;">
				<?php echo $compDate;?>
			</td>
			<td></td>
        </tr>
        <tr valign="top">
			<td width="14%"><strong>Unit Name</strong></td>
			<td width="53%">
				<?php echo $giveNm;?>
			</td>
			<td></td>
		</tr>
		<tr valign="top">
			<td><strong>Qty</strong></td>
			<td>
				<?php echo $giveQty;?>
			</td>
			<td></td>
		</tr>
        <tr height="5px">
        	<td colspan="3" style="border-top:dashed 1px #000000;">&nbsp;</td>
            <td></td>
        </tr>
        </table>    
        </td>
        </tr>
<!-- End of complete detail ============================================================================================ -->
<?php
$kurangStock = 0;
$query = $CKoneksiAtk->mysqlQuery("SELECT * FROM reqnew WHERE reqid=".$reqIdGet.";");
while($row = $CKoneksiAtk->mysqlFetch($query))
{
	$itemId = $row['itemid'];
	$detilId = $row['detilid'];
	
	/*if($adminAtk == "Y")
	{
		$stock = $CReqAtk->lastStock("stockAll", $itemId, $bln, $thn);
		if($stock == "")
		{
			$stock = "0";
		}
		
		$editQty = $row['aprvqty'];
		if($editQty == "")
		{
			$editQty = $row['transqty'];
		}
		$bgColor = "";
		if($stock < $editQty)
		{
			$bgColor = "style=\"background-color:#FF464A;\"";
			$kurangStock = $kurangStock + 1;
		}
	}*/
?>        
        <tr>
            <td width="22%" rowspan="4" align="center">
                <img src="../requestPict/<?php echo $row['reqimg'];?>" width="150" height="130"/>
            </td>
            <td height="28" width="12%"><strong>Requestor</strong></td>
            <td>
            <?php echo $CReqAtk->detilLoginAtk($row['ownerid'], "userfullnm", $db);?>
            </td>
            <td></td>
        </tr>
        <tr>
            <td><strong>Unit Name</strong></td>
            <td>
            <?php echo $row['reqname'];?>
            </td>
            <td>
            </td>
        </tr>
        <tr>
            <td><strong>Qty</strong></td>
            <td>
            <?php echo $row['reqqty']." ".$row['qtytype'];?>
            </td>
            <td></td>
        </tr>
        <tr valign="top">
            <td height="28"><strong>Note</strong></td>
            <td>
                <?php echo $row['reqnote'];?>
            </td>
            <td></td>
        </tr>
<?php } ?>        
		</table>
		</td>
	</tr>
</table>
</body>
<script>
<?php
if($aksiPost == "give")
{
	echo "parent.reqListClick();
		  parent.report('Give processed Succesfully execute');";
}
?> 
</script>
