<?php
require_once('../../config.php');
require_once('../configAtk.php');

$itemIdGet = $_GET['itemId'];
$qty = $_GET['qty'];
$itemImg = $CReqAtk->detilAtkItem("itemimg", $itemIdGet);
$itemName = $CReqAtk->detilAtkItem("itemname", $itemIdGet);
?>
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<body>
<center>
<table width="100%"cellspacing="0" cellpadding="0" border="0">
<tr>
	<td colspan="3">
    	<img src="../picture/<?php echo $itemImg;?>" width="450" height="390"/>
    </td>
</tr>
<tr height="20" valign="middle" style="background:#666;font-family:Tahoma;font-size:12px;color:#FFF;">
	<td width="2%">&nbsp;</td>
	<td width="83%" align="left">
		<?php echo $itemName?>
    </td>
    <td width="15%" align="center" style="background:#0074F4;">		
    	<span style="font-weight:bold;font-size:11px;"><?php echo $qty;?></span>
    </td>
</tr>
</table>
<div style="position:absolute;top:0;right:0;" onMouseOver="this.style.cursor='pointer'">
<table width="100%%"cellspacing="0" cellpadding="0">
<tr>
	<td align="right">
		<img src="../../picture/cross-circle.png" class="btnClose" onMouseOver="this.className='btnCloseHover';" onMouseOut="this.className='btnClose';" onClick="this.className='btnClose';parent.tutup();" title="Close"/>
    </td>
</tr>
</div>
</center>
</body>
</html>