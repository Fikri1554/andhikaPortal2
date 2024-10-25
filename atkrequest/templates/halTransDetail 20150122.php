<?php
require_once('../../config.php');
require_once('../configAtk.php');

$transIdGet = $_GET['transId'];

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

$disp = "none";
$status = $CReqAtk->atkTrans("status", $transIdGet);
$aprvDt = $CReqAtk->atkTrans("aprvdate", $transIdGet);
	$thnAprv =  substr($aprvDt,6,4);
	$blnAprv =  substr($aprvDt,10,2);
	$tglAprv =  substr($aprvDt,12,2);
	$timeAprv = substr($aprvDt,15,8);
$aprvDate = $CPublic->bulanSetengah($blnAprv, "eng")." ".$tglAprv.", ".$thnAprv." ".$timeAprv;

$compDt = $CReqAtk->atkTrans("compdate", $transIdGet);
	$thnComp =  substr($compDt,6,4);
	$blnComp =  substr($compDt,10,2);
	$tglComp =  substr($compDt,12,2);
	$timeComp = substr($compDt,15,8);
$compDate = $CPublic->bulanSetengah($blnComp, "eng")." ".$tglComp.", ".$thnComp." ".$timeComp;
if($status == "Approved" || $status == "Completed")
{
	$disp = "block";
	$trComplete = "<tr><td>&nbsp;</td><td></td><td></td></tr>";
	if($status == "Approved")
	{
		$img = "thumbs_up_48.png";
	}
	if($status == "Completed")
	{
		$img = "symbol_check.png";
		$trComplete = "<tr valign=\"middle\">
						<td><strong>Completed Date</strong></td>
						<td style=\"color: #00F;\">
							".$compDate."
						</td>
						<td></td>
						</tr>";
	}
}
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
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
</script>
<body onLoad="btnAprv();">
<table width="99%" cellpadding="0" cellspacing="0" border="0">
<input type="hidden" id="admAtk" name="admAtk" value="<?php echo $adminAtk;?>">
<input type="hidden" id="status" name="status" value="<?php echo $status;?>">
<tr valign="top">
	<td colspan="2" bgcolor="#FFFFFF" valign="top" align="center">
	<table cellpadding="0" cellspacing="5" width="100%" class="formInput" border="0">
<!-- Start of Status detail ========================================================================================= -->        
        <tr id="trAprv" style="display:<?php echo $disp;?>;">
        <td colspan="4">
       	<table width="100%" cellpadding="0" cellspacing="5" border="0" class="formInput">
        	<tr>
			<td colspan="3" align="center">- - - - - - - - - - - - - - - - - - - - - - - - STATUS DETAIL - - - - - - - - - -  - - - - - - - - - - -</td>
			<td width="1%"></td>
		</tr>
		<tr valign="middle">
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
		</tr>
        <tr height="5px">
        	<td colspan="3" style="border-top:dashed 1px #000000;">&nbsp;</td>
            <td width="0%"></td>
        </tr>
        </table>    
        </td>
        </tr>
<!-- End of Status detail ============================================================================================ -->
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
        <!-- item 1 -->
        <tr valign="top">
           <td colspan="4" class="tdMyFolder" onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        	<table width="100%" border="0" class="fontMyFolderList">
            	<tr>
                	<td width="3%" rowspan="3" <?php echo $bgColor; ?>>&nbsp;
                    
                </td>
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
                    <!--<img src="../../picture/Button-Cross-red-32.png" width="20" onClick="parent.cancel();" onMouseOver="this.style.backgroundColor='#FF888B';" onMouseOut="this.style.backgroundColor='transparent';" style="vertical-align:middle;" title="Cancel Item"/>-->
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
        </tr>
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