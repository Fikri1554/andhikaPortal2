<?php
require_once("../../config.php");

$dateActGet = $_GET['dateAct'];
$empNoGet = $_GET['empNo'];

$tgl =  substr($dateActGet,0,2);
$bln =  substr($dateActGet,3,2);
$thn =  substr($dateActGet,6,4);
$userIdSelect = $CLogin->detilLoginByEmpno($empNoGet, "userid");
$userNameSelect = $CLogin->detilLoginByEmpno($empNoGet, "userfullnm");

if($aksiGet == "simpanReason")
{
	// pertama backup data tabel activity yang akan di unapprove
	$CKoneksi->mysqlQuery("INSERT INTO tblactivityrev (idactivity, urutan, idrevisi, revisike, ownerid, ownername, tanggal, bulan, tahun, fromtime, totime, activity, relatedinfo, status, problemident, corrective, kpinumber, spvcomment, oldspvcomment, bosread, bosreadjob, bosapprove, readbyid, readbyname, approvebyid, approvebyname, addusrdt) 
SELECT idactivity, urutan, idrevisi, revisike, ownerid, ownername, tanggal, bulan, tahun, fromtime, totime, activity, relatedinfo, status, problemident, corrective, kpinumber, spvcomment, oldspvcomment, bosread, bosreadjob, bosapprove, readbyid, readbyname, approvebyid, approvebyname, '".$CPublic->userWhoAct()."' FROM tblactivity 
WHERE ownerid='".$userIdSelect."' AND tanggal='".$tgl."' AND bulan='".$bln."' AND tahun='".$thn."' AND deletests=0"); 

	$revisiKe = ($CDailyAct->detilActByDay($userIdSelect, $tgl, $bln, $thn, "revisike")+1);
	
	// kedua insert row baru di tblrevisi
	$reasonPost = mysql_real_escape_string( $_POST['reason'] );
	$CKoneksi->mysqlQuery("INSERT INTO tblrevisi (revisike, tanggal, bulan, tahun, ownerid, ownername, reason, addusrdt) VALUES ('".$revisiKe."', '".$tgl."', '".$bln."', '".$thn."', '".$userIdSelect."', '".$userNameSelect."', '".$reasonPost."', '".$CPublic->userWhoAct()."')");
	$lastIdActivity = mysql_insert_id();
	
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET bosapprove='N', idrevisi='".$lastIdActivity."', revisike='".$revisiKe."', updusrdt='".$CPublic->userWhoAct()."' WHERE ownerid='".$userIdSelect."' AND tanggal='".$tgl."' AND bulan='".$bln."' AND tahun='".$thn."' AND deletests=0");

	$CHistory->updateLog($userIdLogin, "Unapprove / Buka Approved Job Daily dari ".$CLogin->detilLoginByEmpno($empNoGet, "userfullnm")." (idrevisi = <b>".$lastIdActivity."</b>, ownerid = <b>".$userIdSelect."</b>, tanggal=<b>".$tgl."</b>, bulan=<b>".$bln."</b>, tahun=<b>".$thn."</b>)");
}
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script>
function exit()
{
	parent.tb_remove(false);
	parent.setelahKlikBtnApprove('<?php echo $dateActGet; ?>', '<?php echo $empNoGet; ?>');
}

function klikBtnReason()
{
	var reason = document.getElementById('reason').value; 

	if(reason.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').innerHTML = "Reason still empty!";
		document.getElementById('reason').focus(); 
		return false;
	}
	formReason.submit();
}
</script>




<body bgcolor="#F8F8F8">
<table cellpadding="0" cellspacing="0" width="100%" height="98%" border="0">
<tr valign="top">
	<td>
        <span class="teksLvlFolder" style="color:#666;">DATE ACTIVITY : </span><span class="teksLvlFolder" style="text-decoration:underline;"><?php echo $dateActGet; ?></span>
    </td>
</tr>

<tr><td height="5"></td></tr>
<form name="formReason" method="post" action="halUnapprove.php?aksi=simpanReason&empNo=<?php echo $empNoGet; ?>&dateAct=<?php echo $dateActGet; ?>">
<tr>
	<td align="center" valign="top" height="300" bgcolor="#FFFFFF" class="tdMyFolder">
    	<table cellpadding="0" cellspacing="5" width="100%" class="formInput" style="cursor:default;">
        <tr valign="top">
        	<td width="20%">Reason</td>
            <td>
            	<textarea class="elementDefault" id="reason" name="reason" style="width:98%;height:300px;"></textarea>
            </td>
        </tr>
        <tr><td colspan="2" height="20" align="center" valign="middle">&nbsp; <span id="errorMsg" class="errorMsg">&nbsp;</span></td></tr>
        </table>
    </td>
</tr>

</form>

<tr><td height="5"></td></tr>

<tr valign="top">
	<td class="tdMyFolder" style="cursor:default;" bgcolor="#FFFFFF" height="65" valign="middle" colspan="2">&nbsp;
    	<button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" type="button" style="width:90px;height:55px;" onClick="exit();" title="Close Window">
            <table width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
              <tr>
                <td align="center"><img src="../../picture/Metro-Shut-Down-Blue-32.png" height="25"/> </td>
                
              </tr>
              <tr>
                <td align="center">CLOSE</td>
              </tr>
            </table>
        </button>
        &nbsp;
        <button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" type="button" style="width:95px;height:55px;" onClick="klikBtnReason();" title="Save">
            <table width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
              <tr>
                <td align="center"><img src="../../picture/Floppy-Disk-blue-32.png" height="25"/> </td>
                
              </tr>
              <tr>
                <td align="center">SAVE</td>
              </tr>
            </table>
        </button>
    </td>
</tr>

</table>
</body>

<script>
<?php
if($aksiGet == "simpanReason")
{
	echo "exit();";
}
?>
</script>