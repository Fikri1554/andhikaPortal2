<!DOCTYPE HTML>
<?php
require_once('../../config.php');

$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><td height="5px"></td></tr>
<tr>
	<td>
    <div style="float:left;margin-left:10px;">
    <?php
	$jamKerja1 = 0;
	$query = $CKoneksi->mysqlQuery("SELECT * FROM tblmstdiv ORDER BY nmdiv ASC LIMIT 0,8;");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$jmlMbrDiv = $COtherReport->jmlMbrDiv($row['kddiv']);
		if($jmlMbrDiv != 0)
		{
	?>
    <table cellpadding="0" cellspacing="0" border="0">
    <tr style="font-family:Arial;font-size:13px;font-weight:bold;">
    	<td colspan="3"> <?php echo $row['nmdiv'];?>
    </tr>
    <tr style="background-color:#95C8FF;color:#000;font-family:Arial;font-size:13px;font-weight:bold;" height="28px;">
    	<td align="center" width="40px">
        	No.
        </td>
        <td align="center" width="325px">
        	Name
        </td>
        <td align="center" width="110px">
        	Term(hour)
        </td>
    </tr>
		<?php
        $i = 1;
        $queryUser = $CKoneksi->mysqlQuery("SELECT userfullnm,userid FROM login WHERE kddiv=".$row['kddiv']." AND active = 'Y' AND deletests = 0 ORDER BY userfullnm ASC");
        while($rowUser = $CKoneksi->mysqlFetch($queryUser))
        {
            //$COtherReport->pheonwj($rowUser['userid'], $fromDate, $toDate);
			$jam1= $COtherReport->pheonwj($rowUser['userid'], $fromDate, $toDate);
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
                &nbsp;<?php echo $rowUser['userfullnm'];?>
            </td>
            <td align="center" width="110px">
                <?php echo $COtherReport->pheonwj($rowUser['userid'], $fromDate, $toDate);?>
            </td>
        </tr>
        <?php
        $i++;}
		//echo "<input type=\"hidden\" value=\"".$jamKerja1."\"/>";
        ?>
    <tr><td colspan="3" height="5px"></td></tr>
    </table>
    <?php
		}
	}
	?>
    </div>
    
    <div style="float:left;margin-left:10px;">
    <?php
	$jamKerja2 = 0;
	$query = $CKoneksi->mysqlQuery("SELECT * FROM tblmstdiv ORDER BY nmdiv ASC LIMIT 8,9;");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$jmlMbrDiv = $COtherReport->jmlMbrDiv($row['kddiv']);
		if($jmlMbrDiv != 0)
		{
	?>
    <table cellpadding="0" cellspacing="0" border="0">
    <tr style="font-family:Arial;font-size:13px;font-weight:bold;">
    	<td colspan="3"> <?php echo $row['nmdiv'];?>
    </tr>
    <tr style="background-color:#95C8FF;color:#000;font-family:Arial;font-size:13px;font-weight:bold;" height="28px;">
    	<td align="center" width="40px">
        	No.
        </td>
        <td align="center" width="325px">
        	Name
        </td>
        <td align="center" width="110px">
        	Term(hour)
        </td>
    </tr>
		<?php
        $i = 1;
        $queryUser = $CKoneksi->mysqlQuery("SELECT userfullnm,userid FROM login WHERE kddiv=".$row['kddiv']." AND active = 'Y' AND deletests = 0 ORDER BY userfullnm ASC");
        while($rowUser = $CKoneksi->mysqlFetch($queryUser))
        {
            //$COtherReport->pheonwj($rowUser['userid'], $fromDate, $toDate);
			$jam2= $COtherReport->pheonwj($rowUser['userid'], $fromDate, $toDate);
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
                &nbsp;<?php echo $rowUser['userfullnm'];?>
            </td>
            <td align="center" width="110px">
                <?php echo $COtherReport->pheonwj($rowUser['userid'], $fromDate, $toDate);?>
            </td>
        </tr>
        <?php
        $i++;}
		//echo "<input type=\"hidden\" value=\"".$jamKerja2."\"/>";
        ?>
    <tr><td colspan="3" height="5px"></td></tr>
    </table>
    <?php
		}
	}
	?>
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