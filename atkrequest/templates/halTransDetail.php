<?php
require_once('../../config.php');
require_once('../configAtk.php');

$transIdGet = $_GET['transId'];
$notes = $CReqAtk->atkTrans("notes", $transIdGet);

$tglServer = $CPublic->tglServer();
$bln =  substr($tglServer,4,2);
$thn =  substr($tglServer,0,4);

if($halamanGet == "read")
{
	$status = $CReqAtk->atkTrans("status", $transIdGet);
	$owner = $CReqAtk->detilLoginAtk($CReqAtk->atkTrans("ownerid", $transIdGet), "userfullnm", $db);
	
	if($status == "Unread")
	{
		$CKoneksiAtk->mysqlQuery("UPDATE trans SET status='Processed' WHERE transid=".$transIdGet.";");
		$CHistory->updateLogReqAtk($userIdLogin, "Baca transaksi(transid = <b>".$transIdGet."</b>), owner = <b>".$owner."</b>)");
	}
}

if($halamanGet == "calcQty")
{
	$detilIdGet = $_GET['detilId'];
	$editQtyGet = $_GET['editQty'];
	//echo $detilIdGet." | ".$editQtyGet;
	$CKoneksiAtk->mysqlQuery("UPDATE transdtl SET aprvqty=".$editQtyGet." WHERE detilid=".$detilIdGet.";");
}

if($halamanGet == "calcRefund")
{
	$detilIdGet = $_GET['detilId'];
	$itemId = $CReqAtk->atkTransDtl("itemid", $detilIdGet);
	$rfndQtyGet = mysql_real_escape_string($_GET['rfndQty']);
	$aprvQtyBefore = $CReqAtk->atkTransDtl("aprvqty", $detilIdGet);
	$aprvQtyUpd = $aprvQtyBefore - $rfndQtyGet;
	$noteGet = mysql_real_escape_string($_GET['note']);
	//echo $detilIdGet." | ".$rfndQtyGet." | ".$noteGet." | ".$aprvQtyBefore." | ".$aprvQtyUpd." | ".$itemId;
	$CKoneksiAtk->mysqlQuery("UPDATE transdtl SET aprvqty=".$aprvQtyUpd.",rfndqty=".$rfndQtyGet.", rfndnote = '".$noteGet."' WHERE detilid=".$detilIdGet.";");
	transRefund($CKoneksiAtk, $bln, $thn, $itemId, $rfndQtyGet);
}
function transRefund($CKoneksiAtk, $bln, $thn, $itemId, $rfndQty)
{
	$sql = $CKoneksiAtk->mysqlQuery("SELECT stockid,stockout,stockall FROM stock WHERE itemid=".$itemId." AND stockmonth='".$bln."' AND stockyear=".$thn.";");
	while($r = $CKoneksiAtk->mysqlFetch($sql))
	{
		$stockOutInp = $r['stockout']-$rfndQty;
		$stockAllInp = $r['stockall']+$rfndQty;

		$CKoneksiAtk->mysqlQuery("UPDATE stock SET stockout=".$stockOutInp.", stockall=".$stockAllInp." WHERE stockid=".$r['stockid']." AND active = 'Y';");
	}
}

$disp = "none";
$status = $CReqAtk->atkTrans("status", $transIdGet);
$aprvDt = $CReqAtk->atkTrans("aprvdate", $transIdGet);
	$thnAprv =  substr($aprvDt,6,4);
	$blnAprv =  substr($aprvDt,10,2);
	$tglAprv =  substr($aprvDt,12,2);
	$timeAprv = substr($aprvDt,15,8);
$aprvDate = $CPublic->bulanSetengah($blnAprv, "eng")." ".$tglAprv.", ".$thnAprv." ".$timeAprv;

$rfndDt = $CReqAtk->atkTrans("rfnddate", $transIdGet);
	$thnRfnd =  substr($rfndDt,6,4);
	$blnRfnd =  substr($rfndDt,10,2);
	$tglRfnd =  substr($rfndDt,12,2);
	$timeRfnd = substr($rfndDt,15,8);
$rfndDate = $CPublic->bulanSetengah($blnRfnd, "eng")." ".$tglRfnd.", ".$thnRfnd." ".$timeRfnd;

$compDt = $CReqAtk->atkTrans("compdate", $transIdGet);
	$thnComp =  substr($compDt,6,4);
	$blnComp =  substr($compDt,10,2);
	$tglComp =  substr($compDt,12,2);
	$timeComp = substr($compDt,15,8);
$compDate = $CPublic->bulanSetengah($blnComp, "eng")." ".$tglComp.", ".$thnComp." ".$timeComp;
if($status == "Approved" || $status == "Completed" || $status == "Refund")
{
	$disp = "block";
	$trRefund = "";
	$trComplete = "";
	if($status == "Approved")
	{
		$img = "thumbs_up_48.png";
	}
	if($status == "Refund")
	{
		$img = "undo_48.png";
	}
	if($status == "Completed")
	{
		$img = "symbol_check.png";
		$trComplete = "<tr>
						<td><strong>Completed Date</strong></td>
						<td style=\"color: #00F;\">
							".$compDate."
						</td>
						<td></td>
						</tr>";
	}
	if($CReqAtk->atkTrans("rfnddate", $transIdGet) != "")
	{
		$trRefund = "<tr>
						<td><strong>Refund Date</strong></td>
						<td style=\"color: #00F;\">
							".$rfndDate."
						</td>
						<td></td>
						</tr>";
	}
}
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/main.js"></script>
<script>
function completed(status)
{
	var admAtk = document.getElementById('admAtk').value;
	if(admAtk == "Y")
	{
		if(status == "lebih")
		{
			parent.document.getElementById('btnApprv').disabled = false;
			parent.document.getElementById('btnApprv').className = 'btnStandar';
			parent.document.getElementById('btnApprv').onmouseover = function onmouseover(){
				this.className='btnStandar';}
			parent.document.getElementById('btnApprv').onmouseout = function onmouseout(){
				this.className='btnStandar';}
		}
		if(status == "kurang")
		{
			parent.document.getElementById('btnApprv').disabled = true;
			parent.document.getElementById('btnApprv').className = 'btnStandarDisabled';
			parent.document.getElementById('btnApprv').onmouseover = function onmouseover(){
				this.className='btnStandarDisabled';}
			parent.document.getElementById('btnApprv').onmouseout = function onmouseout(){
				this.className='btnStandarDisabled';}
		}
	}
}

function cekQty(qty)
{
	/*alert(parseInt(stock));
	alert(parseInt(qty));*/
	var stock = document.getElementById('stock').innerHTML;
	if(parseInt(stock) >= parseInt(qty))
	{
		document.getElementById('td2').style.backgroundColor = "";
		document.getElementById('td2').onmouseover = function onmouseover(){
			document.getElementById('td2').style.backgroundColor = "#DDF0FF";
		}
		document.getElementById('td2').onmouseout = function onmouseout(){
			document.getElementById('td2').style.backgroundColor = "#FFFFFF";
		}
	}
	/*if(parseInt(qty) == 0)
	{
		document.getElementById('td2').style.backgroundColor = "#F00000";
		document.getElementById('td2').onmouseout = function onmouseout(){
			document.getElementById('td2').style.backgroundColor = "#F00000";
		}
	}*/
	if(parseInt(stock) - parseInt(qty) < 0)
	{
		document.getElementById('td2').style.backgroundColor = "#FF464A";
		document.getElementById('td2').onmouseover = function onmouseover(){
			document.getElementById('td2').style.backgroundColor = "#FF464A";
		}
		document.getElementById('td2').onmouseout = function onmouseout(){
			document.getElementById('td2').style.backgroundColor = "#FF464A";
		}
	}
	
	completed();
}

function btnAprv()
{
	var admAtk = document.getElementById('admAtk').value;
	var status = document.getElementById('status').value;
	if(admAtk == "Y")
	{
		if(status == "Approved" || status == "Completed")
		{
		parent.document.getElementById('btnApprv').disabled = true;
		parent.document.getElementById('btnApprv').className = 'btnStandarDisabled';
		parent.document.getElementById('btnApprv').onmouseover = function onmouseover(){
			this.className= 'btnStandarDisabled';}
		parent.document.getElementById('btnApprv').onmouseout = function onmouseout(){
			this.className= 'btnStandarDisabled';}
		}
	}
}

function submitRefund(transId, detilId)
{
	document.getElementById('errorMsg'+detilId).innerHTML = "";
	rfndQty = document.getElementById('editQty'+detilId).value;
	note = document.getElementById('note'+detilId).value;
	receiveQty = document.getElementById('receiveQty'+detilId).value;
	//alert(rfndQty+" | "+note+" | "+receiveQty);
	if(rfndQty.replace(/ /g,"") == "") // paramDateAct tidak boleh kosong
	{
		document.getElementById('errorMsg'+detilId).innerHTML = "Refund Qty still empty";
		document.getElementById('editQty'+detilId).focus();
		return false;
	}
	if(rfndQty > receiveQty)
	{
		document.getElementById('errorMsg'+detilId).innerHTML = "Refund must < Received Qty";
		return false;
	}
	else
	{
		parent.calcRefund(transId, detilId, rfndQty, note);
	}
}
</script>
<body onLoad="btnAprv();loadScroll('transDt');" onUnload="saveScroll('transDt');">
<table width="99%" cellpadding="0" cellspacing="0" border="0">
<input type="hidden" id="admAtk" name="admAtk" value="<?php echo $adminAtk;?>">
<input type="hidden" id="status" name="status" value="<?php echo $status;?>">
<tr valign="top">
	<td colspan="2" bgcolor="#FFFFFF" valign="top" align="center">
	<table cellpadding="0" cellspacing="5" width="100%" border="0">
<!-- Start of Status detail ========================================================================================= -->        
        <tr id="trAprv" style="display:<?php echo $disp;?>;">
        <td colspan="4">
       	<table width="100%" cellpadding="0" cellspacing="5" border="0" class="formInput">
        	<tr>
			<td colspan="3" align="center">- - - - - - - - - - - - - - - - - - - - - - - - STATUS DETAIL - - - - - - - - - -  - - - - - - - - - - -</td>
			<td width="1%"></td>
		</tr>
<tr valign="top">
<td colspan="4">
<table width="100%" border="0" class="fontMyFolderList">
	<td width="22%" align="center">
    	<img src="../../picture/<?php echo $img;?>" width="60"/>
    </td>
    <td width="78%">
    	<table width="100%" border="0" class="fontMyFolderList">
        	<tr>
            	<td width="30%"><strong>Approved Date</strong></td>
                <td width="70%" style="color: #00F;"><?php echo $aprvDate;?></td>
            </tr>
            <?php echo $trRefund.$trComplete;?>
        </table>
    </td>
</table>
</td>
</tr>
		<!--<tr valign="middle">
			<td width="22%" rowspan="3" align="center">
            	<img src="../../picture/<?php echo $img;?>" width="60"/>
            </td>
            <td width="22%"><strong>Approved Date</strong></td>
			<td width="55%" style="color: #00F;">
				<?php echo $aprvDate;?>
			</td>
			<td></td>
        </tr>
        <?php echo $trComplete;?>
        <tr>
			<td>&nbsp;</td>
			<td>
			</td>
			<td></td>
		</tr>-->
        <tr height="5px">
        	<td colspan="3" style="border-top:dashed 1px #000000;">&nbsp;</td>
            <td width="0%"></td>
        </tr>
        </table>    
        </td>
        </tr>
<!-- End of Status detail ============================================================================================ -->

<!-- Start notes ===================================================================================================== -->
<?php
	if($notes != "")
	{
?>
<tr valign="top">
<td colspan="4">
    <table width="100%" border="0" class="fontMyFolderList">
    	<tr>
        	<td width="12%"><b>* Notes</b></td>
            <td>
	<?php
        echo $notes;
    ?>
            </td>
        </tr>
    </table>
</td>
</tr>
<?php } ?>
<!-- End notes ======================================================================================================= -->

<?php
$kurangStock = 0;
$query = $CKoneksiAtk->mysqlQuery("SELECT * FROM transdtl WHERE transid=".$transIdGet.";");
while($row = $CKoneksiAtk->mysqlFetch($query))
{
	$itemId = $row['itemid'];
	$detilId = $row['detilid'];
	
	if($adminAtk == "Y")
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
		if($status == "Unread" || $status == "Processed")
		{
		if($stock < $editQty)
		{
			$bgColor = "style=\"background-color:#FF464A;\"";
			$kurangStock = $kurangStock + 1;
		}
		}
	}
?>

<tr valign="top">
<td colspan="4" class="tdMyFolder" onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
<table width="100%" border="0" class="fontMyFolderList">
	<tr>
    	<td width="5%" valign="top"<?php echo $bgColor; ?>>
        <!-- td warna warning -->
        </td>
        
        <td width="17%" valign="top">
        <!-- td gambar -->
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fontMyFolderList">
        	<tr>
            	<td>
                	&nbsp;<img src="../picture/<?php echo $CReqAtk->detilAtkItem("itemimg", $itemId); ?>"/ width="90" height="78">
                </td>
            </tr>
        </table>
        </td>
        
        <td width="78%" valign="top">
        <!-- td detail -->
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fontMyFolderList">
        	<tr height="25">
            	<td>
                    <strong>Item Name</strong> 
                </td>
                <td width="75%" colspan="2">
                    <?php echo $CReqAtk->detilAtkItem("itemname", $itemId);?>
                </td>
            </tr>
            <tr height="25">
            	<td>
                    <strong>Request Qty</strong> 
                </td>
                <td>
                    <?php 
                        echo $row['transqty']." ".$CReqAtk->detilAtkItem("qtytype", $itemId); 
                    ?>
                </td>
                <td align="right">
                <?php
                if(($adminAtk == "Y" && $status == "Unread") || ($adminAtk == "Y" && $status == "Processed") || ($adminAtk == "Y" && $aksiGet == "refund"))
                {
                    echo "( ".$stock." ".$CReqAtk->detilAtkItem("qtytype", $itemId)." Available)&nbsp";
                }
				if($row['rfndqty'] != "")
				{
					echo "<span style=\"color:#009;\">* ".$row['rfndqty']." Refund &nbsp;</span>"; 
				}
                ?></td>
            </tr>
		<?php
		if($status == "Approved" || $status == "Completed" || $status == "Refund")
		{
		?>
            <tr height="25">
            	<td>
                    <strong>Received Qty</strong>
                </td>
                <td colspan="2">
                     <?php echo $row['aprvqty']." ".$CReqAtk->detilAtkItem("qtytype", $itemId);?>
                     <input type="hidden" id="receiveQty<?php echo $detilId;?>" value="<?php echo $row['aprvqty'];?>"/>
                </td>
                <td>
                </td>
            </tr>
		<?php
		}
        if($adminAtk == "Y" && $status == "Unread" || $adminAtk == "Y" && $status == "Processed")
        {
        ?>
            <tr>
            	<td>
                    <strong>Edit Qty</strong>
                </td>
                <td colspan="2">
                     <input type="text" class="elementDefault" id="editQty<?php echo $detilId;?>" name="nmUnit" style="width:30;height:24px;" value="<?php echo $editQty;?>"> <?php echo $CReqAtk->detilAtkItem("qtytype", $itemId);?>
                         &nbsp;&nbsp;
                         <button id="btnCancel" style="width:80px;height:24px;" class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover';" onMouseOut="this.className='btnStandarKecil';" onClick="parent.calculate('<?php echo $transIdGet;?>','<?php echo $detilId;?>',document.getElementById('editQty'+<?php echo $detilId;?>).value);" title="Cancel Edit Master">
                            <table id="tblAdd" cellpadding="0" cellspacing="0" width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
                                <tr valign="middle">
                                    <td align="center"><img src="../../picture/Button-Synchronize-blue-32.png"/ height="18px" style="vertical-align:middle;"></td>
                                    <td align="center">Calculate</td>
                                </tr>
                            </table>
                        </button>
                </td>
            </tr>
		<?php 
		}
		if($adminAtk == "Y" && $status == "Refund")
		{
		?>
        	<tr>
            	<td>
                    <strong>Refund Qty</strong>
                </td>
                <td colspan="2">
                     <input type="text" class="elementDefault" id="editQty<?php echo $detilId;?>" name="nmUnit" style="width:30;height:24px;" value="<?php echo $row['rfndqty'];?>"> <?php echo $CReqAtk->detilAtkItem("qtytype", $itemId);?>
                </td>
            </tr>
            
        	<tr height="4"><td colspan="3"></td></tr>
            <tr height="25">
            	<td valign="top">
                    <strong>Refund Note</strong>
                </td>
                <td colspan="2">
                     <textarea class="elementDefault" id="note<?php echo $detilId;?>" name="note<?php echo $detilId;?>" style="width:100%;height:50px;"><?php echo $row['rfndnote'];?></textarea>
                </td>
                <td>
                </td>
            </tr>
            
            <tr height="25">
            	<td>&nbsp;</td>
                <td colspan="2">
                	<button id="btnCancel" style="width:70px;height:24px;" class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover';" onMouseOut="this.className='btnStandarKecil';" onClick="submitRefund('<?php echo $transIdGet;?>','<?php echo $detilId;?>');return false;" title="Cancel Edit Master">
                            <table id="tblAdd" cellpadding="0" cellspacing="0" width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
                                <tr valign="middle">
                                    <td align="center"><img src="../../picture/Button-Rotate-Cw-blue-32.png"/ height="18px" style="vertical-align:middle;"></td>
                                    <td align="center">Submit</td>
                                </tr>
                            </table>
                        </button>
                        <span id="errorMsg<?php echo $detilId;?>" class="errorMsg" style="font-size:12px;"></span>
                </td>
            </tr>
		<?php } ?>
        </table>
        </td>
    </tr>
</table>
</td>
</tr>



        <!-- item 1 -->
        <!--<tr valign="top">
           <td colspan="4" class="tdMyFolder" onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        	<table width="100%" border="0" class="fontMyFolderList">
            	<tr>
                	<td width="3%" rowspan="3" <?php echo $bgColor; ?>>&nbsp;</td>
                	<td width="18%" rowspan="3">
                    &nbsp;<img src="../picture/<?php echo $CReqAtk->detilAtkItem("itemimg", $itemId); ?>"/ width="90" height="78">
                	</td>
                	<td width="19%">
                    	<strong>Item Name</strong> 
                	</td>
                	<td colspan="2">
                    	<?php echo $CReqAtk->detilAtkItem("itemname", $itemId);?>
                	</td>
                	<td width="3%" rowspan="2" align="right" valign="top">
                	</td>
            	</tr>
            	<tr height="25">
                	<td>
                    	<strong>Qty</strong> 
                	</td>
                	<td width="31%">
                		<?php 
							echo $row['transqty']." ".$CReqAtk->detilAtkItem("qtytype", $itemId); 
						?>
                	</td>
                	<td width="26%" align="right">
                	<?php
					if($adminAtk == "Y" && $status == "Unread" || $adminAtk == "Y" && $status == "Processed")
					{
						echo "( ".$stock." ".$CReqAtk->detilAtkItem("qtytype", $itemId)." Available)&nbsp";
					} 
					?></td>
				</tr>
				<?php
                if($adminAtk == "Y" && $status == "Unread" || $adminAtk == "Y" && $status == "Processed")
                {
                ?>
                <tr height="25">
                    <td>
                        <strong>Edit Qty</strong>
                    </td>
                    <td colspan="2">
                         <input type="text" class="elementDefault" id="editQty<?php echo $detilId;?>" name="nmUnit" style="width:30;height:25px;" value="<?php echo $editQty;?>"> <?php echo $CReqAtk->detilAtkItem("qtytype", $itemId);?>
                         &nbsp;&nbsp;
                         <button id="btnCancel" style="width:80px;height:28px;" class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover';" onMouseOut="this.className='btnStandarKecil';" onClick="parent.calculate('<?php echo $transIdGet;?>','<?php echo $detilId;?>',document.getElementById('editQty'+<?php echo $detilId;?>).value);" title="Cancel Edit Master">
                            <table id="tblAdd" cellpadding="0" cellspacing="0" width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
                                <tr valign="top">
                                    <td align="center"><img src="../../picture/Button-Synchronize-blue-32.png"/ height="18px" style="vertical-align:middle;"></td>
                                    <td align="center">Calculate</td>
                                </tr>
                            </table>
                        </button>
                    </td>
                    <td>
                    </td>
                </tr>
                <?php 
                }
                 
                if($status == "Approved" || $status == "Completed")
                {
                ?>
                <tr height="25">
                    <td>
                        <strong>Received Qty</strong>
                    </td>
                    <td colspan="2">
                         <?php echo $row['aprvqty']." ".$CReqAtk->detilAtkItem("qtytype", $itemId);?>
                    </td>
                    <td>
                    </td>
                </tr>
                <?php } ?>
            </table>
            </td> 
        </tr>-->
<?php 
}
if($kurangStock == 0)
{
	echo "<script>completed('lebih');</script>";
}
else
{
	echo "<script>completed('kurang');</script>";
}
?>
        
		</table>
		</td>
	</tr>
</table>
</body>