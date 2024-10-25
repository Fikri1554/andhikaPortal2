<!DOCTYPE HTML>
<?php
require_once("../configVslRep.php");

$aksiGet = $_GET['aksi'];
?>
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../js/vslRep.js"></script>
<script type="text/javascript" src="../js/popup.js"></script>

<link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>
<link href="../css/vslRep.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />
<link href="../css/button.css" rel="stylesheet" type="text/css" />

<body>
<center>
<style>
body {background-color: #f9f9f9;}
</style>
<?php
if($aksiGet == "checkNotif")
{ 
	$reportTypeGet = $_GET['reportType'];
	?>
    <input type="hidden" id="idMsg" name="idMsg">
	<div style="position:absolute;top:5px;left:5px;font:15px sans-serif; font-weight:bold;">Message Notification from System</div>
    <div style="position:absolute;top:0px;right:0px;">
    <button type="button" class="btnStandar" id="btnRefresh" onclick="klikBtnRefresh('<?php echo $reportTypeGet; ?>');return false;"><table cellpadding="0" cellspacing="0" width="20" height="20"><tr><td align="center"><img src="../picture/arrow-circle-315.png" style="vertical-align:middle;"/></td></tr></table></button>
    <button type="button" class="btnStandar" id="btnRefresh" onclick="klikBtnExit();return false;"><table cellpadding="0" cellspacing="0" width="20" height="20"><tr><td align="center"><img src="../picture/cross.png" style="vertical-align:middle;"/></td></tr></table></button>
    </div>
	<div style="position:absolute; border: solid 1px #CCC; top:25px; left:5px; width:403px; height:144px; text-align:left;">  
    	<iframe width="100%" height="100%" src="../templates/halNotifList.php?aksi=<?php echo $aksiGet; ?>&reportType=<?php echo $reportTypeGet; ?>" target="iframeList" name="iframeList" id="iframeList" frameborder="0" marginwidth="0" marginheight="0" scrolling="yes"></iframe>  
    </div>
    <div style="position:absolute;border-bottom:dotted 1px #CCC;top:177px;left:5px;width:405px;"></div>
    <div style="position:absolute;border-top:dotted 1px #CCC;top:179px;left:5px;width:405px;"></div>
    
	<div id="divDetailPesan" style="position:absolute; top:186px; left:5px; vertical-align:top; border:solid 1px #CCC; width:403px; height:200px; background-color:#FFF;font:12px Arial;text-align:justify;padding:5px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"></div>
<?php
}
?>

</center>
</body>
</HTML>