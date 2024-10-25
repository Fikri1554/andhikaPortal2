<?php
require_once("../configInvReg.php");
?>

<link href="../css/invReg.css" rel="stylesheet" type="text/css" />
<?php
$aksiGet = $_GET['aksi'];
$paramGet = $_GET['param'];
echo $paramGet;

$i = 1;
$html = "";

$html.= "<table cellpadding=\"0\" cellspacing=\"0\" style=\"width:100%;\" class=\"tabelDetailCari\">";

$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT Acctcode, AcctIndo, Acctname FROM AccountCode WHERE Acctname LIKE '%".$term."%' AND SUBSTRING(Acctcode ,1,2) = '12' AND LEN(RTRIM(Acctcode))=5 ORDER BY Acctname ASC;");
while($row = $koneksiOdbcAcc->odbcFetch($query))
{
	$i++;
    $rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
			
	$html.="<tr onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\" style=\"cursor:pointer;\">";
	$html.="	<td height=\"20\"><a>".$row['Acctname']."</a></td>";
	$html.="</tr>";
}
$html.= "</table>";

echo $html;
?>