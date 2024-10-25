<!DOCTYPE HTML>
<?php
require_once("../configInvReg.php");

$aksiGet = $_GET['aksi'];
?>

<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../js/payment.js"></script>
<link href="../css/button.css" rel="stylesheet" type="text/css" />

<body>
<center>
<style>
body {background-color: #f9f9f9;}
</style>
<?php
if($aksiGet == "suksesAssignTransno")
{
?>
	 <div style="position:inherit; border: solid 1px #CCC; width:100%; height:174px; text-align:center; background-color:#FFF;">
     	<div style="position:relative; top:10px;"><img src="../picture/Button-Check-blue-48.png"></div>
        <div style="position:relative; top:5px; font:0.8em sans-serif; line-height:30px; font-weight:bold;">Congrulations, You have successfully Assigned</div>
        <div style="position:relative; top:5px; font:1.2em sans-serif; line-height:30px; color:#096;"><span style=" font:0.8em sans-serif;">Transaction No</span><b> <?php echo $CPublic->zerofill($CInvReg->lastTransNo(), 6); ?> </b></div>
        <div style="position:relative; top:10px;">
            <button class="btnStandar" id="" title="CLOSED" onclick="closePopupAssign();return false;">
                <table width="75" height="40">
                <tr>
                    <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                    <td align="left">CLOSED</td> 
                </tr>
                </table>
            </button>
        </div>
     </div>
<?php	
}
if($aksiGet == "suksesPaid")
{
	$idMailInvGet = $_GET['idMailInv'];
	$barisTransnoGet = $_GET['barisTransno'];
	$transNo = $CInvReg->detilMailInv($idMailInvGet, "transno")
?>
		<div style="position:inherit; border: solid 1px #CCC; width:100%; height:174px; text-align:center; background-color:#FFF;">
     	<div style="position:relative; top:10px;"><img src="../picture/Button-Check-blue-48.png"></div>
        <div style="position:relative; top:5px; font:0.8em sans-serif; line-height:30px; font-weight:bold;">Congrulations, You have successfully Transfer to Accounting</div>
        <div style="position:relative; top:5px; font:1.2em sans-serif; line-height:30px; color:#096;"><span style=" font:0.8em sans-serif;">Transaction No</span><b> <?php echo $CPublic->zerofill($transNo, 6); ?> </b></div>
        <div style="position:relative; top:10px;">
            <button class="btnStandar" id="" title="CLOSED" onclick="parent.closePopupBatch('<?php echo $barisTransnoGet; ?>', '<?php echo $idMailInvGet; ?>', '<?php echo $transNo; ?>');return false;">
                <table width="75" height="40">
                <tr>
                    <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                    <td align="left">CLOSED</td> 
                </tr>
                </table>
            </button>
        </div>
     </div>
<?php
}
if($aksiGet == "cancelPayment")
{
	$idMailInvGet = $_GET['idMailInv'];
	$barisTransnoGet = $_GET['barisTransno'];
	$transNo = $CInvReg->detilMailInv($idMailInvGet, "transno");
?>
		<div style="position:inherit; border: solid 1px #CCC; width:98%; height:174px; text-align:center; background-color:#FFF;text-align:left;padding-left:5px;">
        <div style="position:relative; top:5px; font:1.2em sans-serif; line-height:30px; color:#096;"><span style=" font:0.8em sans-serif;">Transaction No</span><b> <?php echo $CPublic->zerofill($transNo, 6); ?> </b></div>
        <div style="position:relative; top:5px; font:0.8em sans-serif; line-height:30px; font-weight:bold;">Reason</div>
        <div style="position:relative; top:5px; font:1.2em sans-serif; line-height:30px; color:#096;">
        	<textarea id="reasonCancelPay" name="reasonCancelPay" class="elementInput" cols="40" style="height:50px;" onkeyup="parent.textCounter(this, sisaReason, 70);"></textarea>
            <input type="text" name="" id="sisaReason" class="elementInput" style="width:13px;height:13px;line-height:13px;" value="70" disabled readonly/>
        </div>
        <div style="position:relative; top:10px;">
            <button class="btnStandar" id="" title="SAVE AND CLOSE CANCEL PAYMENT" onclick="parent.saveCancelPayment('<?php echo $barisTransnoGet; ?>', '<?php echo $idMailInvGet; ?>', $('#reasonCancelPay').val());return false;">
                <table width="115" height="40">
                <tr>
                    <td align="center" width="20"><img src="../picture/disk-black.png"/></td>
                    <td align="left">SAVE & CLOSED</td> 
                </tr>
                </table>
            </button>
            <button class="btnStandar" id="" title="CANCEL" onclick="parent.closePopupBatch('<?php echo $barisTransnoGet; ?>', '<?php echo $idMailInvGet; ?>', '<?php echo $transNo; ?>');return false;">
                <table width="75" height="40">
                <tr>
                    <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                    <td align="left">CANCEL</td> 
                </tr>
                </table>
            </button>
        </div>
     </div>
<?php
}
?>

</center>
</body>
</HTML>