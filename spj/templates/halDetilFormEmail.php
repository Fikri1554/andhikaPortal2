<!DOCTYPE HTML>
<?php
require_once("../../config.php");
require_once("../configSpj.php");

$formId = $_GET['formId'];

?>

<link href="../css/cssSpj.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>

<script>
//Automatically setting height of textarea to height of its contents
$(function() {
	 $('textarea').height($('textarea').prop('scrollHeight'));
});
</script>
<?php
$subject = "SPJ App -- New SPJ From";
		$isiMessage.= "<table border=0 width=\"100%\" style=\"font-family:Arial Narrow;\" cellpadding=\"0\" cellspacing=\"0\"> 
					  <tr>
						  <td>
							  *************************************************<br>
							  PLEASE DO NOT REPLY THIS EMAIL!<br>
							  *************************************************
							  <br><br>
						  </td>
					  </tr>
					  <tr>
						  <td align=\"center\">
							 <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
							 <tr>
								  <td align=\"center\">&nbsp;</td>
							  </tr>
							  <!-- JUDUL -->
							  <tr>
								  <td align=\"left\">
									<b>&nbsp;*****  
									Your SPJ Form has been Approved
									*****</b>
									".$emailKe."
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td style=\"border:#CCC solid 1px;font-family:Tahoma;font-size:12px;color:#333;\">
						   <table width=\"700px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								".$CSpj->detilFormEmail($formId, $CPublic, $db)."
						   	</table>
						  </td>
						</tr>
						<tr height=\"15\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td align=\"center\">
							<table style=\"font-family:Tahoma;font-size:12px;color:#333;\"  cellpadding=\"0\" cellspacing=\"3\" width=\"98%\">
								".$CSpj->dataAprovalForm($formId, $CPublic, $db)."
							</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							To see more, please check <a href=\"".$link."/\">Andhika Portal</a>.
						</td>
					    </tr>";

echo $isiMessage;
?>
</HTML>