<!DOCTYPE HTML>
<?php
require_once('../../config.php');

$jmlUser = $COtherReport->jmlUser();
if($jmlUser%2 != 0)
{
	$jmlUser = $COtherReport->jmlUser()+1;
};
$halfUser = $jmlUser/2;
//$halfUser = round($jmlUser/2);
	
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>

<script>
$(document).ready(function() {
    parent.tampilLoad('none');
});
</script>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><td height="5px"></td></tr>
<tr>
	<td>
    <div style="float:left;margin-left:10px;">
    <table cellpadding="0" cellspacing="0" border="0">
    <tr style="background-color:#95C8FF;color:#000;font-family:Arial;font-size:13px;font-weight:bold;" height="28px;">
    	<td align="center" width="40px">
        	No.
        </td>
        <td align="center" width="325px">
        	Name
        </td>
        <td align="center" width="110px">
        	Term (hour)
        </td>
    </tr>
    <?php
	$i = 1;
	$jamKerja1 = 0;
	$query = $CKoneksi->mysqlQuery("SELECT userfullnm,userid FROM login WHERE deletests = 0 AND active = 'Y' ORDER BY userfullnm ASC LIMIT 0,".$halfUser."");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$jam1= $COtherReport->pheonwj($row['userid'], $fromDate, $toDate);
		$jamKerja1 = $jamKerja1 + $jam1;
		$clr = "#F5FEFE";
		if($i%2 == 0)
		{
			$clr = "#DDF0FF";
		}
	?>
    <tr style="background-color:<?php echo $clr;?>;color:#000;font-family:Arial;font-size:13px;" height="20px;">
    	<td align="center" width="40px" style="color:#000080;font-weight:bold;font-family:Tahoma;">
        	<?php echo $i;?>
        </td>
        <td align="left" width="325px">
        	&nbsp;<?php echo $row['userfullnm'];?>
        </td>
        <td align="center" width="110px">
        	<?php echo $jam1?>
        </td>
    </tr>
    <?php
	$i++;}//echo $jamKerja1;
	?>
    </table>
    </div>
    
    <div style="float:left;margin-left:10px;">
    <table cellpadding="0" cellspacing="0" border="0">
    <tr style="background-color:#95C8FF;color:#000;font-family:Arial;font-size:13px;font-weight:bold;" height="28px;">
    	<td align="center" width="40px">
        	No.
        </td>
        <td align="center" width="325px">
        	Name
        </td>
        <td align="center" width="110px">
        	Term (hour)
        </td>
    </tr>
    <?php
	$i = $halfUser+1;
	$jamKerja2 = 0;
	$query = $CKoneksi->mysqlQuery("SELECT userfullnm,userid FROM login WHERE deletests = 0 AND active = 'Y' ORDER BY userfullnm ASC LIMIT ".$halfUser.",".$halfUser."");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$jam2= $COtherReport->pheonwj($row['userid'], $fromDate, $toDate);
		$jamKerja2 = $jamKerja2 + $jam2;
		
		$clr = "#F5FEFE";
		if($i%2 == 0)
		{
			$clr = "#DDF0FF";
		}
	?>
    <tr style="background-color:<?php echo $clr;?>;color:#000;font-family:Arial;font-size:13px;" height="20px;">
    	<td align="center" width="40px" style="color:#000080;font-weight:bold;font-family:Tahoma;">
        	<?php echo $i;?>
        </td>
        <td align="left" width="325px">
        	&nbsp;<?php echo $row['userfullnm'];?>
        </td>
        <td align="center" width="110px">
        	<?php echo $jam2;?>
        </td>
    </tr>
    <?php
	$i++;}//echo $jamKerja2;
	?>
    </table>
    </div>
    </td>
</tr>
<tr style="font-family:Arial;font-size:13px;" height="20px;">
    <td align="left" style="color:#000080;font-weight:bold;font-family:Tahoma;">
        &nbsp;&nbsp;&nbsp;All Total (hours) : 
		<?php 
			$total = $jamKerja1 + $jamKerja2;
			echo $total;
		?> hours&nbsp;&nbsp;
    </td>
</tr>
</table>
</HTML>