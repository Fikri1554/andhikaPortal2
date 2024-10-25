<?php
require_once("../../config.php");

$userIdHistoryGet = $_GET['userIdHistory'];
$yearLogGet = $_GET['yearLog'];
$appLogGet = $_GET['appLog'];

?>
<script type="text/javascript" src="../../js/main.js"></script>
<body onLoad="loadScroll('halHistoryList');" onUnload="saveScroll('halHistoryList');">
<center>
<table width="98%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td valign="top" style="font:0.75em sans-serif;background-color:#FFFFFF;">
    <?php
	if($appLogGet == "arcDaily")
	{
		$logHistory = $CHistory->logHistory($yearLogGet, $userIdHistoryGet);
	}
	if($appLogGet == "invoiceReg")
	{
		$logHistory = $CHistory->logHistoryInv($yearLogGet, $userIdHistoryGet);
	}
	if($appLogGet == "qhse")
	{
		$logHistory = $CHistory->logHistoryQhse($yearLogGet, $userIdHistoryGet);	
	}
	if($appLogGet == "empl")
	{
		$logHistory = $CHistory->logHistoryEmpl($yearLogGet, $userIdHistoryGet);	
	}
	if($appLogGet == "reqAtk")
	{
		$logHistory = $CHistory->logHistoryReqAtk($yearLogGet, $userIdHistoryGet);	
	}
	if($appLogGet == "safir")
	{
		$logHistory = $CHistory->logHistorySafir($yearLogGet, $userIdHistoryGet);	
	}
	if($appLogGet == "spj")
	{
		$logHistory = $CHistory->logHistorySpj($yearLogGet, $userIdHistoryGet);	
	}
	if($appLogGet == "voucher")
	{
		$logHistory = $CHistory->logHistoryVoucher($yearLogGet, $userIdHistoryGet);	
	}
	if($appLogGet == "surveystatus")
	{
		$logHistory = $CHistory->logHistorySurStat($yearLogGet, $userIdHistoryGet);	
	}
	if($appLogGet == "jobRole")
	{
		$logHistory = $CHistory->logHistoryJobRole($yearLogGet, $userIdHistoryGet);	
	}
	echo $logHistory;
    ?>
    </td>
</tr>
</table>
</center>
</body>