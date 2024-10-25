<?php
require_once("../../config.php");

$userIdSelect = $CLogin->detilLoginByEmpno($_GET['empNo'], "userid");
$blnGet = $_GET['bulan'];
$thnGet = $_GET['tahun'];
$paramCariGet = $_GET['paramCari'];
$SubOrNo = "notSubordinate";
if($userEmpNo != $_GET['empNo'])
{
	$SubOrNo = "subordinate";
}

?>
<script type="text/javascript" src="../../js/main.js"></script>
<link href="../../css/main.css" rel="stylesheet" type="text/css"/>
<link href="../../css/archives.css" rel="stylesheet" type="text/css"/>
<link href="../../andhikaportal/css/main.css" rel="stylesheet" type="text/css"/>
<link href="../../andhikaportal/css/archives.css" rel="stylesheet" type="text/css"/>

<body onLoad="loadScroll('halSubordinateMonthlyActList');parent.document.getElementById('idDivAnimateLoading').innerHTML = '&nbsp;';" onUnload="saveScroll('halSubordinateMonthlyActList')"> 
<table width="100%">
<?php
$html = "";
$urutan = 1;
if($aksiGet == "cari")
{
	$query = $CKoneksi->mysqlQuery("SELECT * FROM tblactivity WHERE ownerid = '".$userIdSelect."' AND bulan = '".$blnGet."' AND tahun = '".$thnGet."' AND activity LIKE '%".$paramCariGet."%' AND deletests = 0 ORDER BY tanggal ASC");
}
else
{
	$query = $CKoneksi->mysqlQuery("SELECT * FROM tblactivity WHERE ownerid = '".$userIdSelect."' AND bulan = '".$blnGet."' AND tahun = '".$thnGet."' AND deletests = 0 ORDER BY tanggal ASC");
}

while($row = $CKoneksi->mysqlFetch($query))
{	
	if($row['status'] == "onprogress")
	{
		$status = "<span style=\"color:#CC0;\">On Progress</span>";
	}
	if($row['status'] == "postpone")
	{
		$status = "<span style=\"color:#C00;\">Postpone</span>";
	}
	if($row['status'] == "finish")
	{
		$status = "<span style=\"color:#090;\">Finish</span>";
	}
	
	$activity = $CPublic->potongKarakter($row['activity'], 45);
	$relatedInfo = $CPublic->potongKarakter($row['relatedinfo'], 70) ;
	$date = $row['tanggal'];
	$dateAct = $row['tanggal']."-".$row['bulan']."-".$row['tahun'];
	if($date%2 == 0)
	{
		$color = "#F5F5F5";
	}
	if($date%2 != 0)
	{
		$color = "#FFFFFF";
	}
	
	if($row['cuti'] == "Y" || $row['sakit'] == "Y")
	{
		$activity = $row['activity'];
		$relatedInfo = "&nbsp;";
		$statusComment = "&nbsp;";
	}
	$html.="<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\" onclick=\"parent.openThickboxWindow('".$row['idactivity']."','".$SubOrNo."', '');\">
            <td class=\"tdMyFolder\">
                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                <tr style=\"height:23px;\">
					<td align=\"center\" style=\"font-size:12px;color:#006;font-weight:bold;font-family:Tahoma;\" width=\"4%\">".$urutan++."</td>
					<td align=\"center\" width=\"4%\" style=\"color:#006;font-family:tahoma;font-size:13px;\">".$row['tanggal']."</td>
					<td width=\"32%\" align=\"left\" style=\"color:#006;font-family:tahoma;font-size:13px;\">&nbsp;".$CPublic->konversiQuotes1($activity)."</td>
					<td width=\"50%\" align=\"left\" style=\"color:#006;font-family:tahoma;font-size:13px;\">&nbsp;".$relatedInfo."</td>
					<td align=\"center\" width=\"10%\" style=\"color:#006;font-family:tahoma;font-size:13px;font-weight:bold;\">".$status."</td>       
				</tr>	
                </table>
            </td>
        </tr>";
}
echo $html;
?>

</table>
</body>