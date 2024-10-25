<?php
require_once("../../config.php");

$aksiGet = $_GET['aksi'];
$empNoGet = $_GET['empNo'];
$userIdSelectGet = $_GET['userIdSelect'];
?>
<script type="text/javascript">
<?php
if($aksiGet == "dailyActViewMonth" || $aksiGet == "openSubordinateDailyActBalik")
{
?>
		parent.rubahDate('<?php echo $empNoGet; ?>', '<?php echo $userIdSelectGet; ?>');
<?php	
}
//if($aksiGet == "openSubordinateDailyAct" || $aksiGet == "openSubordinateDailyActBalik")
?>
</script>

<table cellpadding="0" cellspacing="0" width="100%" height="100%">
<tr>
    <td align="center">
    
        <table cellpadding="0" cellspacing="0" width="99%">
        <tr align="center">
            <td height="100%" style="font-family:sans-serif;font-weight:bold;font-size:30px;color:#CCC;">PLEASE SELECT BUTTON ABOVE</td>
        </tr>
        </table>
    </td>
</tr>
</table>