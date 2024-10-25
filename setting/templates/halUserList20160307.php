<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$pathFolder="../data/document/";
$userId = $userIdSession;
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../../../js/main.js"></script>

<body onLoad="loadScroll('halUserList');" onUnload="saveScroll('halUserList');">
    
<table width="100%">
<?php
$aksiGet = $_GET['aksi'];
$userIdGet = $_GET['userId'];

if($aksiGet == "deleteUser")
{
	$CKoneksi->mysqlQuery("UPDATE login SET deletests = 1, delusrdt = '".$CPublic->userWhoAct()."' WHERE userid = '".$userIdGet."' AND deletests=0;");
	$CHistory->updateLog($userIdLogin, "Hapus user (Nama = <b>".$CLogin->detilLogin($userIdGet, "userfullnm")."</b>)");
	
	$CHistory->updateLog($userIdGet, "<b> ***** USER INI TELAH DIHAPUS OLEH ADMIN *****</b>");
	$fileSek = $path."/data/history/".$userIdGet."_".date('Y').".tmp";
	$fileRename = $path."/data/history/Del-".$userIdGet."_".date('Y').".tmp";
	rename($fileSek, $fileRename);
}

$html = "";
$urutan = 1;
if($halamanGet == "cari")
{
	$paramCariGet = mysql_real_escape_string( $_GET['paramCari'] );
	$query = $CKoneksi->mysqlQuery("SELECT * FROM login WHERE userfullnm LIKE '%".$paramCariGet."%' AND deletests=0 ORDER BY userfullnm ASC");
}
else
{
	$query = $CKoneksi->mysqlQuery("SELECT * FROM login WHERE deletests=0 ORDER BY userfullnm ASC");
}
while($row = $CKoneksi->mysqlFetch($query))
{	
	$nmJab = $CLogin->detilLogin($row['userid'], "nmjabatan");
	$nmDept = $CLogin->detilLogin($row['userid'], "nmdept");

	$lastLogin = $CPublic->convTglBlnSingkat($row['lastlogin']);
	if($row['lastlogin'] == "00000000 00:00:00")
	{
		$lastLogin = "&nbsp;";
	}
	$lastLogout = "/ ".$CPublic->convTglBlnSingkat($row['lastlogout']);
	if($row['lastlogout'] == "00000000 00:00:00")
	{
		$lastLogout = "&nbsp;";
	}
	
	$html.="<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
            <td class=\"tdMyFolder\">
                <table width=\"100%\">
                <tr>
                    <td width=\"5%\" align=\"center\" style=\"font-size:22px;color:#000080;font-weight:bold;font-family:Tahoma;\" onclick=\"parent.openThickboxWindow('".$row['userid']."', 'editUser')\">".$urutan++."</td>
                    <td width=\"50%\" onclick=\"parent.openThickboxWindow('".$row['userid']."', 'editUser')\">
                    	<table width=\"100%\" class=\"fontMyFolderList\" style=\"background-repeat:no-repeat;background-position:right;\" background=\"../picture/imgFolder.png\">
                        <tr>
                        	<td width=\"130\">Name</td>
							<td width=\"10\">:</td>
							<td>
								<span style=\"color:#000080;\">".$row['userfullnm']."</span>
							</td>
                        </tr>
                        <tr>
                            <td>Rank</td><td>:</td><td>".$nmDept." - ".$nmJab."</td>
                        </tr>
                        <tr>
                            <td>Last Login / Logout</td><td>:</td><td>".$lastLogin." ".$lastLogout."</td>
                        </tr>
                        </table>
                    </td>
					<td width=\"45%\" align=\"right\">
						
                    	<button class=\"btnStandarKecil\" type=\"button\" onClick=\"alert('Detil User');\" style=\"width:75px;height:55px;\" title=\"Detail Of User\">
                            <table width=\"100%\" height=\"100%\">
                              <tr>
                                <td align=\"center\"><img src=\"../../picture/Information-blue-32.png\" height=\"25\"/> </td>
                                
                              </tr>
                              <tr>
                                <td align=\"center\">DETAIL</td>
                              </tr>
                            </table>
                        </button>
						&nbsp;
						<button class=\"btnStandarKecil\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['userid']."', 'editUser')\" style=\"width:75px;height:55px;\" title=\"Edit/View User Data\">
                            <table width=\"100%\" height=\"100%\">
                              <tr>
                                <td align=\"center\"><img src=\"../../picture/Auction-blue-32.png\" height=\"25\"/> </td>
                                
                              </tr>
                              <tr>
                                <td align=\"center\">EDIT</td>
                              </tr>
                            </table>
                        </button>
						&nbsp;
						<button class=\"btnStandarKecil\" type=\"button\" onclick=\"parent.deleteUser('".$row['userid']."');\" style=\"width:75px;height:55px;\" title=\"Delete User Data\">
                            <table width=\"100%\" height=\"100%\">
                              <tr>
                                <td align=\"center\"><img src=\"../../picture/Button-Cross-blue-32.png\" height=\"25\"/> </td>
                                
                              </tr>
                              <tr>
                                <td align=\"center\">DELETE</td>
                              </tr>
                            </table>
                        </button>
						&nbsp;
                    </td>
                </tr>
                </table>
            </td>
        </tr>";
}
echo $html;
?>

</table>
</body>
</HTML>